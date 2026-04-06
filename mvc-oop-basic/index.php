<?php

require_once './commons/env.php';
require_once './commons/function.php';

// Controllers
require_once './controllers/admincontroller.php';
require_once './controllers/HomeController.php';


// Models
require_once './models/Product.php';

// Route
$act = $_GET['act'] ?? '/';

switch ($act) {
    case '/':
    case 'giaodien':
        (new HomeController())->giaodien();
        break;

    case 'admin':
        (new admincontroller())->dashboard();
        break;

    case 'detail':
        (new admincontroller())->detail();
        break;

    case 'ProductUser':
        (new admincontroller())->ProductUser();
        break;

    case 'login':
        (new admincontroller())->login();
        break;

    case 'register':
        (new admincontroller())->register();
        break;

    case 'logout':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php?act=/");
        exit;

    case 'adminProduct':
        (new admincontroller())->adminProduct();
        break;

    case 'addProduct':
        (new admincontroller())->addProduct();
        break;

    case 'editProduct':
        (new admincontroller())->editProduct();
        break;

    case 'deleteProduct':
        (new admincontroller())->deleteProduct();
        break;

    case 'users':
        (new admincontroller())->users();
        break;

    case 'editUser':
        (new admincontroller())->editUser();
        break;

    case 'deleteUser':
        (new admincontroller())->deleteUser();
        break;

    case 'CateProduct':
        (new admincontroller())->CateProduct();
        break;

    case 'addCateProduct':
        (new admincontroller())->addCateProduct();
        break;

    case 'editCateProduct':
        (new admincontroller())->editCateProduct();
        break;

    case 'deleteCateProduct':
        (new admincontroller())->deleteCateProduct();
        break;
    

    case 'loginUser':
    (new HomeController())->loginUser();
    break;

    case 'registerUser':
        (new HomeController())->registerUser();
        break;
        
    //==============================

    case 'detail':
    (new HomeController())->detailProduct();
    break;

    case 'addToCart':
        (new HomeController())->addToCart();
        break;

    case 'cart':
        (new HomeController())->cart();
        break;

    case 'updateCart':
        (new HomeController())->updateCart();
        break;

    case 'deleteCart':
        (new HomeController())->deleteCart();
        break;
    default:
        echo "404 - Không tìm thấy trang";
        break;
}