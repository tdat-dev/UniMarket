<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Middleware\VerificationMiddleware;

class CartController extends BaseController
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
    }

    /**
     * Kiểm tra user đã đăng nhập chưa
     */
    private function getUserId()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * Thêm sản phẩm vào giỏ hàng hoặc Mua ngay
     */
    public function add()
    {
        $userId = $this->getUserId();
        $action = $_POST['action'] ?? 'add';
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int) ($_POST['quantity'] ?? 1);

        // Bắt buộc đăng nhập mới được mua hàng
        if (!$userId) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['error'] = 'Vui lòng đăng nhập để mua hàng.';
            $_SESSION['redirect_after_login'] = $_SERVER['HTTP_REFERER'] ?? '/products';
            header('Location: /login');
            exit;
        }

        VerificationMiddleware::requireVerified();

        if (!$productId) {
            header('Location: /products');
            exit;
        }

        // Check if user is buying their own product
        $productModel = new Product();
        $product = $productModel->find($productId);

        if ($product && $product['user_id'] == $userId) {
            $_SESSION['error'] = 'Bạn không thể mua sản phẩm của chính mình!';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/products'));
            exit;
        }

        // Xử lý theo action
        if ($action === 'buy') {
            // MUA NGAY: Đi thẳng checkout, KHÔNG thêm vào giỏ hàng
            echo '<form id="buy_now_form" action="/checkout" method="POST">';
            echo '<input type="hidden" name="selected_products[]" value="' . $productId . '">';
            echo '<input type="hidden" name="quantities[' . $productId . ']" value="' . $quantity . '">';
            echo '</form>';
            echo '<script>document.getElementById("buy_now_form").submit();</script>';
            exit;
        } else {
            // THÊM VÀO GIỎ: Lưu vào Database
            $this->cartModel->addItem($userId, $productId, $quantity);
            header("Location: /product-detail?id=$productId&added=1");
            exit;
        }
    }

    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $userId = $this->getUserId();

        // Bắt buộc đăng nhập mới được xem giỏ hàng
        if (!$userId) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['error'] = 'Vui lòng đăng nhập để xem giỏ hàng.';
            header('Location: /login');
            exit;
        }

        VerificationMiddleware::requireVerified();

        $products = [];
        $total = 0;

        // Lấy giỏ hàng từ Database
        $cartItems = $this->cartModel->getByUserId($userId);
        foreach ($cartItems as $item) {
            $item['cart_quantity'] = $item['quantity'];
            $products[] = $item;
            $total += $item['price'] * $item['quantity'];
        }

        $this->view('cart/index', [
            'products' => $products,
            'total' => $total
        ]);
    }

    /**
     * Cập nhật số lượng sản phẩm
     */
    public function update()
    {
        $userId = $this->getUserId();

        // Get raw POST data for JSON
        $input = json_decode(file_get_contents('php://input'), true);

        $productId = $input['product_id'] ?? $_POST['product_id'] ?? null;
        $quantity = isset($input['quantity']) ? (int) $input['quantity'] : (isset($_POST['quantity']) ? (int) $_POST['quantity'] : null);

        if (!$productId || $quantity === null) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        if ($userId) {
            // Đã đăng nhập -> Cập nhật Database
            $this->cartModel->updateQuantity($userId, $productId, $quantity);
        } else {
            // Chưa đăng nhập -> Cập nhật Session
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if ($quantity <= 0) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId] = ['quantity' => $quantity];
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Xóa sản phẩm khỏi giỏ
     */
    public function remove()
    {
        $userId = $this->getUserId();
        $productId = $_POST['product_id'] ?? $_GET['product_id'] ?? null;

        if ($productId) {
            if ($userId) {
                $this->cartModel->removeItem($userId, $productId);
            } else {
                unset($_SESSION['cart'][$productId]);
            }
        }

        header('Location: /cart');
        exit;
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        $userId = $this->getUserId();

        if ($userId) {
            $this->cartModel->clearCart($userId);
        } else {
            $_SESSION['cart'] = [];
        }

        header('Location: /cart');
        exit;
    }

    /**
     * Đếm số lượng sản phẩm trong giỏ (cho header badge)
     */
    public function count()
    {
        $userId = $this->getUserId();
        $count = 0;

        if ($userId) {
            $count = $this->cartModel->countItems($userId);
        } else {
            $cart = $_SESSION['cart'] ?? [];
            foreach ($cart as $item) {
                $count += is_array($item) ? ($item['quantity'] ?? 1) : $item;
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }
}
