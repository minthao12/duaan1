<?php
require_once './commons/env.php';
require_once './commons/function.php';
require_once './controllers/admincontroller.php';
require_once './controllers/HomeController.php';
require_once './models/Product.php';
require_once './models/User.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$act = $_GET['act'] ?? '/';

switch ($act) {
    case '/':
    case 'giaodien':
        (new HomeController())->giaodien();
        break;

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

    case 'loginUser':
        (new HomeController())->loginUser();
        break;

    case 'registerUser':
        (new HomeController())->registerUser();
        break;

    case 'admin':
        (new admincontroller())->dashboard();
        break;

    case 'login':
        (new admincontroller())->login();
        break;

    case 'register':
        (new admincontroller())->register();
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?act=giaodien');
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

    case 'detailAdmin':
        (new admincontroller())->detail();
        break;

    case 'ProductUser':
        (new admincontroller())->ProductUser();
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

    case 'users':
        (new admincontroller())->users();
        break;

    case 'editUser':
        (new admincontroller())->editUser();
        break;

    case 'deleteUser':
        (new admincontroller())->deleteUser();
        break;

    default:
        http_response_code(404);
        echo '404 - Không tìm thấy trang';
        break;
}