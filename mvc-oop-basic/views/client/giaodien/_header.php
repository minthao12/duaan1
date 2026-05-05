<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php
$pageTitle = $pageTitle ?? 'HDTT Store';
$categoryMap = $categoryMap ?? [
    1 => ['name' => 'Áo',   'icon' => 'fa-tshirt'],
    2 => ['name' => 'Quần', 'icon' => 'fa-user-tie'],
    3 => ['name' => 'Giày', 'icon' => 'fa-shoe-prints'],
];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($pageTitle) ?> - HDTT Store</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <base href="/Duan1/mvc-oop-basic/views/client/giaodien/">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Topbar Start -->
    <div class="container-fluid px-5 d-none border-bottom d-lg-block">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-4 text-center text-lg-start mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a href="#" class="text-muted me-2"> Trợ giúp</a><small> / </small>
                    <a href="#" class="text-muted mx-2"> Hỗ trợ</a><small> / </small>
                    <a href="#" class="text-muted ms-2"> Liên hệ</a>
                </div>
            </div>

            <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
                <small class="text-dark">Hotline:</small>
                <span class="text-muted ms-2">0967807956</span>
            </div>

            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle text-muted ms-2" data-bs-toggle="dropdown">
                            <small>
                                <i class="fa fa-user me-2"></i>
                                <?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']) : 'Tài khoản' ?>
                            </small>
                        </a>

                        <div class="dropdown-menu rounded">
                            <?php if (!isset($_SESSION['user'])): ?>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=loginUser" class="dropdown-item">
                                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                                </a>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=registerUser" class="dropdown-item">
                                    <i class="fas fa-user-plus me-2"></i>Đăng ký
                                </a>
                            <?php else: ?>
                                <span class="dropdown-item-text fw-bold text-primary">
                                    Xin chào, <?= htmlspecialchars($_SESSION['user']) ?>
                                </span>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=profile" class="dropdown-item">
                                    <i class="fas fa-user-circle me-2"></i>Thông tin cá nhân
                                </a>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=myOrders" class="dropdown-item">
                                    <i class="fas fa-box me-2"></i>Đơn hàng của tôi
                                </a>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=wishlist" class="dropdown-item">
                                    <i class="fas fa-heart me-2"></i>Sản phẩm yêu thích
                                </a>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=cart" class="dropdown-item">
                                    <i class="fas fa-shopping-cart me-2"></i>Giỏ hàng
                                </a>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=logout" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <div class="container-fluid px-5 py-4 d-none d-lg-block">
        <div class="row gx-0 align-items-center text-center">
            <div class="col-md-4 col-lg-3 text-center text-lg-start">
                <div class="d-inline-flex align-items-center">
                    <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="navbar-brand p-0">
                        <h1 class="display-5 text-primary m-0"><i
                                class="fas fa-shopping-bag text-secondary me-2"></i>HDTT Store</h1>
                    </a>
                </div>
            </div>

            <div class="col-md-4 col-lg-6 text-center">
                <div class="position-relative ps-4">
                    <form method="GET" action="/Duan1/mvc-oop-basic/index.php">
                        <input type="hidden" name="act" value="giaodien">

                        <div class="d-flex border rounded-pill">
                            <input class="form-control border-0 rounded-pill w-100 py-3"
                                type="text"
                                name="keyword"
                                placeholder="Tìm kiếm sản phẩm..."
                                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">

                            <select name="category" class="form-select text-dark border-0 border-start rounded-0 p-3" style="width: 200px;">
                                <option value="">Tất cả danh mục</option>
                                <?php foreach ($categoryMap as $catId => $cat): ?>
                                    <option value="<?= $catId ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" class="btn btn-primary rounded-pill py-3 px-5" style="border: 0;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 text-center text-lg-end">
                <div class="d-inline-flex align-items-center">
                    <a href="/Duan1/mvc-oop-basic/index.php?act=myOrders" class="text-muted d-flex align-items-center justify-content-center me-3" title="Đơn hàng">
                        <span class="rounded-circle btn-md-square border"><i class="fas fa-box"></i></span>
                    </a>
                    <a href="/Duan1/mvc-oop-basic/index.php?act=wishlist" class="text-muted d-flex align-items-center justify-content-center me-3" title="Sản phẩm yêu thích">
                        <span class="rounded-circle btn-md-square border"><i class="fas fa-heart"></i></span>
                    </a>
                    <a href="/Duan1/mvc-oop-basic/index.php?act=cart" class="text-muted d-flex align-items-center justify-content-center">
                        <span class="rounded-circle btn-md-square border">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        <span class="text-dark ms-2">Giỏ hàng</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar Start -->
    <div class="container-fluid nav-bar p-0">
        <div class="row gx-0 bg-primary px-5 align-items-center">
            <div class="col-lg-3 d-none d-lg-block">
                <nav class="navbar navbar-light position-relative" style="width: 250px;">
                    <button class="navbar-toggler border-0 fs-4 w-100 px-0 text-start" type="button"
                        data-bs-toggle="collapse" data-bs-target="#allCat">
                        <h4 class="m-0"><i class="fa fa-bars me-2"></i>Danh mục sản phẩm</h4>
                    </button>
                    <div class="collapse navbar-collapse rounded-bottom" id="allCat">
                        <div class="navbar-nav ms-auto py-0">
                            <ul class="list-unstyled categories-bars">
                                <li>
                                    <div class="categories-bars-item">
                                        <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien">
                                            <i class="fas fa-th me-2"></i>Tất cả sản phẩm
                                        </a>
                                    </div>
                                </li>
                                <?php foreach ($categoryMap as $catId => $cat): ?>
                                    <li>
                                        <div class="categories-bars-item">
                                            <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien&category=<?= $catId ?>">
                                                <i class="fas <?= $cat['icon'] ?> me-2"></i><?= htmlspecialchars($cat['name']) ?>
                                            </a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-12 col-lg-9">
                <nav class="navbar navbar-expand-lg navbar-light bg-primary ">
                    <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="navbar-brand d-block d-lg-none">
                        <h1 class="display-5 text-secondary m-0"><i
                                class="fas fa-shopping-bag text-white me-2"></i>HDTT Store</h1>
                    </a>

                    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars fa-1x"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav ms-auto py-0">
                            <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="nav-item nav-link <?= ($activeNav ?? '') === 'home' ? 'active' : '' ?>">Trang chủ</a>
                            <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="nav-item nav-link <?= ($activeNav ?? '') === 'shop' ? 'active' : '' ?>">Sản phẩm</a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=myOrders" class="nav-item nav-link <?= ($activeNav ?? '') === 'orders' ? 'active' : '' ?>">Đơn hàng</a>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=cart" class="nav-item nav-link <?= ($activeNav ?? '') === 'cart' ? 'active' : '' ?>">Giỏ hàng</a>
                            <?php else: ?>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=loginUser" class="nav-item nav-link">Đăng nhập</a>
                                <a href="/Duan1/mvc-oop-basic/index.php?act=registerUser" class="nav-item nav-link">Đăng ký</a>
                            <?php endif; ?>
                        </div>

                        <a href="tel:0967807956" class="btn btn-secondary rounded-pill py-2 px-4 px-lg-3 mb-3 mb-md-3 mb-lg-0"><i
                                class="fa fa-mobile-alt me-2"></i> 0967807956</a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->
