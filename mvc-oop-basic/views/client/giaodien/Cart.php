<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php
function hienThiTrangThaiDonHang($status) {
    return match ($status) {
        'cho_xac_nhan'   => 'Chờ xác nhận',
        'dang_lay_hang'  => 'Đang lấy hàng',
        'dang_van_chuyen'=> 'Đang vận chuyển',
        'da_van_chuyen'  => 'Đã vận chuyển',
        'hoan_thanh'     => 'Hoàn thành',
        'da_huy'         => 'Đã hủy',
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
    <title>Giỏ hàng và đơn hàng của bạn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <base href="/Duan1/mvc-oop-basic/views/client/giaodien/">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .section-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(45deg, #3b82f6, #6366f1);
            color: white;
            padding: 18px 24px;
        }

        .badge-status {
            padding: 7px 12px;
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

<div class="container py-5">
    <div class="card section-card mb-5">
        <div class="section-header">
            <h3 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn</h3>
        </div>
        <div class="card-body p-4">

            <?php if (!empty($cartItems)): ?>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>Ảnh</th>
                    <th>Sản phẩm</th>
                    <th>Màu</th>
                    <th>Size</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tạm tính</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($cartItems as $item): ?>
                    <?php $subtotal = $item['price'] * $item['quantity']; ?>
                    <?php $total += $subtotal; ?>
                    <tr>
                        <td>
                            <input type="checkbox" form="checkoutForm" name="selected_cart[]" value="<?= $item['id'] ?>" class="item-check">
                        </td>
                        <td>
                            <img src="/Duan1/mvc-oop-basic/uploads/<?= htmlspecialchars($item['image']) ?>" width="80" class="rounded">
                        </td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= htmlspecialchars($item['color_name']) ?></td>
                        <td><?= htmlspecialchars($item['size_name']) ?></td>
                        <td><?= number_format($item['price']) ?>đ</td>
                        <td>
                            <form method="POST" action="/Duan1/mvc-oop-basic/index.php?act=updateCart" class="d-flex gap-2 align-items-start">
                                <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">

                                <div>
                                    <input type="number"
                                        name="quantity"
                                        value="<?= $item['quantity'] ?>"
                                        min="1"
                                        max="<?= (int)$item['stock'] ?>"
                                        class="form-control"
                                        style="width: 90px;">
                                    <small class="text-muted">Tồn kho: <?= (int)$item['stock'] ?></small>
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                            </form>
                        </td>
                        <td><?= number_format($subtotal) ?>đ</td>
                        <td>
                            <a href="/Duan1/mvc-oop-basic/index.php?act=deleteCart&id=<?= $item['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <form method="POST" action="/Duan1/mvc-oop-basic/index.php?act=checkout" id="checkoutForm">
        <div class="text-end mt-4">
            <h4>Tổng tiền giỏ hàng: <span class="text-primary"><?= number_format($total) ?>đ</span></h4>

            <button type="submit" class="btn btn-success rounded-pill px-4 mt-3">
                <i class="fas fa-credit-card me-2"></i>Thanh toán
            </button>
        </div>
    </form>
    
<?php else: ?>
    <div class="alert alert-warning mb-0">Giỏ hàng đang trống.</div>
<?php endif; ?>

    <div class="mt-3 text-end">
        <a href="/Duan1/mvc-oop-basic/index.php?act=myOrders" class="btn btn-outline-primary rounded-pill px-4">
            Lịch sử đơn hàng
        </a>
    </div>

        </div>
    </div>

    

    <div class="mt-4">
        <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="btn btn-secondary rounded-pill px-4">
            Tiếp tục mua hàng
        </a>
    </div>
</div>

<script>
document.getElementById('checkAll')?.addEventListener('change', function () {
    document.querySelectorAll('.item-check').forEach(item => {
        item.checked = this.checked;
    });
});
</script>

</body>
</html>