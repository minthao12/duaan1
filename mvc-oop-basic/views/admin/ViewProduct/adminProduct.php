<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentAct = $_GET['act'] ?? 'adminProduct';
$products = $products ?? [];
$keyword = trim($_GET['keyword'] ?? '');

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body{background:#f5f7fb}
        .sidebar{min-height:100vh;background:#1e293b}
        .sidebar a{color:#cbd5e1;padding:12px 20px;display:block;text-decoration:none;border-radius:8px;margin:5px 10px}
        .sidebar a:hover,.sidebar a.active{background:#3b82f6;color:#fff}
        .header{background:#fff;border-radius:12px}
        .table-box{background:#fff;border-radius:16px;padding:18px;box-shadow:0 10px 25px rgba(0,0,0,.05)}
        .product-img{width:70px;height:70px;object-fit:cover;border-radius:10px}
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h4 class="text-white text-center mb-4">HDTT</h4>

            <a href="?act=admin"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="?act=adminProduct" class="active"><i class="bi bi-box"></i> Sản phẩm</a>
            <a href="?act=CateProduct"><i class="bi bi-bag"></i> Danh mục sản phẩm</a>
            <a href="?act=users"><i class="bi bi-people"></i> Người dùng</a>
        </div>

        <div class="col-md-10 p-3">
            <div class="header d-flex justify-content-between align-items-center p-3 shadow-sm mb-4">
                <h5 class="mb-0">Quản lý sản phẩm</h5>

                <div class="d-flex align-items-center gap-2">
                    <form method="GET" class="d-flex gap-2">
                        <input type="hidden" name="act" value="adminProduct">
                        <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tìm kiếm..." value="<?= e($keyword) ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Tìm</button>
                    </form>

                    <a href="?act=addProduct" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle"></i> Thêm sản phẩm
                    </a>
                </div>
            </div>

            <?php if ($keyword !== ''): ?>
                <div class="mb-3">Bạn đang tìm: <b><?= e($keyword) ?></b></div>
            <?php endif; ?>

            <div class="table-box">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Mã</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Trạng thái</th>
                                <th width="210">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $item): ?>
                                    <tr>
                                        <td><?= (int)($item['id'] ?? 0) ?></td>
                                        <td>
                                            <img src="/Duan1/mvc-oop-basic/uploads/<?= e($item['image'] ?? 'default.png') ?>" class="product-img">
                                        </td>
                                        <td><?= e($item['name'] ?? $item['product_name'] ?? '') ?></td>
                                        <td><?= e($item['code'] ?? '') ?></td>
                                        <td><?= number_format((int)($item['price'] ?? 0)) ?>đ</td>
                                        <td><?= (int)($item['quantity'] ?? 0) ?></td>
                                        <td>
                                            <?php if (!empty($item['status'])): ?>
                                                <span class="badge bg-success">Còn bán</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Hết hàng</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="?act=detailAdmin&id=<?= (int)($item['id'] ?? 0) ?>" class="btn btn-info btn-sm">Chi tiết</a>
                                            <a href="?act=editProduct&id=<?= (int)($item['id'] ?? 0) ?>" class="btn btn-warning btn-sm">Sửa</a>
                                            <a href="?act=deleteProduct&id=<?= (int)($item['id'] ?? 0) ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-danger btn-sm">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Chưa có sản phẩm nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>