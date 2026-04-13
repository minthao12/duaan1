<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

class admincontroller {

    public function dashboard() {
        $productModel = new Product();
        $keyword = trim($_GET['keyword'] ?? '');
        $products = $productModel->getAllProducts($keyword);

        require_once __DIR__ . '/../views/admin/main.php';
    }

    public function home() {
        $this->dashboard();
    }

    public function detail() {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID sản phẩm không hợp lệ!";
            return;
        }

        $productModel = new Product();
        $item = $productModel->getProductDetailById((int)$id);

        if ($item) {
            require_once __DIR__ . '/../views/admin/ViewProduct/DetailProduct.php';
        } else {
            echo "Không tìm thấy sản phẩm này trong cơ sở dữ liệu!";
        }
    }

    public function ProductUser() {
        $productModel = new Product();
        $keyword = trim($_GET['keyword'] ?? '');
        $variants = $productModel->getAllVariants($keyword);

        require_once __DIR__ . '/../views/admin/ProductUser.php';
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = "";
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_id'] = $user['id'];

                    header("Location: index.php?act=adminProduct");
                    exit;
                } else {
                    $error = "Sai tài khoản hoặc mật khẩu!";
                }
            }
        }

        require_once __DIR__ . '/../views/admin/ViewProduct/login.php';
    }

    public function register() {
        $message = "";
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

                $ok = $userModel->register(
                    $username,
                    $email,
                    $std,
                    $diachi,
                    $password
                );

                if ($ok) {
                    $message = "Đăng ký thành công!";
                } else {
                    $message = "Đăng ký thất bại!";
                }
            }
        }

        require_once __DIR__ . '/../views/admin/ViewProduct/register.php';
    }

    public function adminProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $productModel = new Product();
        $keyword = trim($_GET['keyword'] ?? '');
        $products = $productModel->getAllProducts($keyword);

        require_once __DIR__ . '/../views/admin/ViewProduct/adminProduct.php';
    }

    public function addProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $productModel = new Product();
        $categories = $productModel->getCategories();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $errors[] = "Tên sản phẩm không được để trống.";
            }

            if ($category_id <= 0) {
                $errors[] = "Vui lòng chọn danh mục.";
            }

            if ($description === '') {
                $errors[] = "Mô tả không được để trống.";
            }

            if (empty($errors)) {
                $data = [
                    'name' => $name,
                    'category_id' => $category_id,
                    'description' => $description
                ];

                $ok = $productModel->addProduct($data);

                if ($ok) {
                    header("Location: index.php?act=adminProduct");
                    exit;
                } else {
                    $errors[] = "Thêm sản phẩm thất bại.";
                }
            }
        }

        require_once __DIR__ . '/../views/admin/ViewProduct/add_product.php';
    }

    public function editProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID sản phẩm không hợp lệ!";
            return;
        }

        $productModel = new Product();
        $product = $productModel->getProductById((int)$id);

        if (!$product) {
            echo "Không tìm thấy sản phẩm cần sửa!";
            return;
        }

        $categories = $productModel->getCategories();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $errors[] = "Tên sản phẩm không được để trống.";
            }

            if ($category_id <= 0) {
                $errors[] = "Vui lòng chọn danh mục.";
            }

            if ($description === '') {
                $errors[] = "Mô tả không được để trống.";
            }

            if (empty($errors)) {
                $data = [
                    'name' => $name,
                    'category_id' => $category_id,
                    'description' => $description
                ];

                $ok = $productModel->updateProduct((int)$id, $data);

                if ($ok) {
                    header("Location: index.php?act=adminProduct");
                    exit;
                } else {
                    $errors[] = "Cập nhật sản phẩm thất bại.";
                }
            }

            $product['name'] = $name;
            $product['category_id'] = $category_id;
            $product['description'] = $description;
        }

        require_once __DIR__ . '/../views/admin/ViewProduct/editproduct.php';
    }

    public function deleteProduct() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?act=login");
        exit;
    }

    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id) || (int)$id <= 0) {
        echo "ID sản phẩm không hợp lệ!";
        return;
    }

    $productModel = new Product();
    $product = $productModel->getProductById((int)$id);

    if (!$product) {
        echo "Không tìm thấy sản phẩm để xóa!";
        return;
    }

    if ($productModel->hasOrder((int)$id)) {
        echo "Không thể xóa vì sản phẩm đã có đơn hàng!";
        return;
    }

    $ok = $productModel->deleteProductSafe((int)$id);

    if ($ok) {
        header("Location: index.php?act=adminProduct");
        exit;
    }

    echo "Xóa sản phẩm thất bại!";
}

    public function users() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $userModel = new User();
        $users = $userModel->getAll();

        require_once __DIR__ . '/../views/admin/User/users.php';
    }

    public function deleteUser() {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID người dùng không hợp lệ!";
            return;
        }

        $userModel = new User();
        $user = $userModel->findById((int)$id);

        if (!$user) {
            echo "Không tìm thấy người dùng để xóa!";
            return;
        }

        $userModel->delete((int)$id);

        header("Location: index.php?act=users");
        exit;
    }

    public function editUser() {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID người dùng không hợp lệ!";
            return;
        }

        $userModel = new User();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $std      = trim($_POST['std'] ?? '');
            $diachi   = trim($_POST['diachi'] ?? '');

            if ($username === '') {
                $errors[] = "Username không được để trống.";
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

            if (empty($errors)) {
                $userModel->update((int)$id, [
                    'username' => $username,
                    'email'    => $email,
                    'std'      => $std,
                    'diachi'   => $diachi
                ]);

                header("Location: index.php?act=users");
                exit;
            }
        }

        $user = $userModel->findById((int)$id);

        if (!$user) {
            echo "Không tìm thấy người dùng!";
            return;
        }

        require_once __DIR__ . '/../views/admin/User/EditUser.php';
    }

    public function CateProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $productModel = new Product();
        $variants = $productModel->getAllVariants();

        require_once __DIR__ . '/../views/admin/ViewProduct/CateProduct.php';
    }

    public function addCateProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $productModel = new Product();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'] ?? '';
            $color_id   = $_POST['color_id'] ?? '';
            $size_id    = $_POST['size_id'] ?? '';
            $price      = $_POST['price'] ?? '';
            $stock      = $_POST['stock'] ?? '';

            if ($product_id === '' || !is_numeric($product_id) || (int)$product_id <= 0) {
                $errors[] = "Vui lòng chọn sản phẩm.";
            }

            if ($color_id === '' || !is_numeric($color_id) || (int)$color_id <= 0) {
                $errors[] = "Vui lòng chọn màu sắc.";
            }

            if ($size_id === '' || !is_numeric($size_id) || (int)$size_id <= 0) {
                $errors[] = "Vui lòng chọn kích thước.";
            }

            if ($price === '' || !is_numeric($price) || (int)$price < 0) {
                $errors[] = "Giá sản phẩm phải là số và không được âm.";
            }

            if ($stock === '' || !is_numeric($stock) || (int)$stock < 0) {
                $errors[] = "Tồn kho phải là số và không được âm.";
            }

            $imageName = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowedExt)) {
                    $errors[] = "Ảnh chỉ được phép là jpg, jpeg, png, webp.";
                }

                if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                    $errors[] = "Ảnh không được lớn hơn 2MB.";
                }

                if (empty($errors)) {
                    $imageName = time() . '_' . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
                }
            } else {
                $errors[] = "Vui lòng chọn ảnh sản phẩm.";
            }

            if (empty($errors)) {
                $data = [
                    'product_id' => (int)$product_id,
                    'color_id'   => (int)$color_id,
                    'size_id'    => (int)$size_id,
                    'image'      => $imageName,
                    'price'      => (int)$price,
                    'stock'      => (int)$stock
                ];

                $productModel->addVariant($data);
                header("Location: index.php?act=CateProduct");
                exit;
            }
        }

        $products = $productModel->getProducts();
        $colors   = $productModel->getColors();
        $sizes    = $productModel->getSizes();

        require_once __DIR__ . '/../views/admin/ViewProduct/AddCateProduct.php';
    }

    public function editCateProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID danh mục sản phẩm không hợp lệ!";
            return;
        }

        $productModel = new Product();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'] ?? '';
            $color_id   = $_POST['color_id'] ?? '';
            $size_id    = $_POST['size_id'] ?? '';
            $price      = $_POST['price'] ?? '';
            $stock      = $_POST['stock'] ?? '';
            $old_image  = $_POST['old_image'] ?? '';

            if ($product_id === '' || !is_numeric($product_id) || (int)$product_id <= 0) {
                $errors[] = "Vui lòng chọn sản phẩm.";
            }

            if ($color_id === '' || !is_numeric($color_id) || (int)$color_id <= 0) {
                $errors[] = "Vui lòng chọn màu sắc.";
            }

            if ($size_id === '' || !is_numeric($size_id) || (int)$size_id <= 0) {
                $errors[] = "Vui lòng chọn kích thước.";
            }

            if ($price === '' || !is_numeric($price) || (int)$price < 0) {
                $errors[] = "Giá sản phẩm phải là số và không được âm.";
            }

            if ($stock === '' || !is_numeric($stock) || (int)$stock < 0) {
                $errors[] = "Tồn kho phải là số và không được âm.";
            }

            $imageName = $old_image;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowedExt)) {
                    $errors[] = "Ảnh chỉ được phép là jpg, jpeg, png, webp.";
                }

                if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                    $errors[] = "Ảnh không được lớn hơn 2MB.";
                }

                if (empty($errors)) {
                    $imageName = time() . '_' . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
                }
            }

            if (empty($errors)) {
                $data = [
                    'product_id' => (int)$product_id,
                    'color_id'   => (int)$color_id,
                    'size_id'    => (int)$size_id,
                    'image'      => $imageName,
                    'price'      => (int)$price,
                    'stock'      => (int)$stock
                ];

                $productModel->updateVariant((int)$id, $data);
                header("Location: index.php?act=CateProduct");
                exit;
            }
        }

        $variant = $productModel->getVariantById((int)$id);

        if (!$variant) {
            echo "Không tìm thấy biến thể hoặc biến thể này đã bị ẩn!";
            return;
        }

        $products = $productModel->getProducts();
        $colors   = $productModel->getColors();
        $sizes    = $productModel->getSizes();

        require_once __DIR__ . '/../views/admin/ViewProduct/EditCateProduct.php';
    }

    public function deleteCateProduct() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?act=login");
        exit;
    }

    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id) || (int)$id <= 0) {
        echo "ID danh mục sản phẩm không hợp lệ!";
        return;
    }

    $productModel = new Product();

    // Lấy thông tin variant còn hoạt động
    $variant = $productModel->getVariantById((int)$id);

    if (!$variant) {
        echo "Không tìm thấy dữ liệu để ẩn!";
        return;
    }

    $ok = $productModel->deleteVariant((int)$id);

    if ($ok) {
        header("Location: index.php?act=CateProduct");
        exit;
    }

    echo "Ẩn biến thể sản phẩm thất bại!";
    }

    public function donhang() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?act=login");
        exit;
    }

    $productModel = new Product();
    $orders = $productModel->getAllOrders();

    require_once __DIR__ . '/../views/admin/Order/donhang.php';
}

public function detailOrder()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?act=login");
        exit;
    }

    $orderId = (int)($_GET['id'] ?? 0);

    if ($orderId <= 0) {
        echo "ID đơn hàng không hợp lệ!";
        return;
    }

    $productModel = new Product();
    $order = $productModel->getOrderById($orderId);

    if (!$order) {
        echo "Không tìm thấy đơn hàng!";
        return;
    }

    $orderDetails = $productModel->getOrderDetails($orderId);

    require_once __DIR__ . '/../views/admin/Order/detailOrder.php';
}

public function thongke()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?act=login");
        exit;
    }

    $productModel = new Product();
    $thongke = $productModel->getThongKeDoanhThu();

    require_once __DIR__ . '/../views/admin/Order/thongke.php';
}

public function updateOrderStatus() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?act=login");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?act=donhang");
        exit;
    }

    $orderId = (int)($_POST['order_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    $allowStatus = [
        'cho_xac_nhan',
        'dang_lay_hang',
        'dang_van_chuyen',
        'da_van_chuyen',
        'hoan_thanh',
        'da_huy'
    ];

    if ($orderId <= 0 || !in_array($status, $allowStatus, true)) {
        echo "Dữ liệu cập nhật không hợp lệ!";
        return;
    }

    $productModel = new Product();
    $order = $productModel->getOrderById($orderId);

    if (!$order) {
        echo "Không tìm thấy đơn hàng!";
        return;
    }

    // không cho hoàn thành quay về chờ xác nhận
    if ($order['status'] === 'hoan_thanh' && $status === 'cho_xac_nhan') {
        echo "Đơn hàng đã hoàn thành, không thể chuyển lại về chờ xác nhận!";
        return;
    }

    // nếu đã hoàn thành thì không cho đổi về các trạng thái trước
    if ($order['status'] === 'hoan_thanh' && $status !== 'hoan_thanh') {
        echo "Đơn hàng đã hoàn thành, không thể đổi trạng thái!";
        return;
    }

    $productModel->updateOrderStatusById($orderId, $status);

    header("Location: index.php?act=donhang");
    exit;
    }
}