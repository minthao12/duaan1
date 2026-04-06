<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentAct = $_GET['act'] ?? '/';
$products = $products ?? [];
$keyword = trim($_GET['keyword'] ?? '');

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function isActive($act, $list)
{
    return in_array($act, $list, true) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>HDTT Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body{background:#f5f7fb}
        .sidebar{min-height:100vh;background:#1e293b}
        .sidebar a{
            color:#cbd5e1;
            padding:12px 20px;
            display:block;
            text-decoration:none;
            border-radius:10px;
            margin:6px 10px;
            transition:.25s
        }
        .sidebar a:hover,.sidebar a.active{background:#2563eb;color:#fff}
        .header{background:#fff;border-radius:16px}
        .card-stat{border:none;border-radius:18px;color:#fff}
        .card-stat-1{background:linear-gradient(45deg,#4facfe,#00f2fe)}
        .card-stat-2{background:linear-gradient(45deg,#43e97b,#38f9d7)}
        .card-stat-3{background:linear-gradient(45deg,#fa709a,#fee140)}
        .card-stat-4{background:linear-gradient(45deg,#667eea,#764ba2)}
        .table-box{
            background:#fff;
            border-radius:18px;
            box-shadow:0 10px 25px rgba(0,0,0,.05);
            padding:18px
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h4 class="text-white text-center mb-4">HDTT</h4>

            <a href="?act=admin" class="<?= isActive($currentAct, ['/', 'admin']) ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="?act=adminProduct" class="<?= isActive($currentAct, ['adminProduct', 'addProduct', 'editProduct', 'detailAdmin']) ?>">
                <i class="bi bi-box"></i> Sản phẩm
            </a>

            <a href="?act=CateProduct" class="<?= isActive($currentAct, ['CateProduct', 'addCateProduct', 'editCateProduct']) ?>">
                <i class="bi bi-bag"></i> Danh mục sản phẩm
            </a>

            <a href="?act=users" class="<?= isActive($currentAct, ['users', 'editUser']) ?>">
                <i class="bi bi-people"></i> Người dùng
            </a>
        </div>

        <div class="col-md-10 p-3">
            <div class="header d-flex justify-content-between align-items-center p-3 shadow-sm mb-4">
                <h5 class="mb-0">Dashboard</h5>

                <div class="d-flex align-items-center gap-3">
                    <form method="GET" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="act" value="admin">
                        <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tìm kiếm..." value="<?= e($keyword) ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Tìm</button>
                    </form>

                    <?php if (isset($_SESSION['user'])): ?>
                        <span><?= e($_SESSION['user']) ?></span>
                        <a href="?act=logout" class="btn btn-danger btn-sm">Đăng xuất</a>
                    <?php else: ?>
                        <a href="?act=login" class="btn btn-primary btn-sm">Đăng nhập</a>
                        <a href="?act=register" class="btn btn-success btn-sm">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card card-stat card-stat-1 p-3">
                        <h4><?= count($products) ?></h4>
                        <p class="mb-0">Sản phẩm</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat card-stat-2 p-3">
                        <h4>50</h4>
                        <p class="mb-0">Đơn hàng</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat card-stat-3 p-3">
                        <h4>20</h4>
                        <p class="mb-0">Khách hàng</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat card-stat-4 p-3">
                        <h4>10tr</h4>
                        <p class="mb-0">Doanh thu</p>
                    </div>
                </div>
            </div>

            <?php if ($keyword !== ''): ?>
                <p>Bạn đang tìm: <b><?= e($keyword) ?></b></p>
            <?php endif; ?>

            <div class="table-box">
                <h5 class="mb-3">Danh sách sản phẩm</h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Mô tả</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $item): ?>
                                    <tr>
                                        <td><?= (int)($item['id'] ?? 0) ?></td>
                                        <td class="fw-bold"><?= e($item['name'] ?? $item['product_name'] ?? '') ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?= e($item['category_name'] ?? 'Chưa phân loại') ?>
                                            </span>
                                        </td>
                                        <td style="max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"
                                            title="<?= e($item['description'] ?? '') ?>">
                                            <?= e($item['description'] ?? '') ?>
                                        </td>
                                        <td>
                                            <a href="?act=detailAdmin&id=<?= (int)($item['id'] ?? 0) ?>" class="btn btn-info btn-sm">Chi tiết</a>
                                            <a href="?act=editProduct&id=<?= (int)($item['id'] ?? 0) ?>" class="btn btn-warning btn-sm">Sửa</a>
                                            <a href="?act=deleteProduct&id=<?= (int)($item['id'] ?? 0) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Chưa có dữ liệu sản phẩm.</td>
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