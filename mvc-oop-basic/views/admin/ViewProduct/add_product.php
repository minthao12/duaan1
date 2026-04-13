<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = $errors ?? [];
$categories = $categories ?? [];

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow border-0 rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Thêm sản phẩm</h3>
            <a href="?act=adminProduct" class="btn btn-outline-secondary">← Quay lại</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" value="<?= e($_POST['name'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Danh mục</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $cate): ?>
                            <option value="<?= (int)$cate['id'] ?>"
                                <?= (isset($_POST['category_id']) && (int)$_POST['category_id'] === (int)$cate['id']) ? 'selected' : '' ?>>
                                <?= e($cate['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="5"><?= e($_POST['description'] ?? '') ?></textarea>
                </div>
            </div>

            <button class="btn btn-success mt-4">Thêm sản phẩm</button>
        </form>
    </div>
</div>

</body>
</html>