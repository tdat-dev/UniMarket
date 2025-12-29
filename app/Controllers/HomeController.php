<?php

namespace App\Controllers;
use App\Models\Product;
class HomeController extends BaseController
{
    public function index()
    {
        $productModel = new Product();
        $latestProducts = $productModel->getLatest(8);
        $suggestedProducts = $productModel->getRandom(12);
        $topProducts = $productModel->getByTopKeywords(6);

        $this->view('home/index', [
            'latestProducts' => $latestProducts,
            'suggestedProducts' => $suggestedProducts,
            'topProducts' => $topProducts
        ]);
    }
}