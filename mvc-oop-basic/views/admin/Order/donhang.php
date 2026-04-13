<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php
function hienThiTrangThaiDonHang($status) {
    return match ($status) {
        'cho_xac_nhan'    => 'Chờ xác nhận',
        'dang_lay_hang'   => 'Đang lấy hàng',
        'dang_van_chuyen' => 'Đang vận chuyển',
        'da_van_chuyen'   => 'Đã vận chuyển',
        'hoan_thanh'      => 'Hoàn thành',
        'da_huy'          => 'Đã hủy',
        default => 'Không xác định',
    };
}

function hienThiTrangThaiThanhToan($paymentStatus) {
    return match ($paymentStatus) {
        'unpaid' => 'Chưa thanh toán',
        'paid' => 'Đã thanh toán',
        default => 'Không xác định',
    };
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
        }

        .sidebar {
            height: 100vh;
            background: #1e293b;
        }

        .sidebar a {
            color: #cbd5e1;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px 10px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #334155;
            color: #fff;
        }

        .sidebar a.active {
            background: #3b82f6;
            color: #fff;
        }

        .header {
            background: white;
            border-radius: 12px;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .badge-status {
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #842029;
        }

        .payment-unpaid {
            background: #ffe5b4;
            color: #9a6700;
        }

        .payment-paid {
            background: #d1e7dd;
            color: #0f5132;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php $currentAct = $_GET['act'] ?? '/'; ?>

        <div class="col-md-2 sidebar p-3">
            <h4 class="text-white text-center mb-4">HDTT</h4>

            <a href="?act=adminProduct" class="<?= ($currentAct == 'adminProduct') ? 'active' : '' ?>">
                <i class="bi bi-box"></i> Danh mục Sản phẩm
            </a>

            <a href="?act=CateProduct" class="<?= ($currentAct == 'CateProduct') ? 'active' : '' ?>">
                <i class="bi bi-bag"></i> sản phẩm
            </a>

            <a href="?act=users" class="<?= ($currentAct == 'users') ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Người dùng
            </a>

            <a href="?act=donhang" class="<?= ($currentAct == 'donhang') ? 'active' : '' ?>">
                <i class="bi bi-receipt"></i> Đơn hàng
            </a>
            <a href="?act=thongke" class="<?= ($currentAct == 'thongke') ? 'active' : '' ?>">
                <i class="bi bi-bar-chart"></i> Thống kê
            </a>
        </div>

        <div class="col-md-10 p-3">
            <div class="header d-flex justify-content-between align-items-center p-3 shadow-sm mb-4">
                <h5 class="mb-0">Quản lý đơn hàng</h5>
                <div>
                    <span class="me-3 fw-semibold"><?= htmlspecialchars($_SESSION['user'] ?? '') ?></span>
                    <a href="?act=logout" class="btn btn-danger btn-sm">Đăng xuất</a>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <?php if (!empty($orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th>Người nhận</th>
                                        <th>SĐT</th>
                                        <th>Địa chỉ</th>
                                        <th>Tổng tiền</th>
                                        <th>Phí ship</th>
                                        <th>PTTT</th>
                                        <th>Trạng thái hiện tại</th>
                                        <th>Thanh toán hiện tại</th>
                                        <th>Cập nhật</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?= $order['id'] ?></td>
                                            <td><?= htmlspecialchars($order['username'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($order['receiver_name']) ?></td>
                                            <td><?= htmlspecialchars($order['receiver_phone']) ?></td>
                                            <td><?= htmlspecialchars($order['receiver_address']) ?></td>
                                            <td><?= number_format($order['total']) ?>đ</td>
                                            <td><?= number_format($order['shipping_fee']) ?>đ</td>
                                            <td>
                                                <?= ($order['payment_method'] === 'cod') ? 'Thanh toán khi nhận hàng' : htmlspecialchars($order['payment_method']) ?>
                                            </td>
                                            <td>
                                                <span class="badge-status 
                                                    <?= $order['status'] === 'pending' ? 'status-pending' : '' ?>
                                                    <?= $order['status'] === 'completed' ? 'status-completed' : '' ?>
                                                    <?= $order['status'] === 'cancelled' ? 'status-cancelled' : '' ?>">
                                                    <?= hienThiTrangThaiDonHang($order['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge-status 
                                                    <?= $order['payment_status'] === 'unpaid' ? 'payment-unpaid' : '' ?>
                                                    <?= $order['payment_status'] === 'paid' ? 'payment-paid' : '' ?>">
                                                    <?= hienThiTrangThaiThanhToan($order['payment_status']) ?>
                                                </span>
                                            </td>
                                            <td style="min-width: 220px;">
                                                <form method="POST" action="index.php?act=updateOrderStatus" class="d-flex gap-2">
                                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

                                                    <select name="status" class="form-select form-select-sm">
                                                        <option value="cho_xac_nhan" <?= $order['status'] === 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                                        <option value="dang_lay_hang" <?= $order['status'] === 'dang_lay_hang' ? 'selected' : '' ?>>Đang lấy hàng</option>
                                                        <option value="dang_van_chuyen" <?= $order['status'] === 'dang_van_chuyen' ? 'selected' : '' ?>>Đang vận chuyển</option>
                                                        <option value="da_van_chuyen" <?= $order['status'] === 'da_van_chuyen' ? 'selected' : '' ?>>Đã vận chuyển</option>
                                                        <option value="hoan_thanh" <?= $order['status'] === 'hoan_thanh' ? 'selected' : '' ?>>Hoàn thành</option>
                                                        <option value="da_huy" <?= $order['status'] === 'da_huy' ? 'selected' : '' ?>>Đã hủy</option>
                                                    </select>

                                                    <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="index.php?act=detailOrder&id=<?= $order['id'] ?>" class="btn btn-info btn-sm">
                                                    Xem chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">Chưa có đơn hàng nào.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>