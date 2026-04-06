<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = '/Duan1/mvc-oop-basic';
$cartItems = $cartItems ?? [];

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <base href="<?= $baseUrl ?>/views/client/giaodien/">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Giỏ hàng của bạn</h2>
        <a href="<?= $baseUrl ?>/index.php?act=giaodien" class="btn btn-outline-primary">← Tiếp tục mua sắm</a>
    </div>

    <?php if (!empty($cartItems)): ?>
        <div class="table-responsive">
            <table class="table align-middle table-bordered">
                <thead class="table-light">
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
                        <?php
                            $subtotal = (int)$item['price'] * (int)$item['quantity'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <img src="<?= $baseUrl ?>/uploads/<?= e($item['image']) ?>" width="80" class="rounded">
                            </td>
                            <td><?= e($item['product_name']) ?></td>
                            <td><?= e($item['color_name']) ?></td>
                            <td><?= e($item['size_name']) ?></td>
                            <td><?= number_format((int)$item['price']) ?>đ</td>
                            <td>
                                <form method="POST" action="<?= $baseUrl ?>/index.php?act=updateCart" class="d-flex gap-2">
                                    <input type="hidden" name="cart_id" value="<?= (int)$item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= (int)$item['quantity'] ?>" min="1" class="form-control" style="width: 90px;">
                                    <button class="btn btn-sm btn-primary">Cập nhật</button>
                                </form>
                            </td>
                            <td><?= number_format($subtotal) ?>đ</td>
                            <td>
                                <a href="<?= $baseUrl ?>/index.php?act=deleteCart&id=<?= (int)$item['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Tổng cộng</th>
                        <th colspan="2" class="text-primary"><?= number_format($total) ?>đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Giỏ hàng của bạn đang trống.</div>
    <?php endif; ?>
</div>

</body>
</html>