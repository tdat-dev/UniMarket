<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\VerificationMiddleware;
use App\Models\Product;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Services\EscrowService;

/**
 * Checkout Controller
 * 
 * Xử lý quy trình checkout và tạo đơn hàng.
 * 
 * @package App\Controllers
 */
class CheckoutController extends BaseController
{
    /**
     * Hiển thị trang checkout
     */
    public function process(): void
    {
        VerificationMiddleware::requireVerified();
        $user = $this->requireAuth();
        $userId = (int) $user['id'];

        $allCart = $this->getCartItems($userId);
        $selectedIds = $_POST['selected_products'] ?? [];

        if (empty($selectedIds)) {
            $this->redirect('/cart');
        }

        $productModel = new Product();
        $products = [];

        foreach ($selectedIds as $id) {
            $qty = $this->getQuantityForProduct((int) $id, $allCart);

            if ($qty > 0) {
                $product = $productModel->find((int) $id);

                if ($product !== null) {
                    // Không cho phép mua sản phẩm của chính mình
                    if ((int) $product['user_id'] === $userId) {
                        $_SESSION['error'] = 'Bạn không thể mua sản phẩm "' . $product['name'] . '" của chính mình!';
                        $this->redirect('/cart');
                    }

                    $product['cart_quantity'] = $qty;
                    $products[] = $product;
                }
            }
        }

        $addressModel = new UserAddress();

        $this->view('cart/checkout', [
            'products' => $products,
            'selected_ids' => $selectedIds,
            'user' => (new User())->find($userId),
            'addresses' => $addressModel->getByUserId($userId),
            'defaultAddress' => $addressModel->getDefaultAddress($userId),
        ]);
    }

    /**
     * Xác nhận đặt hàng
     */
    public function confirm(): void
    {
        VerificationMiddleware::requireVerified();
        $user = $this->requireAuth();
        $userId = (int) $user['id'];

        $allCart = $this->getCartItems($userId);
        $selectedIds = $_POST['selected_products'] ?? [];
        $paymentMethod = $this->input('payment_method', 'cod');

        // Build cart items to process
        $cartToProcess = $this->buildCartToProcess($selectedIds, $allCart);

        if (empty($cartToProcess)) {
            $this->redirect('/cart');
        }

        // Validate products
        $productModel = new Product();
        $result = $this->validateAndPrepareProducts($cartToProcess, $productModel, $userId);

        if (!empty($result['errors'])) {
            $_SESSION['error'] = implode('<br>', $result['errors']);
            $this->redirect('/cart');
        }

        // Validate shipping address
        $shippingAddress = $this->getShippingAddress($userId);
        if ($shippingAddress === null) {
            $_SESSION['error'] = 'Vui lòng chọn địa chỉ nhận hàng trước khi đặt hàng.';
            $this->redirect('/addresses/create?redirect_to=' . urlencode('/checkout'));
        }

        // Group by seller and create orders
        $ordersBySeller = $this->groupBySeller($result['products']);
        $createdOrderIds = $this->createOrders($ordersBySeller, $userId, $paymentMethod, $productModel);

        // Handle PayOS payment
        if ($paymentMethod === 'payos' && !empty($createdOrderIds)) {
            $_POST['order_id'] = $createdOrderIds[0];
            $paymentController = new PaymentController();
            $paymentController->create();
            exit;
        }

        // COD: Show success page
        $this->view('cart/success', [
            'order_ids' => $createdOrderIds,
            'payment_method' => $paymentMethod,
        ]);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Lấy cart items từ DB hoặc session
     * 
     * @return array<int|string, array<string, mixed>|int>
     */
    private function getCartItems(int $userId): array
    {
        $cartModel = new Cart();
        $dbItems = $cartModel->getByUserId($userId);

        $cart = [];
        foreach ($dbItems as $item) {
            $item['cart_quantity'] = $item['quantity'];
            $cart[$item['product_id']] = $item;
        }

        return $cart;
    }

    /**
     * Lấy quantity cho product từ cart hoặc POST
     */
    private function getQuantityForProduct(int $productId, array $cart): int
    {
        // Check in cart
        if (isset($cart[$productId])) {
            $item = $cart[$productId];
            return is_array($item)
                ? (int) ($item['quantity'] ?? $item['cart_quantity'] ?? 1)
                : (int) $item;
        }

        // Check in POST (Buy Now flow)
        if (isset($_POST['quantities'][$productId])) {
            return (int) $_POST['quantities'][$productId];
        }

        return 0;
    }

    /**
     * Build cart items to process
     * 
     * @return array<int, int> [product_id => quantity]
     */
    private function buildCartToProcess(array $selectedIds, array $cart): array
    {
        $result = [];
        $postQuantities = $_POST['quantities'] ?? [];

        foreach ($selectedIds as $id) {
            $id = (int) $id;

            if (isset($postQuantities[$id])) {
                $qty = (int) $postQuantities[$id];
                if ($qty > 0) {
                    $result[$id] = $qty;
                }
            } elseif (isset($cart[$id])) {
                $result[$id] = $this->getQuantityForProduct($id, $cart);
            }
        }

        return $result;
    }

    /**
     * Validate products and prepare for order
     * 
     * @return array{errors: array<string>, products: array<array{product: array, quantity: int}>}
     */
    private function validateAndPrepareProducts(array $cartToProcess, Product $productModel, int $userId): array
    {
        $errors = [];
        $products = [];

        foreach ($cartToProcess as $id => $qty) {
            $product = $productModel->find($id);

            if ($product === null || (int) $product['quantity'] < $qty) {
                $errors[] = "Sản phẩm " . ($product['name'] ?? 'Unknown') . " không đủ hàng.";
                continue;
            }

            if ((int) $product['user_id'] === $userId) {
                $errors[] = "Bạn không thể mua sản phẩm '" . $product['name'] . "' của chính mình.";
                continue;
            }

            $products[] = [
                'product' => $product,
                'quantity' => $qty,
            ];
        }

        return ['errors' => $errors, 'products' => $products];
    }

    /**
     * Get shipping address
     */
    private function getShippingAddress(int $userId): ?array
    {
        $addressModel = new UserAddress();
        $addressId = $this->input('shipping_address_id');

        if ($addressId !== null) {
            return $addressModel->findById((int) $addressId, $userId);
        }

        return $addressModel->getDefaultAddress($userId);
    }

    /**
     * Group products by seller
     * 
     * @return array<int, array{seller_id: int, total: float, items: array}>
     */
    private function groupBySeller(array $products): array
    {
        $ordersBySeller = [];

        foreach ($products as $item) {
            $sellerId = (int) $item['product']['user_id'];

            if (!isset($ordersBySeller[$sellerId])) {
                $ordersBySeller[$sellerId] = [
                    'seller_id' => $sellerId,
                    'total' => 0.0,
                    'items' => [],
                ];
            }

            $itemTotal = (float) $item['product']['price'] * (int) $item['quantity'];
            $ordersBySeller[$sellerId]['total'] += $itemTotal;
            $ordersBySeller[$sellerId]['items'][] = $item;
        }

        return $ordersBySeller;
    }

    /**
     * Create orders grouped by seller
     * 
     * @return array<int> Order IDs
     */
    private function createOrders(array $ordersBySeller, int $buyerId, string $paymentMethod, Product $productModel): array
    {
        $orderModel = new Order();
        $orderItemModel = new OrderItem();
        $cartModel = new Cart();
        $escrowService = new EscrowService();

        $createdOrderIds = [];

        foreach ($ordersBySeller as $orderData) {
            $orderStatus = ($paymentMethod === 'payos') ? Order::STATUS_PENDING : Order::STATUS_PENDING;

            // Create order
            $orderId = $orderModel->createOrder([
                'buyer_id' => $buyerId,
                'seller_id' => $orderData['seller_id'],
                'total_amount' => $orderData['total'],
                'status' => $orderStatus,
                'payment_method' => $paymentMethod,
            ]);

            $createdOrderIds[] = $orderId;

            // Set trial days based on product condition
            $firstProduct = $orderData['items'][0]['product'] ?? null;
            $condition = $firstProduct['condition'] ?? 'new';
            $trialDays = $escrowService->getTrialDays($condition);
            $orderModel->update($orderId, ['trial_days' => $trialDays]);

            // Create order items and update stock
            foreach ($orderData['items'] as $item) {
                $product = $item['product'];
                $qty = (int) $item['quantity'];

                $orderItemModel->addItem(
                    $orderId,
                    (int) $product['id'],
                    $qty,
                    (float) $product['price']
                );

                // Decrease stock
                $productModel->decreaseQuantity((int) $product['id'], $qty);

                // Remove from cart
                $cartModel->removeItem($buyerId, (int) $product['id']);
            }
        }

        return $createdOrderIds;
    }
}
