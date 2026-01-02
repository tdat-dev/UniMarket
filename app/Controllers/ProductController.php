<?php

namespace App\Controllers;

use App\Models\Product;

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

        $this->view('products/detail', ['product' => $product]);
    }

    // Hàm hiện form đăng tin
    public function create()
    {
        $this->view('products/create'); // Bạn cần tạo file view này
    }

    // Hàm xử lý lưu tin
   public function store() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = [
            'name'        => $_POST['name'] ?? '',
            'price'       => $_POST['price'] ?? 0,
            'description' => $_POST['description'] ?? '',
            'category_id' => $_POST['category_id'] ?? 0,
        ];

        $productModel = new Product();
        $productModel->create($data);

        // Sau khi đăng tin xong → quay về trang danh sách
        header('Location: /products');
        exit;
    }
  }
}