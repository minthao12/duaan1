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

    public function users() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $userModel = new User();
        $users = $userModel->getAllUsers();

        require_once __DIR__ . '/../views/admin/User/users.php';
    }

    public function deleteUser() {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID người dùng không hợp lệ!";
            return;
        }

        $userModel = new User();
        $user = $userModel->getUserById((int)$id);

        if (!$user) {
            echo "Không tìm thấy người dùng để xóa!";
            return;
        }

        $userModel->deleteUser((int)$id);

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
                $userModel->updateUser((int)$id, [
                    'username' => $username,
                    'email'    => $email,
                    'std'      => $std,
                    'diachi'   => $diachi
                ]);

                header("Location: index.php?act=users");
                exit;
            }
        }

        $user = $userModel->getUserById((int)$id);

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

            if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
                $errors[] = "Vui lòng chọn ảnh.";
            } else {
                $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowedExt)) {
                    $errors[] = "Ảnh chỉ được phép là jpg, jpeg, png, webp.";
                }

                if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                    $errors[] = "Ảnh không được lớn hơn 2MB.";
                }
            }

            if (empty($errors)) {
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);

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
        $colors = $productModel->getColors();
        $sizes = $productModel->getSizes();

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
            echo "Không tìm thấy dữ liệu cần sửa!";
            return;
        }

        $products = $productModel->getProducts();
        $colors = $productModel->getColors();
        $sizes = $productModel->getSizes();

        require_once __DIR__ . '/../views/admin/ViewProduct/EditCateProduct.php';
    }

    public function deleteCateProduct() {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID danh mục sản phẩm không hợp lệ!";
            return;
        }

        $productModel = new Product();
        $variant = $productModel->getVariantById((int)$id);

        if (!$variant) {
            echo "Không tìm thấy dữ liệu để xóa!";
            return;
        }

        $productModel->deleteVariant((int)$id);

        header("Location: index.php?act=CateProduct");
        exit;
    }
}