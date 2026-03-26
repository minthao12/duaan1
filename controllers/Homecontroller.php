<?php
require_once __DIR__ . '/../models/Product.php';

class HomeController {

    public function dashboard() {
    $productModel = new Product();
    $variants = $productModel->getAllVariants();

    require_once __DIR__ . '/../views/admin/main.php';
}
}