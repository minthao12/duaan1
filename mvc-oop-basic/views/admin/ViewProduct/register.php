<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1e293b, #3b82f6);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-card {
            width: 420px;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-custom {
            background: linear-gradient(45deg, #3b82f6, #667eea);
            border: none;
            border-radius: 10px;
        }

        .btn-custom:hover {
            opacity: 0.9;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #3b82f6;
        }
    </style>
</head>
<body>

<form method="POST" class="register-card">
    <div class="text-center mb-3">
        <div class="logo">HDTT</div>
        <h4>Đăng ký tài khoản</h4>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <!-- Username -->
    <div class="mb-2">
        <label class="form-label">Tài khoản</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input name="username" class="form-control" placeholder="Nhập username" required>
        </div>
    </div>

    <!-- Email -->
    <div class="mb-2">
        <label class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input name="email" type="email" class="form-control" placeholder="Nhập email" required>
        </div>
    </div>

    <!-- SĐT -->
    <div class="mb-2">
        <label class="form-label">Số điện thoại</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input name="std" class="form-control" placeholder="Nhập số điện thoại" required>
        </div>
    </div>

    <!-- Địa chỉ -->
    <div class="mb-2">
        <label class="form-label">Địa chỉ</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
            <input name="diachi" class="form-control" placeholder="Nhập địa chỉ" required>
        </div>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label class="form-label">Mật khẩu</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
        </div>
    </div>

    <!-- Button -->
    <button class="btn btn-custom w-100 text-white">
        <i class="bi bi-person-plus"></i> Đăng ký
    </button>

    <!-- Link -->
    <div class="text-center mt-3">
        <span>Đã có tài khoản?</span>
        <a href="?act=login">Đăng nhập</a>
    </div>

    <div class="text-center mt-2">
        <a href="?act=/" class="text-muted">← Quay về Dashboard</a>
    </div>
</form>

</body>
</html>