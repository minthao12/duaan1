<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <base href="/Duan1/mvc-oop-basic/views/client/giaodien/">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>

    <?php if (!empty($cartItems)): ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
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
                                <img src="/Duan1/mvc-oop-basic/uploads/<?= htmlspecialchars($item['image']) ?>"
                                     width="80" class="rounded">
                            </td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= htmlspecialchars($item['color_name']) ?></td>
                            <td><?= htmlspecialchars($item['size_name']) ?></td>
                            <td><?= number_format($item['price']) ?>đ</td>
                            <td>
                                <form method="POST" action="/Duan1/mvc-oop-basic/index.php?act=updateCart" class="d-flex gap-2">
                                    <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width: 90px;">
                                    <button class="btn btn-sm btn-primary">Cập nhật</button>
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

        <div class="text-end mt-4">
            <h4>Tổng tiền: <span class="text-primary"><?= number_format($total) ?>đ</span></h4>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Giỏ hàng đang trống.</div>
    <?php endif; ?>

    <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="btn btn-secondary rounded-pill px-4 mt-3">
        Tiếp tục mua hàng
    </a>
</div>

</body>
</html>