<?php 

require_once './commons/env.php';
require_once './commons/function.php';

// Controllers
require_once './controllers/HomeController.php';

// Models
require_once './models/Product.php';

// Route
$act = $_GET['act'] ?? '/';

switch ($act) {

    // Trang chủ
    case '/':
        (new HomeController())->home();
        break;

    // Trang quản trị
   case 'admin':
    (new HomeController())->dashboard();
    break;

    case 'detail':
        (new HomeController())->detail();
        break;

    default:
        echo "404 - Không tìm thấy trang";
        break;
}