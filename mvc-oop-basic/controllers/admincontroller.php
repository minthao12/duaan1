<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

class admincontroller
{
    private Product $productModel;
    private User $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->productModel = new Product();
        $this->userModel = new User();
    }

    private function view(string $path, array $data = []): void
    {
        extract($data);

        $file = __DIR__ . '/../views/' . $path . '.php';

        if (!file_exists($file)) {
            die('Không tìm thấy file view: ' . $file);
        }

        require_once $file;
    }

    private function redirect(string $act): void
    {
        header('Location: index.php?act=' . $act);
        exit;
    }

    private function requireAdmin(): void
    {
        if (empty($_SESSION['user_id']) || (($_SESSION['role'] ?? 'user') !== 'admin')) {
            $this->redirect('login');
        }
    }

    public function dashboard(): void
    {
        $this->requireAdmin();

        $keyword = trim($_GET['keyword'] ?? '');
        $products = $this->productModel->getAllProducts($keyword);

        $this->view('admin/main', [
            'products' => $products
        ]);
    }

    public function login(): void
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = $this->userModel->findByUsername($username);

            if (!$user) {
                $error = 'Tài khoản không tồn tại';
            } else {
                $passwordMatched = false;

                if (isset($user['password']) && password_verify($password, $user['password'])) {
                    $passwordMatched = true;
                }

                if (isset($user['password']) && $user['password'] === $password) {
                    $passwordMatched = true;
                }

                if (!$passwordMatched) {
                    $error = 'Sai mật khẩu';
                } elseif (($user['role'] ?? 'user') !== 'admin') {
                    $error = 'Tài khoản này không có quyền admin';
                } else {
                    $_SESSION['user_id'] = (int)$user['id'];
                    $_SESSION['user'] = $user['username'];
                    $_SESSION['role'] = $user['role'] ?? 'admin';

                    $this->redirect('admin');
                }
            }
        }

        $this->view('admin/ViewProduct/login', [
            'error' => $error
        ]);
    }

    public function register(): void
    {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $std = trim($_POST['std'] ?? '');
            $diachi = trim($_POST['diachi'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($username !== '' && $email !== '' && $password !== '') {
                $ok = $this->userModel->create([
                    'username' => $username,
                    'email' => $email,
                    'std' => $std,
                    'diachi' => $diachi,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => 'admin'
                ]);

                if ($ok) {
                    $message = 'Đăng ký admin thành công';
                } else {
                    $message = 'Đăng ký thất bại';
                }
            }
        }

        $this->view('admin/ViewProduct/register', [
            'message' => $message
        ]);
    }

    public function adminProduct(): void
    {
        $this->requireAdmin();

        $keyword = trim($_GET['keyword'] ?? '');
        $products = $this->productModel->getAllProducts($keyword);

        $this->view('admin/ViewProduct/adminProduct', [
            'products' => $products
        ]);
    }

    public function addProduct(): void
    {
        $this->requireAdmin();

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $code = trim($_POST['code'] ?? '');
            $price = (int)($_POST['price'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 0);
            $status = (int)($_POST['status'] ?? 1);
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $errors[] = 'Tên sản phẩm không được để trống';
            }

            if ($code === '') {
                $errors[] = 'Mã sản phẩm không được để trống';
            } elseif ($this->productModel->findProductByCode($code)) {
                $errors[] = 'Mã sản phẩm đã tồn tại';
            }

            if ($price <= 0) {
                $errors[] = 'Giá phải lớn hơn 0';
            }

            if ($quantity < 0) {
                $errors[] = 'Số lượng không hợp lệ';
            }

            if (empty($errors)) {
                $this->productModel->createProduct([
                    'name' => $name,
                    'code' => $code,
                    'price' => $price,
                    'quantity' => $quantity,
                    'status' => $status,
                    'description' => $description
                ]);

                $this->redirect('adminProduct');
            }
        }

        $this->view('admin/add_product', [
            'errors' => $errors
        ]);
    }

    public function editProduct(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $this->redirect('adminProduct');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $code = trim($_POST['code'] ?? '');
            $price = (int)($_POST['price'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 0);
            $status = (int)($_POST['status'] ?? 1);
            $description = trim($_POST['description'] ?? '');

            $this->productModel->updateProduct($id, [
                'name' => $name,
                'code' => $code,
                'price' => $price,
                'quantity' => $quantity,
                'status' => $status,
                'description' => $description
            ]);

            $this->redirect('adminProduct');
        }

        $this->view('admin/editproduct', [
            'product' => $product
        ]);
    }

    public function deleteProduct(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->productModel->deleteProduct($id);
        }

        $this->redirect('adminProduct');
    }

    public function detail(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $this->redirect('adminProduct');
        }

        $this->view('admin/ViewProduct/DetailProduct', [
            'product' => $product
        ]);
    }

    public function ProductUser(): void
    {
        $this->requireAdmin();

        $keyword = trim($_GET['keyword'] ?? '');
        $variants = $this->productModel->getAllVariants($keyword);

        $this->view('admin/ProductUser', [
            'variants' => $variants
        ]);
    }

    public function CateProduct(): void
    {
        $this->requireAdmin();

        $variants = $this->productModel->getAllVariants();

        $this->view('admin/ViewProduct/CateProduct', [
            'variants' => $variants
        ]);
    }

    public function addCateProduct(): void
    {
        $this->requireAdmin();

        $errors = [];
        $products = $this->productModel->getSimpleProducts();
        $colors = $this->productModel->getColors();
        $sizes = $this->productModel->getSizes();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)($_POST['product_id'] ?? 0);
            $colorId = (int)($_POST['color_id'] ?? 0);
            $sizeId = (int)($_POST['size_id'] ?? 0);
            $price = (int)($_POST['price'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? $_POST['stock'] ?? 0);

            if ($productId <= 0) {
                $errors[] = 'Vui lòng chọn sản phẩm';
            }

            if ($colorId <= 0) {
                $errors[] = 'Vui lòng chọn màu';
            }

            if ($sizeId <= 0) {
                $errors[] = 'Vui lòng chọn size';
            }

            if ($price <= 0) {
                $errors[] = 'Giá phải lớn hơn 0';
            }

            if ($quantity < 0) {
                $errors[] = 'Số lượng không hợp lệ';
            }

            $imageName = '';
            if (!empty($_FILES['image']['name'])) {
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $target = __DIR__ . '/../uploads/' . $imageName;
                @move_uploaded_file($_FILES['image']['tmp_name'], $target);
            }

            if (empty($errors)) {
                $this->productModel->createVariant([
                    'product_id' => $productId,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                    'price' => $price,
                    'quantity' => $quantity,
                    'image' => $imageName
                ]);

                $this->redirect('CateProduct');
            }
        }

        $this->view('admin/ViewProduct/AddCateProduct', [
            'products' => $products,
            'colors' => $colors,
            'sizes' => $sizes,
            'errors' => $errors
        ]);
    }

    public function editCateProduct(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $variant = $this->productModel->getVariantById($id);

        if (!$variant) {
            $this->redirect('CateProduct');
        }

        $products = $this->productModel->getSimpleProducts();
        $colors = $this->productModel->getColors();
        $sizes = $this->productModel->getSizes();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)($_POST['product_id'] ?? 0);
            $colorId = (int)($_POST['color_id'] ?? 0);
            $sizeId = (int)($_POST['size_id'] ?? 0);
            $price = (int)($_POST['price'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? $_POST['stock'] ?? 0);

            $imageName = $variant['image'] ?? '';
            if (!empty($_FILES['image']['name'])) {
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $target = __DIR__ . '/../uploads/' . $imageName;
                @move_uploaded_file($_FILES['image']['tmp_name'], $target);
            }

            $this->productModel->updateVariant($id, [
                'product_id' => $productId,
                'color_id' => $colorId,
                'size_id' => $sizeId,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $imageName
            ]);

            $this->redirect('CateProduct');
        }

        $this->view('admin/ViewProduct/EditCateProduct', [
            'variant' => $variant,
            'products' => $products,
            'colors' => $colors,
            'sizes' => $sizes
        ]);
    }

    public function deleteCateProduct(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->productModel->deleteVariant($id);
        }

        $this->redirect('CateProduct');
    }

    public function users(): void
    {
        $this->requireAdmin();

        $users = $this->userModel->getAll();

        $this->view('admin/User/users', [
            'users' => $users
        ]);
    }

    public function editUser(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->findById($id);

        if (!$user) {
            $this->redirect('users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->update($id, [
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'std' => trim($_POST['std'] ?? ''),
                'diachi' => trim($_POST['diachi'] ?? '')
            ]);

            $this->redirect('users');
        }

        $this->view('admin/User/EditUser', [
            'user' => $user
        ]);
    }

    public function deleteUser(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->userModel->delete($id);
        }

        $this->redirect('users');
    }
}