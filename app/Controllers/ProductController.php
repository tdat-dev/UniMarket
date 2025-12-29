<?php

namespace App\Controllers;

use App\Models\Product;

class ProductController extends BaseController // Kế thừa BaseController để dùng hàm view()
{
    public function index()
    {
        $productModel = new Product();
        $products = $productModel->all();

        // Thay vì echo JSON, ta gọi View và truyền data
        $this->view('home/index', ['products' => $products]);
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
    public function create() {
        $this->view('products/create'); // Bạn cần tạo file view này
    }

    // Hàm xử lý lưu tin
    public function store() {
        // Code xử lý upload ảnh và gọi Model create() tại đây
    }
}