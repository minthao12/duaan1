<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$products = $products ?? [];
$colors = $colors ?? [];
$sizes = $sizes ?? [];
$errors = $errors ?? [];

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm danh mục sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow border-0 rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Thêm danh mục sản phẩm</h3>
            <a href="?act=CateProduct" class="btn btn-outline-secondary">← Quay lại</a>
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

        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Sản phẩm</label>
                    <select name="product_id" class="form-select">
                        <option value="">-- Chọn sản phẩm --</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?= (int)$p['id'] ?>"><?= e($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Màu sắc</label>
                    <select name="color_id" class="form-select">
                        <option value="">-- Chọn màu --</option>
                        <?php foreach ($colors as $c): ?>
                            <option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kích thước</label>
                    <select name="size_id" class="form-select">
                        <option value="">-- Chọn size --</option>
                        <?php foreach ($sizes as $s): ?>
                            <option value="<?= (int)$s['id'] ?>"><?= e($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá</label>
                    <input type="number" name="price" class="form-control" value="<?= e($_POST['price'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" value="<?= e($_POST['quantity'] ?? '') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Ảnh</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>

            <button class="btn btn-primary mt-4">Thêm danh mục</button>
        </form>
    </div>
</div>

</body>
</html>