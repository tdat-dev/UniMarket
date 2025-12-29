<?php

namespace App\Controllers;
use App\Models\Product;
class HomeController extends BaseController
{
    public function index()
    {
        $productModel = new Product();
        $products = $productModel->all();

        $this->view('home/index', ['products' => $products ?? []]);
    }
}