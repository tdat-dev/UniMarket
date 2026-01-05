<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Category;
use App\Middleware\VerificationMiddleware;

class ProductController extends BaseController // Kế thừa BaseController để dùng hàm view()
{
    public function index()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 20; // Giảm xuống để phù hợp với layout 5 cột
        $offset = ($page - 1) * $limit;

        // Lấy các tham số filter từ URL
        $keyword = $_GET['keyword'] ?? '';
        $categoryId = isset($_GET['category_id']) ? (int) $_GET['category_id'] : 0;
        $sort = $_GET['sort'] ?? 'newest'; // Mặc định sắp xếp theo mới nhất
        $priceMin = isset($_GET['price_min']) && is_numeric($_GET['price_min']) ? (int) $_GET['price_min'] : null;
        $priceMax = isset($_GET['price_max']) && is_numeric($_GET['price_max']) ? (int) $_GET['price_max'] : null;

        $productModel = new Product();
        $categoryModel = new Category();

        // Tạo mảng filters để truyền vào model
        $filters = [
            'category_id' => $categoryId,
            'keyword' => $keyword,
            'price_min' => $priceMin,
            'price_max' => $priceMax,
            'sort' => $sort
        ];

        // Gọi hàm mới hỗ trợ đầy đủ filter + sort
        $products = $productModel->getFiltered($filters, $limit, $offset);
        $totalProducts = $productModel->countFiltered($filters);

        $totalPages = ceil($totalProducts / $limit);
        $categories = $categoryModel->getTree();

        $this->view('products/index', [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'keyword' => $keyword,
            'categoryId' => $categoryId,
            'sort' => $sort,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax
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

        // Lấy tất cả ảnh của sản phẩm
        $productImageModel = new ProductImage();
        $productImages = $productImageModel->getByProductId($id);

        // Nếu chưa có ảnh trong bảng mới, dùng ảnh từ cột image
        if (empty($productImages) && !empty($product['image'])) {
            $productImages = [
                ['image_path' => $product['image'], 'is_primary' => 1]
            ];
        }

        // Lấy sản phẩm liên quan (cùng danh mục, trừ sản phẩm hiện tại)
        $relatedProducts = $productModel->getByCategory($product['category_id'], 4, $product['id']);

        $this->view('products/detail', [
            'product' => $product,
            'seller' => $seller,
            'productImages' => $productImages,
            'relatedProducts' => $relatedProducts
        ]);
    }

    // Hàm hiện form đăng tin
    public function create()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getTree();
        $this->view('products/create', ['categories' => $categories]);
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

        // 2. Handle Image Upload - Upload TẤT CẢ ảnh
        $uploadedImages = []; // Mảng chứa đường dẫn các ảnh đã upload
        $mainImage = 'default_product.png'; // Fallback cho cột image (backwards compatible)

        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $rootDir = __DIR__ . '/../../public/';
            $uploadDir = 'uploads/products/';

            // Đảm bảo thư mục tồn tại
            if (!is_dir($rootDir . $uploadDir)) {
                mkdir($rootDir . $uploadDir, 0777, true);
            }

            // Loop qua TẤT CẢ ảnh được upload
            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $fileTmp = $_FILES['images']['tmp_name'][$i];
                    $fileName = time() . '_' . $i . '_' . $_FILES['images']['name'][$i];

                    if (move_uploaded_file($fileTmp, $rootDir . $uploadDir . $fileName)) {
                        $imagePath = 'products/' . $fileName;
                        $uploadedImages[] = $imagePath;

                        // Ảnh đầu tiên làm main image
                        if ($i === 0) {
                            $mainImage = $imagePath;
                        }
                    }
                }
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
            'image' => $mainImage // Vẫn lưu ảnh chính vào cột image (backwards compatible)
        ];

        // Nếu có trường condition từ form
        if (!empty($data['condition'])) {
            $productData['description'] .= "\n\nTình trạng: " . ($data['condition'] == 'new' ? 'Mới 100%' : $data['condition']);
        }

        try {
            $newId = $productModel->create($productData);
            if ($newId) {
                // Lưu TẤT CẢ ảnh vào bảng product_images
                if (!empty($uploadedImages)) {
                    $productImageModel = new ProductImage();
                    $productImageModel->addMultiple($newId, $uploadedImages);
                }

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