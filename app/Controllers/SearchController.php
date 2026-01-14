<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SearchKeyword;

/**
 * Search Controller
 * 
 * Xử lý tìm kiếm sản phẩm và autocomplete suggestions.
 * 
 * @package App\Controllers
 */
class SearchController extends BaseController
{
    private const ITEMS_PER_PAGE = 12;

    /**
     * Trang kết quả tìm kiếm
     */
    public function index(): void
    {
        $keyword = $this->query('q', '');
        $categoryId = $this->query('category');
        $page = max(1, (int) $this->query('page', 1));
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;

        // Track keyword nếu có
        if (!empty(trim($keyword))) {
            $searchModel = new SearchKeyword();
            $searchModel->trackKeyword($keyword);
        }

        // Resolve category children nếu có filter category
        $filterCategoryId = $categoryId;
        if ($categoryId !== null) {
            $categoryModel = new Category();
            $filterCategoryId = $categoryModel->getChildrenIds((int) $categoryId);
        }

        $filters = [
            'keyword' => $keyword,
            'category_id' => $filterCategoryId,
        ];

        $productModel = new Product();
        $products = $productModel->getFiltered($filters, self::ITEMS_PER_PAGE, $offset);
        $totalProducts = $productModel->countFiltered($filters);

        $this->view('search/index', [
            'products' => $products,
            'keyword' => $keyword,
            'categoryId' => $categoryId,
            'currentPage' => $page,
            'totalPages' => (int) ceil($totalProducts / self::ITEMS_PER_PAGE),
        ]);
    }

    /**
     * Tìm kiếm với yêu cầu đăng nhập
     */
    public function search(): void
    {
        if (!$this->isAuthenticated()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect('/login');
        }

        $this->index();
    }

    /**
     * API: Gợi ý sản phẩm cho autocomplete
     */
    public function suggest(): never
    {
        $keyword = $this->query('q', '');

        $productModel = new Product();
        $results = $productModel->searchByKeyword($keyword, 6, 0);

        $this->json($results);
    }
}