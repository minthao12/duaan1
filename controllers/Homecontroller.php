<?php 
require_once __DIR__ . '/../giaodien/index.php';

class HomeController {

    public function dashboard() {
        $productModel = new Product();

        $keyword = $_GET['keyword'] ?? '';

        $products = $productModel->getAllProducts($keyword);

        require_once __DIR__ . '/../giaodien/index.php';
    }

    public function home() {
        $this->dashboard();
    }
}