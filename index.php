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

   case '/':
    (new HomeController())->dashboard(); // gọi luôn dashboard
    break;
    default:
        echo "404 - Không tìm thấy trang";
        break;
}