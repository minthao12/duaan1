<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = $errors ?? [];
$message = $message ?? '';

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký người dùng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/Duan1/mvc-oop-basic/views/client/giaodien/">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            min-height:100vh;
            display:flex;
            align-items:center;
            background:linear-gradient(135deg,#f8fafc,#dbeafe);
        }
        .register-card{
            border:none;
            border-radius:24px;
            box-shadow:0 20px 50px rgba(0,0,0,.12);
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <form method="POST" action="/Duan1/mvc-oop-basic/index.php?act=registerUser" class="card register-card p-4 p-lg-5">
                <h3 class="text-center mb-4">Đăng ký tài khoản</h3>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $eItem): ?>
                                <li><?= e($eItem) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($message !== ''): ?>
                    <div class="alert alert-info"><?= e($message) ?></div>
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <input name="username" class="form-control" placeholder="Username" value="<?= e($_POST['username'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <input name="email" type="email" class="form-control" placeholder="Email" value="<?= e($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <input name="std" class="form-control" placeholder="Số điện thoại" value="<?= e($_POST['std'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <input name="diachi" class="form-control" placeholder="Địa chỉ" value="<?= e($_POST['diachi'] ?? '') ?>">
                    </div>

                    <div class="col-12">
                        <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                    </div>
                </div>

                <button class="btn btn-success w-100 mt-4 py-2">Đăng ký</button>

                <div class="text-center mt-3">
                    <a href="/Duan1/mvc-oop-basic/index.php?act=loginUser">Đã có tài khoản? Đăng nhập</a>
                </div>

                <div class="text-center mt-2">
                    <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="text-muted">← Quay lại trang chủ</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>