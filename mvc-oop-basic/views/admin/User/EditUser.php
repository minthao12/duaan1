<?php session_start(); ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa người dùng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .card {
            max-width: 500px;
            margin: 50px auto;
            border-radius: 15px;
        }
    </style>
</head>
<body>

<div class="card shadow p-4">
    <h3 class="text-center mb-3">✏️ Sửa người dùng</h3>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" class="form-control mb-2"
               value="<?= $user['username'] ?>" required>

        <input type="email" name="email" class="form-control mb-2"
               value="<?= $user['email'] ?>" required>

        <input type="text" name="std" class="form-control mb-2"
               value="<?= $user['std'] ?>" required>

        <input type="text" name="diachi" class="form-control mb-3"
               value="<?= $user['diachi'] ?>" required>

        <button class="btn btn-warning w-100">Cập nhật</button>
    </form>

    <a href="?act=users" class="btn btn-secondary w-100 mt-2">← Quay lại</a>
</div>

</body>
</html>