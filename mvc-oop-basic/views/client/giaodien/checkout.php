<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/Duan1/mvc-oop-basic/views/client/giaodien/">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4">Xác nhận thanh toán</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-3">Thông tin nhận hàng</h4>

                    <form method="POST" action="/Duan1/mvc-oop-basic/index.php?act=placeOrder">
                        <?php foreach ($checkoutItems as $item): ?>
                            <input type="hidden" name="selected_cart[]" value="<?= $item['id'] ?>">
                        <?php endforeach; ?>

                        <div class="mb-3">
                            <label class="form-label">Họ tên người nhận</label>
                            <input
                                type="text"
                                name="receiver_name"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['receiver_name'] ?? $user['username'] ?? '') ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input
                                type="text"
                                name="receiver_phone"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['receiver_phone'] ?? $user['std'] ?? '') ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ nhận hàng</label>
                            <input
                                type="text"
                                name="receiver_address"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['receiver_address'] ?? $user['diachi'] ?? '') ?>"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Hình thức thanh toán</label>
                            <input type="text" class="form-control" value="Thanh toán khi nhận hàng (COD)" readonly>
                            <input type="hidden" name="payment_method" value="cod">
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            Xác nhận thanh toán
                        </button>
                        <a href="/Duan1/mvc-oop-basic/index.php?act=cart" class="btn btn-secondary rounded-pill px-4">
                            Quay lại giỏ hàng
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-3">Sản phẩm thanh toán</h4>

                    <?php foreach ($checkoutItems as $item): ?>
                        <div class="d-flex align-items-center border-bottom py-3">
                            <img src="/Duan1/mvc-oop-basic/uploads/<?= htmlspecialchars($item['image']) ?>" width="70" class="rounded me-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?= htmlspecialchars($item['product_name']) ?></h6>
                                <div class="text-muted small">
                                    Màu: <?= htmlspecialchars($item['color_name']) ?> |
                                    Size: <?= htmlspecialchars($item['size_name']) ?>
                                </div>
                                <div class="small">SL: <?= (int)$item['quantity'] ?></div>
                            </div>
                            <div class="fw-bold text-primary">
                                <?= number_format($item['price'] * $item['quantity']) ?>đ
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <strong><?= number_format($subTotal) ?>đ</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí ship:</span>
                            <strong><?= number_format($shippingFee) ?>đ</strong>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-3">
                            <span class="fw-bold">Tổng tiền cần thanh toán:</span>
                            <strong class="text-danger"><?= number_format($grandTotal) ?>đ</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>