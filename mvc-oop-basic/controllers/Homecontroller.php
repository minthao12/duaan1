<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

class HomeController
{
    public function __construct()
    {
    }

    public function home()
    {
        $this->giaodien();
    }

    public function giaodien()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $productModel = new Product();
        $keyword = trim($_GET['keyword'] ?? '');
        $products = $productModel->getAllVariants($keyword);

        require_once __DIR__ . '/../views/client/giaodien/index.php';
    }

    public function detailProduct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $productId = $_GET['id'] ?? 0;

        if (!$productId || !is_numeric($productId)) {
            echo "Sản phẩm không hợp lệ";
            return;
        }

        $productModel = new Product();
        $productVariants = $productModel->getProductVariantsByProductId((int)$productId);

        if (empty($productVariants)) {
            echo "Không tìm thấy sản phẩm";
            return;
        }

        $firstVariant = $productVariants[0];

        $colors = [];
        $sizes = [];

        foreach ($productVariants as $item) {
            $colors[$item['color_id']] = $item['color_name'];
            $sizes[$item['size_id']] = $item['size_name'];
        }

        require_once __DIR__ . '/../views/client/giaodien/detailProduct.php';
    }

    public function registerUser()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $errors = [];
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $std      = trim($_POST['std'] ?? '');
        $diachi   = trim($_POST['diachi'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '') {
            $errors[] = 'Tên đăng nhập không được để trống.';
        } elseif (mb_strlen($username) < 3) {
            $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự.';
        }

        if ($email === '') {
            $errors[] = 'Email không được để trống.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không đúng định dạng.';
        }

        if ($std === '') {
            $errors[] = 'Số điện thoại không được để trống.';
        } elseif (!preg_match('/^[0-9]{9,11}$/', $std)) {
            $errors[] = 'Số điện thoại phải từ 9 đến 11 số.';
        }

        if ($diachi === '') {
            $errors[] = 'Địa chỉ không được để trống.';
        }

        if ($password === '') {
            $errors[] = 'Mật khẩu không được để trống.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Mật khẩu phải từ 6 ký tự trở lên.';
        }

        $userModel = new User();

        if (empty($errors) && $userModel->findByUsername($username)) {
            $errors[] = 'Tên đăng nhập đã tồn tại.';
        }

        if (empty($errors) && $userModel->findByEmail($email)) {
            $errors[] = 'Email đã tồn tại.';
        }

        if (empty($errors)) {
            $register = $userModel->register($username, $email, $std, $diachi, $password);

            if ($register) {
                $_SESSION['success_register'] = 'Đăng ký thành công. Vui lòng đăng nhập.';
                header('Location: index.php?act=loginUser');
                exit;
            } else {
                $errors[] = 'Đăng ký thất bại, vui lòng thử lại.';
            }
        }
    }

    require_once __DIR__ . '/../views/client/giaodien/registerUser.php';
}

public function loginUser()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $error = "";
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '') {
            $errors[] = "Username không được để trống.";
        }

        if ($password === '') {
            $errors[] = "Mật khẩu không được để trống.";
        }

        if (empty($errors)) {
            $userModel = new User();
            $user = $userModel->login($username, $password);

            if ($user) {
                $_SESSION['user'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'] ?? 'user';

                if (($user['role'] ?? 'user') === 'admin') {
                    header("Location: index.php?act=adminProduct");
                    exit;
                }

                header("Location: index.php?act=giaodien");
                exit;
            } else {
                $error = "Sai tài khoản hoặc mật khẩu!";
            }
        }
    }

    require_once __DIR__ . '/../views/client/giaodien/loginUser.php';
}

    public function addToCart()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?act=loginUser");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=giaodien");
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $colorId   = (int)($_POST['color_id'] ?? 0);
        $sizeId    = (int)($_POST['size_id'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 1);

        if ($productId <= 0 || $colorId <= 0 || $sizeId <= 0 || $quantity <= 0) {
            echo "Dữ liệu không hợp lệ";
            return;
        }

        $productModel = new Product();
        $variant = $productModel->findVariantByOption($productId, $colorId, $sizeId);

        if (!$variant) {
            echo "Không tìm thấy biến thể sản phẩm";
            return;
        }

        if ($quantity > (int)$variant['stock']) {
            echo "Số lượng vượt quá tồn kho";
            return;
        }

        $productModel->addCartItem($_SESSION['user_id'], $variant['id'], $quantity, (int)$variant['price']);

        header("Location: index.php?act=cart");
        exit;
    }

    public function cart()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?act=loginUser");
            exit;
        }

        $productModel = new Product();
        $cartItems = $productModel->getCartItemsByUserId($_SESSION['user_id']);

        require_once __DIR__ . '/../views/client/giaodien/cart.php';
    }

    public function updateCart()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?act=loginUser");
            exit;
        }

        $cartId = (int)($_POST['cart_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($cartId > 0 && $quantity > 0) {
            $productModel = new Product();
            $productModel->updateCartQuantity($cartId, $quantity, $_SESSION['user_id']);
        }

        header("Location: index.php?act=cart");
        exit;
    }

    public function deleteCart()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?act=loginUser");
            exit;
        }

        $cartId = (int)($_GET['id'] ?? 0);

        if ($cartId > 0) {
            $productModel = new Product();
            $productModel->deleteCartItem($cartId, $_SESSION['user_id']);
        }

        header("Location: index.php?act=cart");
        exit;
    }
}