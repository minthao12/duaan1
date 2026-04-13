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
    <title>Đơn hàng của tôi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/Duan1/mvc-oop-basic/views/client/giaodien/">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4">Đơn hàng của tôi</h2>

    <?php if (!empty($orders)): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Người nhận</th>
                        <th>SĐT</th>
                        <th>Địa chỉ</th>
                        <th>Tổng tiền</th>
                        <th>Phí ship</th>
                        <th>Trạng thái đơn</th>
                        <th>Trạng thái thanh toán</th>
                        <th>Hình thức</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['receiver_name']) ?></td>
                            <td><?= htmlspecialchars($order['receiver_phone']) ?></td>
                            <td><?= htmlspecialchars($order['receiver_address']) ?></td>
                            <td><?= number_format($order['total']) ?>đ</td>
                            <td><?= number_format($order['shipping_fee']) ?>đ</td>
                            <td><?= hienThiTrangThaiDonHang($order['status']) ?></td>
                            <td><?= hienThiTrangThaiThanhToan($order['payment_status']) ?></td>
                            <td>
                                <?= ($order['payment_method'] === 'cod') ? 'Thanh toán khi nhận hàng' : htmlspecialchars($order['payment_method']) ?>
                            </td>
                            <td>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=orderDetail&id=<?= $order['id'] ?>"
                                class="btn btn-sm btn-primary">
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
    <?php endif; ?>

    <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="btn btn-primary rounded-pill px-4">
        Tiếp tục mua hàng
    </a>
</div>

</body>
</html>