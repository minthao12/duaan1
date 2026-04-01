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

    case 'ProductUser':
        (new HomeController())->ProductUser();
        break;
    
    case 'login':
    (new HomeController())->login();
    break;
    
    case 'adminProduct':
        (new HomeController())->adminProduct();
        break;

    case 'register':
        (new HomeController())->register();
        break;

    case 'logout':
        session_start();
        session_destroy();
        header("Location: index.php?act=/");
        break;
    
    case 'users':
        (new HomeController())->users();
        break;
    
    case 'editUser':
        (new HomeController())->editUser();
        break;

    case 'deleteUser':
        (new HomeController())->deleteUser();
        break;

    case 'CateProduct':
        (new HomeController())->CateProduct();
        break;

    case 'CateProduct':
        (new HomeController())->CateProduct();
        break;

    case 'addCateProduct':
        (new HomeController())->addCateProduct();
        break;

    case 'editCateProduct':
        (new HomeController())->editCateProduct();
        break;

    case 'deleteCateProduct':
        (new HomeController())->deleteCateProduct();
        break;
    default:
        echo "404 - Không tìm thấy trang";
        break;
}