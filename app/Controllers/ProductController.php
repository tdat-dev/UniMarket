<?php

namespace App\Controllers;

use App\Models\Product;

class ProductController
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        // 1. Tạo object Product (tự động kết nối DB qua BaseModel)
        $productModel = new Product();
        
        // 2. Gọi method all() để lấy data
        $products = $productModel->all();
        
        // 3. Trả về JSON (tạm thời, sau này sẽ load view)
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($products, JSON_UNESCAPED_UNICODE);
    }

    // Hiển thị chi tiết 1 sản phẩm
    public function show($id)
    {
        $productModel = new Product();
        $product = $productModel->find($id);
        
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Không tìm thấy sản phẩm']);
            return;
        }
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($product, JSON_UNESCAPED_UNICODE);
    }
}