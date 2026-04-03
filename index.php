<?php 

require_once './commons/env.php';
require_once './commons/function.php';

// Controllers
require_once './controllers/HomeController.php';
require_once './controllers/AdminController.php';

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
  case 'home_admin'
  (new Admincontroller()) ->home_admin();
  break; 
  case 'admin':
    (new AdminController())->dashboard();
    break;

    case 'detail':
        (new Admincontroller())->detail();
        break;

    case 'ProductUser':
        (new Admincontroller())->ProductUser();
        break;
    
    case 'login':
    (new Admincontroller)->login();
    break;
    
    case 'adminProduct':
        (new Admincontroller())->adminProduct();
        break;

    case 'register':
        (new Admincontroller())->register();
        break;

    case 'logout':
        session_start();
        session_destroy();
        header("Location: index.php?act=/");
        break;
    
    case 'users':
        (new Admincontroller())->users();
        break;
    
    case 'editUser':
        (new Admincontroller())->editUser();
        break;

    case 'deleteUser':
        (new Admincontroller())->deleteUser();
        break;

    case 'CateProduct':
        (new Admincontroller())->CateProduct();
        break;

    case 'CateProduct':
        (new Admincontroller())->CateProduct();
        break;

    case 'addCateProduct':
        (new Admincontroller())->addCateProduct();
        break;

    case 'editCateProduct':
        (new Admincontroller())->editCateProduct();
        break;

    case 'deleteCateProduct':
        (new Admincontroller())->deleteCateProduct();
        break;
    default:
        echo "404 - Không tìm thấy trang";
        break;
}