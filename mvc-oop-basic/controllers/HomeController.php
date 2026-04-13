<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

class HomeController
{
    public function __construct()
    {
    }

    private function requireLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?act=loginUser");
            exit;
        }
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

    $id = (int)($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo "ID sản phẩm không hợp lệ!";
        return;
    }

    $productModel = new Product();
    $variants = $productModel->getProductDetailWithVariants($id);

    if (empty($variants)) {
        echo "Không tìm thấy sản phẩm!";
        return;
    }

    $firstVariant = $variants[0];

    $colors = [];
    $sizes = [];

    foreach ($variants as $item) {
        if (!empty($item['color_id']) && !empty($item['color_name'])) {
            $colors[$item['color_id']] = $item['color_name'];
        }

        if (!empty($item['size_id']) && !empty($item['size_name'])) {
            $sizes[$item['size_id']] = $item['size_name'];
        }
    }

    require_once __DIR__ . '/../views/client/giaodien/detailProduct.php';
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
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
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

    public function registerUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $message = "";
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $std      = trim($_POST['std'] ?? '');
            $diachi   = trim($_POST['diachi'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '') {
                $errors[] = "Username không được để trống.";
            } elseif (mb_strlen($username) < 3) {
                $errors[] = "Username phải có ít nhất 3 ký tự.";
            }

            if ($email === '') {
                $errors[] = "Email không được để trống.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ.";
            }

            if ($std === '') {
                $errors[] = "Số điện thoại không được để trống.";
            } elseif (!preg_match('/^[0-9]{9,11}$/', $std)) {
                $errors[] = "Số điện thoại phải từ 9 đến 11 chữ số.";
            }

            if ($diachi === '') {
                $errors[] = "Địa chỉ không được để trống.";
            }

            if ($password === '') {
                $errors[] = "Mật khẩu không được để trống.";
            } elseif (strlen($password) < 6) {
                $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
            }

            if (empty($errors)) {
                $userModel = new User();
                $ok = $userModel->register($username, $email, $std, $diachi, $password);

                if ($ok) {
                    header("Location: index.php?act=loginUser");
                    exit;
                } else {
                    $message = "Đăng ký thất bại!";
                }
            }
        }

        require_once __DIR__ . '/../views/client/giaodien/registerUser.php';
    }

    public function addToCart()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=giaodien");
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $colorId   = (int)($_POST['color_id'] ?? 0);
        $sizeId    = (int)($_POST['size_id'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 1);

        if ($productId <= 0 || $colorId <= 0 || $sizeId <= 0 || $quantity <= 0) {
            echo "Dữ liệu thêm giỏ hàng không hợp lệ!";
            return;
        }

        $productModel = new Product();
        $variant = $productModel->getVariantByProductColorSize($productId, $colorId, $sizeId);

        if (!$variant) {
            echo "Không tìm thấy biến thể sản phẩm!";
            return;
        }

        if ($quantity > (int)$variant['stock']) {
            echo "Số lượng vượt quá tồn kho!";
            return;
        }

        $productModel->addToCart($_SESSION['user_id'], $variant['id'], $quantity);

        header("Location: index.php?act=cart");
        exit;
    }

    public function cart()
{
    $this->requireLogin();

    $productModel = new Product();
    $cartItems = $productModel->getCartByUser($_SESSION['user_id']);

    require_once __DIR__ . '/../views/client/giaodien/cart.php';
    }

    public function updateCart()
{
    $this->requireLogin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?act=cart");
        exit;
    }

    $cartId = (int)($_POST['cart_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);

    if ($cartId <= 0 || $quantity <= 0) {
        echo "<script>alert('Số lượng không hợp lệ'); window.location='index.php?act=cart';</script>";
        exit;
    }

    $productModel = new Product();
    $cartItem = $productModel->getCartItemById($cartId, $_SESSION['user_id']);

    if (!$cartItem) {
        echo "<script>alert('Không tìm thấy sản phẩm trong giỏ hàng'); window.location='index.php?act=cart';</script>";
        exit;
    }

    if ($quantity > (int)$cartItem['stock']) {
        echo "<script>alert('Số lượng vượt quá tồn kho của sản phẩm " . addslashes($cartItem['product_name']) . "'); window.location='index.php?act=cart';</script>";
        exit;
    }

    $productModel->updateCartQuantity($cartId, $quantity, $_SESSION['user_id']);

    header("Location: index.php?act=cart");
    exit;
}

    public function deleteCart()
    {
        $this->requireLogin();

        $cartId = (int)($_GET['id'] ?? 0);

        if ($cartId > 0) {
            $productModel = new Product();
            $productModel->deleteCartItem($cartId, $_SESSION['user_id']);
        }

        header("Location: index.php?act=cart");
        exit;
    }

    public function checkout()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=cart");
            exit;
        }

        $selectedCartIds = $_POST['selected_cart'] ?? [];

        if (empty($selectedCartIds)) {
            echo "<script>alert('Bạn chưa chọn sản phẩm để thanh toán'); window.location='index.php?act=cart';</script>";
            exit;
        }

        $productModel = new Product();
        $userModel = new User();

        $checkoutItems = $productModel->getCartItemsByIds($_SESSION['user_id'], $selectedCartIds);

        if (empty($checkoutItems)) {
            echo "<script>alert('Không có sản phẩm hợp lệ để thanh toán'); window.location='index.php?act=cart';</script>";
            exit;
        }

        $user = $userModel->getUserById($_SESSION['user_id']);

        $subTotal = 0;
        foreach ($checkoutItems as $item) {
            $subTotal += $item['price'] * $item['quantity'];
        }

        $shippingFee = 30000;
        $grandTotal = $subTotal + $shippingFee;

        require_once __DIR__ . '/../views/client/giaodien/checkout.php';
    }

    public function placeOrder()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=cart");
            exit;
        }

        $selectedCartIds = $_POST['selected_cart'] ?? [];
        $receiverName    = trim($_POST['receiver_name'] ?? '');
        $receiverPhone   = trim($_POST['receiver_phone'] ?? '');
        $receiverAddress = trim($_POST['receiver_address'] ?? '');
        $paymentMethod   = trim($_POST['payment_method'] ?? 'cod');

        if (empty($selectedCartIds)) {
            echo "<script>alert('Không có sản phẩm nào được chọn'); window.location='index.php?act=cart';</script>";
            exit;
        }

        $errors = [];

        if ($receiverName === '') {
            $errors[] = "Họ tên người nhận không được để trống.";
        }

        if ($receiverPhone === '') {
            $errors[] = "Số điện thoại người nhận không được để trống.";
        } elseif (!preg_match('/^[0-9]{9,11}$/', $receiverPhone)) {
            $errors[] = "Số điện thoại người nhận phải từ 9 đến 11 số.";
        }

        if ($receiverAddress === '') {
            $errors[] = "Địa chỉ nhận hàng không được để trống.";
        }

        if ($paymentMethod !== 'cod') {
            $errors[] = "Hiện tại chỉ hỗ trợ thanh toán khi nhận hàng.";
        }

        $productModel = new Product();
        $userModel = new User();

        $checkoutItems = $productModel->getCartItemsByIds($_SESSION['user_id'], $selectedCartIds);
        $user = $userModel->getUserById($_SESSION['user_id']);

        if (empty($checkoutItems)) {
            echo "<script>alert('Sản phẩm thanh toán không hợp lệ'); window.location='index.php?act=cart';</script>";
            exit;
        }

        $subTotal = 0;
        foreach ($checkoutItems as $item) {
            if ($item['quantity'] > $item['stock']) {
                $errors[] = "Sản phẩm " . $item['product_name'] . " không đủ tồn kho.";
            }
            $subTotal += $item['price'] * $item['quantity'];
        }

        $shippingFee = 30000;
        $grandTotal = $subTotal + $shippingFee;

        if (!empty($errors)) {
            require_once __DIR__ . '/../views/client/giaodien/checkout.php';
            return;
        }

        $orderId = $productModel->createOrder([
            'user_id' => $_SESSION['user_id'],
            'total' => $grandTotal,
            'status' => 'cho_xac_nhan',
            'payment_status' => 'unpaid',
            'online' => 'no',
            'receiver_name' => $receiverName,
            'receiver_phone' => $receiverPhone,
            'receiver_address' => $receiverAddress,
            'shipping_fee' => $shippingFee,
            'payment_method' => 'cod',
        ]);

        if (!$orderId) {
            echo "Tạo đơn hàng thất bại!";
            return;
        }

        foreach ($checkoutItems as $item) {
            $productModel->addOrderDetail(
                $orderId,
                $item['variant_id'],
                $item['quantity'],
                $item['price']
            );

            $productModel->updateVariantStock(
                $item['variant_id'],
                $item['quantity']
            );
        }

        $productModel->removeManyCartItems($_SESSION['user_id'], $selectedCartIds);

        echo "<script>alert('Đặt hàng thành công'); window.location='index.php?act=myOrders';</script>";
        exit;
    }

    public function myOrders()
    {
    $this->requireLogin();

    $productModel = new Product();
    $orders = $productModel->getOrderHistoryByUser($_SESSION['user_id']);

    require_once __DIR__ . '/../views/client/giaodien/myOrders.php';
    }
    public function orderDetail()
{
    $this->requireLogin();

    $orderId = (int)($_GET['id'] ?? 0);

    if ($orderId <= 0) {
        echo "ID đơn hàng không hợp lệ!";
        return;
    }

    $productModel = new Product();

    $order = $productModel->getOrderByIdAndUser($orderId, $_SESSION['user_id']);

    if (!$order) {
        echo "Không tìm thấy đơn hàng của bạn!";
        return;
    }

    $orderDetails = $productModel->getOrderDetails($orderId);

    require_once __DIR__ . '/../views/client/giaodien/orderDetail.php';
}
}