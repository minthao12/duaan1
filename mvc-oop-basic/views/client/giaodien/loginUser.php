<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = $errors ?? [];
$error = $error ?? '';
$success = $_SESSION['success_register'] ?? '';
unset($_SESSION['success_register']);

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập người dùng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/Duan1/mvc-oop-basic/views/client/giaodien/">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            min-height:100vh;
            display:flex;
            align-items:center;
            background:linear-gradient(135deg,#eff6ff,#dbeafe);
        }
        .auth-card{
            border:none;
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 20px 50px rgba(0,0,0,.12);
        }
        .auth-left{
            background:linear-gradient(135deg,#2563eb,#1d4ed8);
            color:#fff;
            padding:40px 30px;
            min-height:100%;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card auth-card">
                <div class="row g-0">
                    <div class="col-md-5 auth-left d-flex flex-column justify-content-center">
                        <h2 class="fw-bold">HDTT Store</h2>
                        <p class="mb-0">Đăng nhập để mua hàng và quản lý tài khoản.</p>
                    </div>

                    <div class="col-md-7 p-4 p-lg-5">
                        <form method="POST" action="/Duan1/mvc-oop-basic/index.php?act=loginUser">
                            <h3 class="text-center mb-4">Đăng nhập</h3>

                            <?php if ($success !== ''): ?>
                                <div class="alert alert-success"><?= e($success) ?></div>
                            <?php endif; ?>

                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $eItem): ?>
                                            <li><?= e($eItem) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if ($error !== ''): ?>
                                <div class="alert alert-danger"><?= e($error) ?></div>
                            <?php endif; ?>

                            <input name="username" class="form-control mb-3" placeholder="Tên đăng nhập" value="<?= e($_POST['username'] ?? '') ?>">
                            <input type="password" name="password" class="form-control mb-3" placeholder="Mật khẩu">

                            <button class="btn btn-primary w-100 py-2">Đăng nhập</button>

                            <div class="text-center mt-3">
                                <a href="/Duan1/mvc-oop-basic/index.php?act=registerUser">Chưa có tài khoản? Đăng ký</a>
                            </div>

                            <div class="text-center mt-2">
                                <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="text-muted">← Quay lại trang chủ</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>