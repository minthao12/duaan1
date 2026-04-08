<?php
require_once './commons/env.php';
require_once './commons/function.php';
require_once './controllers/admincontroller.php';
require_once './controllers/HomeController.php';
<<<<<<< Updated upstream
require_once './models/Product.php';
require_once './models/User.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
=======

// Models
require_once './models/Product.php';
require_once './models/User.php';
>>>>>>> Stashed changes

$act = $_GET['act'] ?? '/';

switch ($act) {
    case '/':
    case 'giaodien':
        (new HomeController())->giaodien();
        break;

<<<<<<< Updated upstream
=======
    // ===== CLIENT =====
>>>>>>> Stashed changes
    case 'detail':
        (new HomeController())->detailProduct();
        break;

<<<<<<< Updated upstream
=======
    case 'loginUser':
        (new HomeController())->loginUser();
        break;

    case 'registerUser':
        (new HomeController())->registerUser();
        break;

>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
    case 'loginUser':
        (new HomeController())->loginUser();
        break;

    case 'registerUser':
        (new HomeController())->registerUser();
        break;

=======
    case 'checkout':
        (new HomeController())->checkout();
        break;

    case 'placeOrder':
        (new HomeController())->placeOrder();
        break;

    case 'myOrders':
        (new HomeController())->myOrders();
        break;

    // ===== ADMIN =====
>>>>>>> Stashed changes
    case 'admin':
        (new admincontroller())->dashboard();
        break;

    case 'login':
        (new admincontroller())->login();
        break;

    case 'register':
        (new admincontroller())->register();
        break;

<<<<<<< Updated upstream
    case 'logout':
        session_destroy();
        header('Location: index.php?act=giaodien');
        exit;

=======
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
    case 'detailAdmin':
        (new admincontroller())->detail();
=======
    case 'ProductUser':
        (new admincontroller())->ProductUser();
        break;

    case 'users':
        (new admincontroller())->users();
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
    case 'users':
        (new admincontroller())->users();
        break;

    case 'editUser':
        (new admincontroller())->editUser();
        break;

    case 'deleteUser':
        (new admincontroller())->deleteUser();
        break;
=======
    case 'donhang':
        (new admincontroller())->donhang();
        break;

    case 'updateOrderStatus':
        (new admincontroller())->updateOrderStatus();
        break;

    case 'logout':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php?act=/");
        exit;
>>>>>>> Stashed changes

    default:
        http_response_code(404);
        echo '404 - Không tìm thấy trang';
        break;
}