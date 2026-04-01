<?php
require_once __DIR__ . '/../models/Product.php';

class HomeController {

    public function dashboard() {
    $productModel = new Product();
    $variants = $productModel->getAllVariants();

    require_once __DIR__ . '/../views/admin/main.php';
}
public function home() {
        $this->dashboard(); // gọi lại dashboard
    }
public function detail() {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $productModel = new Product();
            $item = $productModel->getVariantById($id); 
            if ($item) {
                require_once __DIR__ . '/../views/admin/datailproduct.php';
            } else {
                echo "Không tìm thấy sản phẩm này trong cơ sở dữ liệu!";
            }
        } else {
            echo "URL thiếu ID sản phẩm!";
        }
    }
}