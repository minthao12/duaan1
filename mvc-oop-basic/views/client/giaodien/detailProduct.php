<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <base href="/Duan1/mvc-oop-basic/views/client/giaodien/">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid px-5 d-none border-bottom d-lg-block">
    <div class="row gx-0 align-items-center">
        <div class="col-lg-4 text-center text-lg-start mb-lg-0">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                <a href="#" class="text-muted me-2"> Help</a><small> / </small>
                <a href="#" class="text-muted mx-2"> Support</a><small> / </small>
                <a href="#" class="text-muted ms-2"> Contact</a>
            </div>
        </div>

        <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
            <small class="text-dark">Số điện thoại:</small>
            <span class="text-muted ms-2">0967807956</span>
        </div>

        <div class="col-lg-4 text-center text-lg-end">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-muted ms-2" data-bs-toggle="dropdown">
                        <small>
                            <i class="fa fa-user me-2"></i>
                            <?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']) : 'Đăng ký' ?>
                        </small>
                    </a>

                    <div class="dropdown-menu rounded">
                        <?php if (!isset($_SESSION['user'])): ?>
                            <a href="/Duan1/mvc-oop-basic/index.php?act=loginUser" class="dropdown-item">Đăng nhập</a>
                            <a href="/Duan1/mvc-oop-basic/index.php?act=registerUser" class="dropdown-item">Đăng ký</a>
                        <?php else: ?>
                            <span class="dropdown-item-text fw-bold text-primary">
                                Xin chào, <?= htmlspecialchars($_SESSION['user']) ?>
                            </span>
                            <a href="/Duan1/mvc-oop-basic/index.php?act=logout" class="dropdown-item">Đăng xuất</a>
                        <?php endif; ?>
                        <a href="/Duan1/mvc-oop-basic/index.php?act=cart" class="dropdown-item">Giỏ hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid nav-bar p-0 mb-5">
    <div class="row gx-0 bg-primary px-5 align-items-center">
        <div class="col-12 col-lg-9 ms-auto">
            <nav class="navbar navbar-expand-lg navbar-light bg-primary">
                <div class="collapse navbar-collapse show">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="nav-item nav-link active">Home</a>
                        <a href="/Duan1/mvc-oop-basic/index.php?act=cart" class="nav-item nav-link">Giỏ hàng</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5 align-items-start">
        <div class="col-lg-6">
            <div class="border rounded p-3 bg-light">
                <img src="/Duan1/mvc-oop-basic/uploads/<?= htmlspecialchars($firstVariant['image']) ?>"
                     class="img-fluid w-100 rounded"
                     alt="<?= htmlspecialchars($firstVariant['product_name']) ?>"
                     style="max-height: 500px; object-fit: cover;">
            </div>
        </div>

        <div class="col-lg-6">
            <h2 class="mb-3"><?= htmlspecialchars($firstVariant['product_name']) ?></h2>

            <h4 class="text-primary mb-3"><?= number_format($firstVariant['price']) ?>đ</h4>
            <?php
                $hasStock = false;
                foreach ($variants as $v) {
                    if ((int)$v['stock'] > 0) {
                        $hasStock = true;
                        break;
                    }
                }
            ?>

            <p class="mb-4"><?= htmlspecialchars($firstVariant['description']) ?></p>

            <form method="POST" action="/Duan1/mvc-oop-basic/index.php?act=addToCart">
                <input type="hidden" name="product_id" value="<?= (int)$firstVariant['product_id'] ?>">

                <div class="mb-3">
                    <label class="form-label fw-bold">Màu sắc</label>
                    <select name="color_id" class="form-select" required>
                        <option value="">-- Chọn màu --</option>
                        <?php foreach ($colors as $colorId => $colorName): ?>
                            <option value="<?= $colorId ?>"><?= htmlspecialchars($colorName) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Size</label>
                    <select name="size_id" class="form-select" required>
                        <option value="">-- Chọn size --</option>
                        <?php foreach ($sizes as $sizeId => $sizeName): ?>
                            <option value="<?= $sizeId ?>"><?= htmlspecialchars($sizeName) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                </div>

                <div class="d-flex gap-3">
                    <?php if ($hasStock): ?>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                            <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng
                        </button>

                        <button type="submit" formaction="/Duan1/mvc-oop-basic/index.php?act=addToCart"
                                class="btn btn-secondary rounded-pill px-4 py-2">
                            <i class="fas fa-bolt me-2"></i>Mua ngay
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-danger rounded-pill px-4 py-2" disabled>
                            Hết hàng
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
    <div class="container py-5">
        <div class="row g-4 rounded mb-5" style="background: rgba(255, 255, 255, .03);">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="rounded p-4">
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4"
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="text-white">Địa chỉ</h4>
                        <p class="mb-0">Hà Nội, Việt Nam</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="rounded p-4">
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4"
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-envelope fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="text-white">Email</h4>
                        <p class="mb-0">hdttstore@gmail.com</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="rounded p-4">
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4"
                         style="width: 70px; height: 70px;">
                        <i class="fa fa-phone-alt fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="text-white">Số điện thoại</h4>
                        <p class="mb-2">0967807956</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>