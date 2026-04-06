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
    <title>Chi tiết sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow border-0 rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Chi tiết sản phẩm</h3>
            <a href="?act=adminProduct" class="btn btn-outline-secondary">← Quay lại</a>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <img src="/Duan1/mvc-oop-basic/uploads/<?= e($product['image'] ?? 'default.png') ?>"
                     class="img-fluid rounded border"
                     alt="<?= e($product['name'] ?? '') ?>">
            </div>

            <div class="col-md-8">
                <p><strong>ID:</strong> <?= (int)($product['id'] ?? 0) ?></p>
                <p><strong>Tên:</strong> <?= e($product['name'] ?? '') ?></p>
                <p><strong>Mã:</strong> <?= e($product['code'] ?? '') ?></p>
                <p><strong>Giá:</strong> <?= number_format((int)($product['price'] ?? 0)) ?>đ</p>
                <p><strong>Số lượng:</strong> <?= (int)($product['quantity'] ?? 0) ?></p>
                <p>
                    <strong>Trạng thái:</strong>
                    <?php if (!empty($product['status'])): ?>
                        <span class="badge bg-success">Còn bán</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Hết hàng</span>
                    <?php endif; ?>
                </p>
                <p><strong>Mô tả:</strong> <?= e($product['description'] ?? '') ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>