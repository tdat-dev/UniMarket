<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;

/**
 * Home Controller
 * 
 * Xử lý trang chủ và các trang static.
 * 
 * @package App\Controllers
 */
class HomeController extends BaseController
{
    /**
     * Trang chủ
     */
    public function index(): void
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $this->view('home/index', [
            'latestProducts' => $productModel->getLatest(8),
            'suggestedProducts' => $productModel->getRandom(12),
            'topProducts' => $productModel->getByTopKeywords(6),
            'categories' => $categoryModel->getParents(20),
        ]);
    }

    /**
     * Trang hỗ trợ
     */
    public function support(): void
    {
        $this->view('support/index');
    }

    /**
     * Trang Chính sách bảo mật
     */
    public function privacy(): void
    {
        $this->view('legal/privacy');
    }

    /**
     * Trang Điều khoản sử dụng
     */
    public function terms(): void
    {
        $this->view('legal/terms');
    }
}