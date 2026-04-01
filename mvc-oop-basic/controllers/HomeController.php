<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

class HomeController {

    public function dashboard() {
    $productModel = new Product();

    $keyword = $_GET['keyword'] ?? '';

    $products = $productModel->getAllProducts($keyword);

    require_once __DIR__ . '/../views/admin/main.php';
    }
    public function home() {
            $this->dashboard(); // gọi lại dashboard
        }

    public function detail() {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $productModel = new Product();
            $item = $productModel->getProductDetailById($id); 
            
            if ($item) {
                require_once __DIR__ . '/../views/admin/ViewProduct/DetailProduct.php';
            } else {
                echo "Không tìm thấy sản phẩm này trong cơ sở dữ liệu!";
            }
        } else {
            echo "URL thiếu ID sản phẩm!";
        }
    }

    public function ProductUser() {
    $productModel = new Product();
    $keyword = $_GET['keyword'] ?? '';
    $variants = $productModel->getAllVariants($keyword);

    require_once __DIR__ . '/../views/admin/ProductUser.php';
    }

    // Đây là phần danh mục sản phẩm của admin


    public function login() {
    session_start();
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userModel = new User();

        $user = $userModel->login($_POST['username'], $_POST['password']);

        if ($user) {
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php?act=adminProduct");
            exit;
        } else {
            $error = "Sai tài khoản hoặc mật khẩu!";
        }
    }

    require_once __DIR__ . '/../views/admin/ViewProduct/login.php';
}

    public function register() {
    $message = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userModel = new User();

        $ok = $userModel->register(
            $_POST['username'],
            $_POST['email'],
            $_POST['std'],
            $_POST['diachi'],
            $_POST['password']
        );

        if ($ok) {
            $message = "Đăng ký thành công!";
        } else {
            $message = "Đăng ký thất bại!";
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
    $keyword = $_GET['keyword'] ?? '';
    $products = $productModel->getAllProducts($keyword);

    require_once __DIR__ . '/../views/admin/ViewProduct/adminProduct.php';
    }


    //người dùng
    public function users() {
    session_start();

    // chặn nếu chưa login
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?act=login");
        exit;
    }

    $userModel = new User();
    $users = $userModel->getAllUsers();

    require_once __DIR__ . '/../views/admin/User/users.php';
    }

    public function deleteUser() {
    $id = $_GET['id'];

    $userModel = new User();
    $userModel->deleteUser($id);

    header("Location: index.php?act=users");
    }

    public function editUser() {
    $id = $_GET['id'];
    $userModel = new User();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userModel->updateUser($id, $_POST);
        header("Location: index.php?act=users");
        exit;
    }

    $user = $userModel->getUserById($id);

    require_once __DIR__ . '/../views/admin/User/EditUser.php';
    }

    // Danh mục sản phẩm
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
        $productModel = new Product();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imageName = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
            }

            $data = [
                'product_id' => $_POST['product_id'],
                'color_id'   => $_POST['color_id'],
                'size_id'    => $_POST['size_id'],
                'image'      => $imageName,
                'price'      => $_POST['price'],
                'stock'      => $_POST['stock']
            ];

            $productModel->addVariant($data);
            header("Location: index.php?act=CateProduct");
            exit;
        }

        $products = $productModel->getProducts();
        $colors = $productModel->getColors();
        $sizes = $productModel->getSizes();

        require_once __DIR__ . '/../views/admin/ViewProduct/AddCateProduct.php';
    }

    public function editCateProduct() {
        $id = $_GET['id'];
        $productModel = new Product();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imageName = $_POST['old_image'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
            }

            $data = [
                'product_id' => $_POST['product_id'],
                'color_id'   => $_POST['color_id'],
                'size_id'    => $_POST['size_id'],
                'image'      => $imageName,
                'price'      => $_POST['price'],
                'stock'      => $_POST['stock']
            ];

            $productModel->updateVariant($id, $data);
            header("Location: index.php?act=CateProduct");
            exit;
        }

        $variant = $productModel->getVariantById($id);
        $products = $productModel->getProducts();
        $colors = $productModel->getColors();
        $sizes = $productModel->getSizes();

        require_once __DIR__ . '/../views/admin/ViewProduct/EditCateProduct.php';
    }

public function deleteCateProduct() {
    $id = $_GET['id'];
    $productModel = new Product();
    $productModel->deleteVariant($id);

    header("Location: index.php?act=CateProduct");
    exit;
}
}