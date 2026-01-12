<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Category;
use App\Models\Follow;
use App\Models\Notification;

/**
 * Product Controller
 * Handles product listing, viewing, creation and deletion
 * 
 * @package App\Controllers
 */
class ProductController extends BaseController
{
    private const ITEMS_PER_PAGE = 20;

    /**
     * List all products with filtering and pagination
     * 
     * Nếu có category_id, redirect sang SEO URL /dm/slug.cX
     */
    public function index(): void
    {
        // Redirect sang Category SEO URL nếu có category_id
        $categoryId = (int) $this->query('category_id', 0);
        if ($categoryId > 0) {
            $categoryModel = new Category();
            $category = $categoryModel->find($categoryId);

            if ($category) {
                $seoUrl = \App\Helpers\SlugHelper::categoryUrl($category['name'], $categoryId);
                // Preserve other query params (except category_id and page)
                $preserveParams = $_GET;
                unset($preserveParams['category_id']);
                unset($preserveParams['page']);

                if (!empty($preserveParams)) {
                    $seoUrl .= '?' . http_build_query($preserveParams);
                }

                header("Location: {$seoUrl}", true, 301);
                exit;
            }
        }

        $page = max(1, (int) $this->query('page', 1));
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;

        $filters = [
            'category_id' => 0, // No category filter on /products
            'keyword' => $this->query('keyword', ''),
            'price_min' => $this->getNumericQuery('price_min'),
            'price_max' => $this->getNumericQuery('price_max'),
            'sort' => $this->query('sort', 'newest')
        ];

        $productModel = new Product();
        $categoryModel = new Category();

        $products = $productModel->getFiltered($filters, self::ITEMS_PER_PAGE, $offset);
        $totalProducts = $productModel->countFiltered($filters);
        $totalPages = (int) ceil($totalProducts / self::ITEMS_PER_PAGE);

        $this->view('products/index', [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'categories' => $categoryModel->getTree(),
            'keyword' => $filters['keyword'],
            'categoryId' => 0,
            'sort' => $filters['sort'],
            'priceMin' => $filters['price_min'],
            'priceMax' => $filters['price_max']
        ]);
    }

    /**
     * Show product với SEO URL (Zoldify style)
     * 
     * URL: /z/ten-san-pham.p123
     */
    public function showBySlug(string $slug, int $productId): void
    {
        // Gọi show() với flag fromSeoUrl=true để không redirect lại
        $this->show($productId, true);
    }

    /**
     * Show single product detail
     * 
     * Nếu truy cập bằng URL cũ /products/{id}, redirect sang SEO URL mới
     */
    public function show(int|string $id, bool $fromSeoUrl = false): void
    {
        $productModel = new Product();
        $product = $productModel->find((int) $id);

        if (!$product) {
            $this->handleNotFound('Sản phẩm không tồn tại');
            return;
        }

        // Redirect sang SEO URL nếu truy cập bằng URL cũ
        if (!$fromSeoUrl) {
            $seoUrl = \App\Helpers\SlugHelper::productUrl(
                $product['name'],
                (int) $product['user_id'],
                (int) $product['id']
            );
            header("Location: {$seoUrl}", true, 301);
            exit;
        }

        $userModel = new User();
        $seller = $userModel->find($product['user_id']);

        $productImages = $this->getProductImages((int) $id, $product['image'] ?? null);
        $relatedProducts = $productModel->getByCategory($product['category_id'], 4, $product['id']);

        // Stats
        $activeProductCount = $productModel->countActiveByUserId($product['user_id']);
        
        $reviewModel = new \App\Models\Review();
        $stats = $reviewModel->getSellerStats($product['user_id']);

        $this->view('products/detail', [
            'product' => $product,
            'seller' => $seller,
            'productImages' => $productImages,
            'relatedProducts' => $relatedProducts,
            'activeProductCount' => $activeProductCount,
            'stats' => $stats
        ]);
    }

    /**
     * Show product creation form
     */
    public function create(): void
    {
        $categoryModel = new Category();
        $this->view('products/create', [
            'categories' => $categoryModel->getTree()
        ]);
    }

    /**
     * Store new product
     */
    public function store(): void
    {
        $user = $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/products/create');
        }

        $data = $_POST;
        $errors = $this->validateProductData($data);

        if (!empty($errors)) {
            $categoryModel = new Category();
            $this->view('products/create', [
                'errors' => $errors,
                'old' => $data,
                'categories' => $categoryModel->getTree()
            ]);
            return;
        }

        $uploadedImages = $this->handleImageUpload();
        $mainImage = $uploadedImages[0] ?? 'default_product.png';

        $productData = [
            'name' => htmlspecialchars($data['name']),
            'price' => (int) $data['price'],
            'description' => $this->buildDescription($data),
            'user_id' => $user['id'],
            'category_id' => (int) $data['category_id'],
            'quantity' => max(1, (int) ($data['quantity'] ?? 1)),
            'image' => $mainImage
        ];

        try {
            $productModel = new Product();
            $newId = $productModel->create($productData);

            if ($newId) {
                $this->saveProductImages($newId, $uploadedImages);
                $this->notifyFollowers($user, $productData['name']);
                $this->redirect('/shop?id=' . $user['id']);
            } else {
                throw new \Exception('Không thể tạo sản phẩm');
            }
        } catch (\Exception $e) {
            $categoryModel = new Category();
            $this->view('products/create', [
                'errors' => ['db' => 'Lỗi: ' . $e->getMessage()],
                'old' => $data,
                'categories' => $categoryModel->getTree()
            ]);
        }
    }

    /**
     * Cancel product sale (delete or hide)
     */
    public function cancelSale(int|string $id): void
    {
        if (!$this->isAuthenticated()) {
            $this->jsonError('Bạn chưa đăng nhập', 401);
        }

        $productId = (int) $id;
        $productModel = new Product();
        $product = $productModel->find($productId);

        if (!$product) {
            $this->jsonError('Sản phẩm không tồn tại', 404);
        }

        if ($product['user_id'] !== $this->getUserId()) {
            $this->jsonError('Bạn không có quyền xoá sản phẩm này', 403);
        }

        // Check if product has any orders
        if ($productModel->hasAnyOrder($productId)) {
            $this->jsonError(
                'Sản phẩm đã phát sinh đơn hàng nên không thể xoá vĩnh viễn. Bạn chỉ có thể ẩn sản phẩm.',
                400
            );
        }

        if ($productModel->delete($productId)) {
            $this->jsonSuccess('Đã xoá sản phẩm thành công');
        } else {
            $this->jsonError('Lỗi hệ thống, không thể xoá', 500);
        }
    }

    // ==================== PRIVATE HELPERS ====================

    /**
     * Get numeric query parameter
     */
    private function getNumericQuery(string $key): ?int
    {
        $value = $this->query($key);
        return ($value !== null && is_numeric($value)) ? (int) $value : null;
    }

    /**
     * Validate product data
     * 
     * @return array<string, string> Errors
     */
    private function validateProductData(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }

        if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            $errors['price'] = 'Giá bán không hợp lệ';
        }

        if (empty($data['category_id'])) {
            $errors['category_id'] = 'Vui lòng chọn danh mục';
        }

        if (!isset($_FILES['images']) || $_FILES['images']['error'][0] !== UPLOAD_ERR_OK) {
            $errors['images'] = 'Vui lòng chọn ít nhất 1 ảnh sản phẩm';
        }

        return $errors;
    }

    /**
     * Handle image upload
     * 
     * @return array<string> Uploaded image paths
     */
    private function handleImageUpload(): array
    {
        $uploadedImages = [];

        if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
            return $uploadedImages;
        }

        $rootDir = __DIR__ . '/../../public/';
        $uploadDir = 'uploads/products/';

        if (!is_dir($rootDir . $uploadDir)) {
            mkdir($rootDir . $uploadDir, 0777, true);
        }

        $fileCount = count($_FILES['images']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['images']['tmp_name'][$i];
                $fileName = time() . '_' . $i . '_' . basename($_FILES['images']['name'][$i]);

                if (move_uploaded_file($fileTmp, $rootDir . $uploadDir . $fileName)) {
                    $uploadedImages[] = 'products/' . $fileName;
                }
            }
        }

        return $uploadedImages;
    }

    /**
     * Build product description with condition
     */
    private function buildDescription(array $data): string
    {
        $description = htmlspecialchars($data['description'] ?? '');

        if (!empty($data['condition'])) {
            $conditionText = $data['condition'] === 'new' ? 'Mới 100%' : $data['condition'];
            $description .= "\n\nTình trạng: " . $conditionText;
        }

        return $description;
    }

    /**
     * Get product images with fallback
     * 
     * @return array<int, array<string, mixed>>
     */
    private function getProductImages(int $productId, ?string $fallbackImage): array
    {
        try {
            $productImageModel = new ProductImage();
            $images = $productImageModel->getByProductId($productId);

            if (!empty($images)) {
                return $images;
            }
        } catch (\Exception $e) {
            // Table might not exist
        }

        // Fallback to main image
        if (!empty($fallbackImage)) {
            return [['image_path' => $fallbackImage, 'is_primary' => 1]];
        }

        return [];
    }

    /**
     * Save product images to database
     */
    private function saveProductImages(int $productId, array $images): void
    {
        if (empty($images)) {
            return;
        }

        try {
            $productImageModel = new ProductImage();
            $productImageModel->addMultiple($productId, $images);
        } catch (\Exception $e) {
            // Table might not exist, ignore
        }
    }

    /**
     * Notify followers about new product
     */
    private function notifyFollowers(array $user, string $productName): void
    {
        try {
            $followModel = new Follow();
            $notifModel = new Notification();

            $followers = $followModel->getFollowers($user['id']);
            $content = "Shop {$user['full_name']} vừa đăng bán sản phẩm mới: {$productName}";

            foreach ($followers as $follower) {
                $notifModel->create($follower['id'], $content);
            }
        } catch (\Exception $e) {
            // Ignore notification errors
        }
    }

    /**
     * Handle not found response
     */
    private function handleNotFound(string $message): void
    {
        http_response_code(404);
        $this->view('errors/404', ['message' => $message]);
    }
}