<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\User;
class ProductController extends BaseController // Kế thừa BaseController để dùng hàm view()
{
    public function index()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 22; // Số sản phẩm trên mỗi trang
        $offset = ($page - 1) * $limit;

        $productModel = new Product();
        $products = $productModel->getPaginated($limit, $offset);
        $totalProducts = $productModel->countAll();
        $totalPages = ceil($totalProducts / $limit);

        $this->view('products/index', [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function show()
    {
        // Lấy ID từ URL: product-detail?id=5
        $id = $_GET['id'] ?? null;

        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            die("Sản phẩm không tồn tại"); // Hoặc redirect 404
        }

        // Lấy thông tin người bán
        $userModel = new User();
        $seller = $userModel->find($product['user_id']);

        // Lấy sản phẩm liên quan (cùng danh mục, trừ sản phẩm hiện tại)
        $relatedProducts = $productModel->getByCategory($product['category_id'], 4, $product['id']);

        $this->view('products/detail', [
            'product' => $product,
            'seller' => $seller,
            'relatedProducts' => $relatedProducts
        ]);
    }

    // Hàm hiện form đăng tin
    public function create()
    {
        $this->view('products/create'); // Bạn cần tạo file view này
    }

    // Hàm xử lý lưu tin
    public function store()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products/create');
            exit;
        }

        // 1. Validate dữ liệu
        $errors = [];
        $data = $_POST;

        if (empty($data['name'])) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }

        if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            $errors['price'] = 'Giá bán không hợp lệ';
        }

        if (empty($data['category_id'])) {
            $errors['category_id'] = 'Vui lòng chọn danh mục';
        }

        if (empty($data['quantity']) || $data['quantity'] < 1) {
             $data['quantity'] = 1; // Default
        }

        // Validate Image
        if (!isset($_FILES['images']) || $_FILES['images']['error'][0] != UPLOAD_ERR_OK) {
             // Optional: Allow product without image? Usually no for a marketplace.
             // For now require at least one image
             $errors['images'] = 'Vui lòng chọn ít nhất 1 ảnh sản phẩm';
        }

        if (!empty($errors)) {
            $this->view('products/create', ['errors' => $errors, 'old' => $data]);
            return;
        }

        // 2. Handle Image Upload
        // Lấy ảnh đầu tiên làm ảnh đại diện (Do bảng products hiện tại chỉ lưu 1 ảnh)
        $mainImage = 'default_product.png'; // Fallback
        
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileTmp = $_FILES['images']['tmp_name'][0];
            $fileName = time() . '_' . $_FILES['images']['name'][0];
            $uploadDir = 'uploads/products/'; // Relative to public
            
            // Đảm bảo thư mục tồn tại (cần check absolute path)
            $rootDir = __DIR__ . '/../../public/';
            if (!is_dir($rootDir . $uploadDir)) {
                mkdir($rootDir . $uploadDir, 0777, true);
            }

            if (move_uploaded_file($fileTmp, $rootDir . $uploadDir . $fileName)) {
                // View prepends '/uploads/', so we save 'products/filename.ext'
                $mainImage = 'products/' . $fileName; 
            }
        }

        // 3. Save to DB
        $productModel = new Product();
        $productData = [
            'name' => htmlspecialchars($data['name']),
            'price' => (int) $data['price'],
            'description' => htmlspecialchars($data['description'] ?? ''),
            'user_id' => $_SESSION['user']['id'],
            'category_id' => (int) $data['category_id'],
            'quantity' => (int) $data['quantity'],
            'image' => $mainImage
        ];

        // Nếu có trường condition từ form và model chưa hỗ trợ, ta có thể nối vào description hoặc bỏ qua
        if (!empty($data['condition'])) {
            $productData['description'] .= "\n\nTình trạng: " . ($data['condition'] == 'new' ? 'Mới 100%' : $data['condition']);
        }

        try {
            $newId = $productModel->create($productData);
            if ($newId) {
                // Success -> Redirect to product detail or shop
                header('Location: /shop?id=' . $_SESSION['user']['id']); 
                exit;
            } else {
                $errors['db'] = 'Lỗi hệ thống, không thể tạo sản phẩm';
                 $this->view('products/create', ['errors' => $errors, 'old' => $data]);
            }
        } catch (\Exception $e) {
             $errors['db'] = 'Lỗi: ' . $e->getMessage();
             $this->view('products/create', ['errors' => $errors, 'old' => $data]);
        }
    }
}