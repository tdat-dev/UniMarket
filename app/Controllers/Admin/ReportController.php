<?php

declare(strict_types=1);

namespace App\Controllers\Admin;
use App\Models\Report;
use App\Models\Product;

class ReportController extends AdminBaseController
{
    protected $reportModel;
    protected $productModel;

    public function __construct()
    {
        $this->reportModel = new Report();
        $this->productModel = new Product();

        // nếu có middleware
        // $this->middleware('admin');
    }

    // GET: /admin/reports
    public function index()
    {
        $reports = $this->reportModel->getAll();
        return $this->view('reports/index', [
            'title' => 'Báo Cáo Vi Phạm',
            'reports' => $reports
        ]);
    }

    // GET: /admin/reports/show?id=1
    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return $this->redirect('/admin/reports');
        }

        $report = $this->reportModel->findById($id);
        return $this->view('reports/show', [
            'title' => 'Chi Tiết Báo Cáo',
            'report' => $report
        ]);
    }

    // POST: /admin/reports/hide-product
    public function hideProduct()
    {
        $reportId = $_POST['report_id'];
        $productId = $_POST['product_id'];

        $this->productModel->hideProduct($productId);
        $this->reportModel->markResolved($reportId);

        return $this->redirect('/admin/reports');
    }
}
