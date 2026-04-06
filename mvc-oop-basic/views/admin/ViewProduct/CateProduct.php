<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$variants = $variants ?? [];

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý danh mục sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow border-0 rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Quản lý danh mục sản phẩm</h3>
            <div>
                <a href="?act=admin" class="btn btn-outline-secondary">Dashboard</a>
                <a href="?act=addCateProduct" class="btn btn-success">+ Thêm danh mục</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Màu</th>
                        <th>Size</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Ảnh</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($variants)): ?>
                        <?php foreach ($variants as $item): ?>
                            <tr>
                                <td><?= (int)($item['id'] ?? 0) ?></td>
                                <td><?= e($item['product_name'] ?? '') ?></td>
                                <td><?= e($item['color_name'] ?? '') ?></td>
                                <td><?= e($item['size_name'] ?? '') ?></td>
                                <td><?= number_format((int)($item['price'] ?? 0)) ?>đ</td>
                                <td><?= (int)($item['quantity'] ?? 0) ?></td>
                                <td>
                                    <img src="/Duan1/mvc-oop-basic/uploads/<?= e($item['image'] ?? 'default.png') ?>"
                                         width="70" class="rounded">
                                </td>
                                <td>
                                    <a href="?act=editCateProduct&id=<?= (int)($item['id'] ?? 0) ?>" class="btn btn-warning btn-sm">Sửa</a>
                                    <a href="?act=deleteCateProduct&id=<?= (int)($item['id'] ?? 0) ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-danger btn-sm">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Chưa có dữ liệu biến thể sản phẩm.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>