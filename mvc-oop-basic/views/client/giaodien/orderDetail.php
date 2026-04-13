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
        'paid'   => 'Đã thanh toán',
        default  => 'Không xác định',
    };
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/Duan1/mvc-oop-basic/views/client/giaodien/">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4">Chi tiết đơn hàng #<?= $order['id'] ?></h2>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['receiver_name']) ?></p>
            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['receiver_phone']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['receiver_address']) ?></p>
            <p><strong>Trạng thái đơn:</strong> <?= hienThiTrangThaiDonHang($order['status']) ?></p>
            <p><strong>Trạng thái thanh toán:</strong> <?= hienThiTrangThaiThanhToan($order['payment_status']) ?></p>
            <p><strong>Phương thức:</strong> <?= $order['payment_method'] === 'cod' ? 'Thanh toán khi nhận hàng' : htmlspecialchars($order['payment_method']) ?></p>
            <p><strong>Phí ship:</strong> <?= number_format($order['shipping_fee']) ?>đ</p>
            <p class="mb-0"><strong>Tổng tiền:</strong> <span class="text-danger fw-bold"><?= number_format($order['total']) ?>đ</span></p>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h4 class="mb-3">Sản phẩm trong đơn</h4>

            <?php if (!empty($orderDetails)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ảnh</th>
                                <th>Sản phẩm</th>
                                <th>Màu</th>
                                <th>Size</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderDetails as $item): ?>
                                <tr>
                                    <td>
                                        <img src="/Duan1/mvc-oop-basic/uploads/<?= htmlspecialchars($item['image']) ?>"
                                             width="80" class="rounded">
                                    </td>
                                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td><?= htmlspecialchars($item['color_name']) ?></td>
                                    <td><?= htmlspecialchars($item['size_name']) ?></td>
                                    <td><?= number_format($item['price']) ?>đ</td>
                                    <td><?= (int)$item['quantity'] ?></td>
                                    <td><?= number_format($item['price'] * $item['quantity']) ?>đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Không có sản phẩm trong đơn hàng.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="/Duan1/mvc-oop-basic/index.php?act=myOrders" class="btn btn-secondary rounded-pill px-4">
            Quay lại đơn hàng của tôi
        </a>
    </div>
</div>

</body>
</html>