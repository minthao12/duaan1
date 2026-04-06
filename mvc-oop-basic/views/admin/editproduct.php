<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$product = $product ?? [];

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow border-0 rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Sửa sản phẩm</h3>
            <a href="?act=adminProduct" class="btn btn-outline-secondary">← Quay lại</a>
        </div>

        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" value="<?= e($product['name'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mã sản phẩm</label>
                    <input type="text" name="code" class="form-control" value="<?= e($product['code'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá</label>
                    <input type="number" name="price" class="form-control" value="<?= e($product['price'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" value="<?= e($product['quantity'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="1" <?= (isset($product['status']) && $product['status'] == 1) ? 'selected' : '' ?>>Còn bán</option>
                        <option value="0" <?= (isset($product['status']) && $product['status'] == 0) ? 'selected' : '' ?>>Hết hàng</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="5"><?= e($product['description'] ?? '') ?></textarea>
                </div>
            </div>

            <button class="btn btn-warning mt-4">Cập nhật sản phẩm</button>
        </form>
    </div>
</div>

</body>
</html>