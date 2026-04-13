<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê doanh thu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Thống kê doanh thu</h2>
        <a href="index.php?act=adminProduct" class="btn btn-outline-secondary">← Quay lại</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow rounded-4 p-3">
                <h5>Tổng đơn hàng</h5>
                <h3><?= (int)$thongke['total_orders'] ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow rounded-4 p-3">
                <h5>Đơn hoàn thành</h5>
                <h3><?= (int)$thongke['completed_orders'] ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow rounded-4 p-3">
                <h5>Chưa thanh toán</h5>
                <h3><?= (int)$thongke['unpaid_orders'] ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow rounded-4 p-3">
                <h5>Doanh thu</h5>
                <h3 class="text-danger"><?= number_format($thongke['revenue']) ?>đ</h3>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow rounded-4 p-4">
        <h4 class="mb-3">Doanh thu theo ngày</h4>

        <?php if (!empty($thongke['daily_revenue'])): ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ngày</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($thongke['daily_revenue'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['order_date']) ?></td>
                                <td><?= number_format($item['daily_revenue']) ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">Chưa có dữ liệu doanh thu.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>