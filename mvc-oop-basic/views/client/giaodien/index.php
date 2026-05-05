<?php
$categoryMap = [
    1 => ['name' => 'Áo',   'icon' => 'fa-tshirt'],
    2 => ['name' => 'Quần', 'icon' => 'fa-user-tie'],
    3 => ['name' => 'Giày', 'icon' => 'fa-shoe-prints'],
];
$totalVariants = is_array($products ?? null) ? count($products) : 0;
$currentCategory = (int)($currentCategory ?? ($_GET['category'] ?? 0));
$currentCategoryName = $currentCategory > 0 && isset($categoryMap[$currentCategory])
    ? $categoryMap[$currentCategory]['name']
    : '';

$pageTitle = 'Trang chủ';
$activeNav = 'home';
require_once __DIR__ . '/_header.php';
?>


    <!-- Carousel Start -->
    <div class="container-fluid carousel bg-light px-0">
        <div class="row g-0 justify-content-end">
            <div class="col-12 col-lg-7 col-xl-9">
                <div class="header-carousel owl-carousel bg-light py-5">
                    <div class="row g-0 header-carousel-item align-items-center">
                        <div class="col-xl-6 carousel-img wow fadeInLeft" data-wow-delay="0.1s">
                            <img src="img/banner.jpg" class="img-fluid w-100" alt="Thời trang nam nữ">
                        </div>
                        <div class="col-xl-6 carousel-content p-4">
                            <h4 class="text-uppercase fw-bold mb-4 wow fadeInRight" data-wow-delay="0.1s"
                                style="letter-spacing: 3px;">HDTT STORE</h4>
                            <h1 class="display-3 text-capitalize mb-4 wow fadeInRight" data-wow-delay="0.3s">
                                Thời Trang Nam Nữ<br>Phong Cách Hiện Đại
                            </h1>
                            <p class="text-dark wow fadeInRight" data-wow-delay="0.5s">
                                Khám phá các mẫu áo, quần và giày phù hợp với phong cách của bạn.
                            </p>
                            <a class="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight" data-wow-delay="0.7s"
                                href="/Duan1/mvc-oop-basic/index.php?act=giaodien">
                                Mua ngay
                            </a>
                        </div>
                    </div>

                    <div class="row g-0 header-carousel-item align-items-center">
                        <div class="col-xl-6 carousel-img wow fadeInLeft" data-wow-delay="0.1s">
                            <img src="img/banner2.png" class="img-fluid w-100" alt="Bộ sưu tập mới">
                        </div>
                        <div class="col-xl-6 carousel-content p-4">
                            <h4 class="text-uppercase fw-bold mb-4 wow fadeInRight" data-wow-delay="0.1s"
                                style="letter-spacing: 3px;">BỘ SƯU TẬP MỚI</h4>
                            <h1 class="display-3 text-capitalize mb-4 wow fadeInRight" data-wow-delay="0.3s">
                                Nâng Cấp Outfit<br>Mỗi Ngày
                            </h1>
                            <p class="text-dark wow fadeInRight" data-wow-delay="0.5s">
                                Sản phẩm chất lượng, dễ phối đồ, phù hợp nhiều phong cách.
                            </p>
                            <a class="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight" data-wow-delay="0.7s"
                                href="/Duan1/mvc-oop-basic/index.php?act=giaodien">
                                Xem sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5 col-xl-3 wow fadeInRight" data-wow-delay="0.1s">
                <div class="carousel-header-banner h-100">
                    <img src="img/header-img.jpg" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="Sản phẩm nổi bật">
                    <div class="carousel-banner-offer">
                        <p class="bg-primary text-white rounded fs-5 py-2 px-4 mb-0 me-3">Hot</p>
                        <p class="text-primary fs-5 fw-bold mb-0">Nổi bật</p>
                    </div>
                    <div class="carousel-banner">
                        <div class="carousel-banner-content text-center p-4">
                            <a href="#" class="d-block mb-2">HDTT Store</a>
                            <a href="#" class="d-block text-white fs-3">Áo, Quần, Giày<br>Phong Cách Mới</a>
                            <span class="text-white fs-5 d-block">Mẫu đẹp - Dễ phối - Giá tốt</span>
                        </div>
                        <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="btn btn-primary rounded-pill py-2 px-4">
                            <i class="fas fa-shopping-cart me-2"></i> Xem ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Danh mục nổi bật -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                <h4 class="text-primary border-bottom border-primary border-2 d-inline-block p-2 title-border-radius">Danh mục</h4>
                <h1 class="mb-0 display-5">Mua sắm theo danh mục</h1>
            </div>
            <div class="row g-4">
                <?php foreach ($categoryMap as $catId => $cat): ?>
                    <div class="col-md-4">
                        <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien&category=<?= $catId ?>#products" class="text-decoration-none">
                            <div class="border rounded p-4 text-center h-100 category-card <?= $currentCategory === $catId ? 'border-primary shadow-sm' : '' ?>"
                                 style="transition: .3s;">
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas <?= $cat['icon'] ?> fa-2x text-primary"></i>
                                </div>
                                <h4 class="text-dark mb-1"><?= htmlspecialchars($cat['name']) ?></h4>
                                <p class="text-muted mb-0">Xem sản phẩm</p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Our Products Start -->
    <div class="container-fluid product py-5" id="products">
        <div class="container py-5">
            <div class="tab-class">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6 text-start wow fadeInLeft" data-wow-delay="0.1s">
                        <h1 class="mb-0">
                            <?php if ($currentCategoryName !== ''): ?>
                                Danh mục: <span class="text-primary"><?= htmlspecialchars($currentCategoryName) ?></span>
                            <?php else: ?>
                                Sản phẩm của chúng tôi
                            <?php endif; ?>
                        </h1>
                        <p class="text-muted mb-0">
                            Hiện có <b><?= $totalVariants ?></b> mẫu
                            <?= $currentCategoryName !== '' ? 'thuộc danh mục "' . htmlspecialchars($currentCategoryName) . '"' : 'đang bán' ?>
                            <?php if ($currentCategory > 0): ?>
                                &middot; <a href="/Duan1/mvc-oop-basic/index.php?act=giaodien" class="text-decoration-none">
                                    <i class="fas fa-times-circle"></i> Bỏ lọc
                                </a>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-lg-6 text-end wow fadeInRight" data-wow-delay="0.1s">
                        <ul class="nav nav-pills d-inline-flex text-center mb-0">
                            <li class="nav-item">
                                <a class="d-flex mx-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                    <span class="text-dark px-3">Tất cả</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="d-flex py-2 mx-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-2">
                                    <span class="text-dark px-3">Mới về</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="d-flex mx-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-3">
                                    <span class="text-dark px-3">Còn hàng</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="tab-content mt-5">
                    <!-- Tab 1: Tất cả sản phẩm -->
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <?php if (!empty($_GET['keyword'])): ?>
                            <div class="mb-4">
                                <p class="mb-0">
                                    Bạn đang tìm: <b><?= htmlspecialchars($_GET['keyword']) ?></b>
                                </p>
                            </div>
                        <?php endif; ?>

                        <style>
                            .pcard{
                                background:#fff;
                                border:1px solid #ececec;
                                border-radius:16px;
                                overflow:hidden;
                                transition:all .3s ease;
                                height:100%;
                                display:flex;flex-direction:column;
                            }
                            .pcard:hover{
                                transform:translateY(-6px);
                                box-shadow:0 16px 40px rgba(15,23,42,.08);
                                border-color:#cfe2ff;
                            }
                            .pcard-img{
                                position:relative;overflow:hidden;
                                aspect-ratio:1/1;
                                background:linear-gradient(135deg,#f8fafc,#eef2ff);
                            }
                            .pcard-img img{
                                width:100%;height:100%;object-fit:cover;
                                transition:transform .5s ease;
                            }
                            .pcard:hover .pcard-img img{transform:scale(1.05)}
                            .pcard-tag{
                                position:absolute;top:12px;left:12px;
                                padding:4px 11px;border-radius:999px;
                                font-size:11px;font-weight:700;letter-spacing:.4px;
                                text-transform:uppercase;color:#fff;
                                box-shadow:0 4px 10px rgba(0,0,0,.1);
                            }
                            .pcard-tag.new{background:linear-gradient(135deg,#10b981,#059669)}
                            .pcard-tag.out{background:linear-gradient(135deg,#dc3545,#991b1b)}
                            .pcard-eye{
                                position:absolute;top:12px;right:12px;
                                width:36px;height:36px;border-radius:50%;
                                background:rgba(255,255,255,.95);
                                display:flex;align-items:center;justify-content:center;
                                color:#0d6efd;font-size:14px;
                                opacity:0;transform:translateX(8px);
                                transition:all .3s ease;
                                box-shadow:0 4px 10px rgba(0,0,0,.1);
                            }
                            .pcard:hover .pcard-eye{opacity:1;transform:translateX(0)}
                            .pcard-eye:hover{background:#0d6efd;color:#fff}
                            .pcard-cat{
                                position:absolute;bottom:12px;left:12px;
                                padding:3px 10px;border-radius:6px;
                                background:rgba(255,255,255,.92);backdrop-filter:blur(4px);
                                font-size:10.5px;font-weight:700;letter-spacing:.5px;
                                color:#1e293b;text-transform:uppercase;
                            }
                            .pcard-body{
                                padding:18px 18px 16px;
                                flex:1;display:flex;flex-direction:column;
                            }
                            .pcard-name{
                                font-size:15.5px;font-weight:700;
                                color:#1e293b;margin:0 0 8px;
                                display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
                                overflow:hidden;line-height:1.35;min-height:42px;
                            }
                            .pcard-name a{color:inherit;text-decoration:none}
                            .pcard-name a:hover{color:#0d6efd}
                            .pcard-meta{
                                display:flex;flex-wrap:wrap;gap:6px;
                                margin-bottom:10px;
                            }
                            .pcard-meta span{
                                font-size:11px;color:#64748b;
                                padding:2px 8px;border-radius:6px;
                                background:#f1f5f9;font-weight:600;
                            }
                            .pcard-price{
                                font-size:18px;font-weight:800;
                                color:#0d6efd;margin-bottom:4px;
                            }
                            .pcard-price small{font-size:12px;color:#94a3b8;font-weight:500}
                            .pcard-stock{font-size:12px;margin-bottom:14px}
                            .pcard-stock.in{color:#059669}
                            .pcard-stock.out{color:#dc3545}
                            .pcard-btn{
                                margin-top:auto;
                                background:linear-gradient(135deg,#0d6efd,#6610f2);
                                color:#fff;
                                padding:10px 16px;border-radius:10px;
                                font-size:13px;font-weight:600;
                                text-align:center;text-decoration:none;
                                display:flex;align-items:center;justify-content:center;gap:6px;
                                transition:all .25s ease;
                                box-shadow:0 4px 12px rgba(13,110,253,.25);
                            }
                            .pcard-btn:hover{
                                color:#fff;
                                transform:translateY(-1px);
                                box-shadow:0 8px 20px rgba(13,110,253,.4);
                            }
                            .pcard-btn.disabled{
                                background:#e5e7eb;color:#94a3b8;
                                box-shadow:none;cursor:not-allowed;pointer-events:none;
                            }
                            .cat-name-1{color:#0ea5e9}
                            .cat-name-2{color:#22c55e}
                            .cat-name-3{color:#a855f7}
                        </style>

                        <div class="row g-4">
                            <?php if (!empty($products)): ?>
                                <?php
                                    $catNames = [1 => 'Áo', 2 => 'Quần', 3 => 'Giày'];
                                ?>
                                <?php foreach ($products as $item):
                                    $hasStock = (int)$item['total_stock'] > 0;
                                    $catId = (int)($item['category_id'] ?? 0);
                                    $catLabel = $catNames[$catId] ?? '';
                                ?>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="pcard wow fadeInUp" data-wow-delay="0.1s">
                                            <div class="pcard-img">
                                                <img src="/Duan1/mvc-oop-basic/uploads/<?= htmlspecialchars($item['image']) ?>"
                                                     alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                     loading="lazy">

                                                <?php if (!$hasStock): ?>
                                                    <span class="pcard-tag out">Hết hàng</span>
                                                <?php else: ?>
                                                    <span class="pcard-tag new">Mới</span>
                                                <?php endif; ?>

                                                <a href="/Duan1/mvc-oop-basic/index.php?act=detail&id=<?= (int)$item['product_id'] ?>"
                                                   class="pcard-eye" title="Xem nhanh">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <?php if ($catLabel): ?>
                                                    <span class="pcard-cat cat-name-<?= $catId ?>"><?= htmlspecialchars($catLabel) ?></span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="pcard-body">
                                                <h5 class="pcard-name">
                                                    <a href="/Duan1/mvc-oop-basic/index.php?act=detail&id=<?= (int)$item['product_id'] ?>">
                                                        <?= htmlspecialchars($item['product_name']) ?>
                                                    </a>
                                                </h5>

                                                <div class="pcard-meta">
                                                    <span title="Số màu"><i class="fas fa-palette me-1"></i><?= count($item['colors']) ?> màu</span>
                                                    <span title="Số size"><i class="fas fa-ruler me-1"></i><?= count($item['sizes']) ?> size</span>
                                                </div>

                                                <div class="pcard-price">
                                                    <?php if ($item['min_price'] === $item['max_price']): ?>
                                                        <?= number_format($item['min_price']) ?>đ
                                                    <?php else: ?>
                                                        <?= number_format($item['min_price']) ?>đ
                                                        <small>– <?= number_format($item['max_price']) ?>đ</small>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="pcard-stock <?= $hasStock ? 'in' : 'out' ?>">
                                                    <?php if ($hasStock): ?>
                                                        <i class="fas fa-check-circle"></i> Còn <?= (int)$item['total_stock'] ?> sản phẩm
                                                    <?php else: ?>
                                                        <i class="fas fa-times-circle"></i> Tạm hết hàng
                                                    <?php endif; ?>
                                                </div>

                                                <a href="/Duan1/mvc-oop-basic/index.php?act=detail&id=<?= (int)$item['product_id'] ?>"
                                                   class="pcard-btn <?= !$hasStock ? 'disabled' : '' ?>">
                                                    <i class="fas fa-shopping-bag"></i> Xem chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-warning text-center py-5">
                                        <i class="fas fa-search fa-2x mb-3 d-block text-muted"></i>
                                        <h5 class="mb-0">Không tìm thấy sản phẩm phù hợp</h5>
                                        <small class="text-muted">Thử từ khóa khác hoặc xem các danh mục khác</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (($totalPages ?? 1) > 1):
                            $qs = function($p) use ($keyword, $currentCategory) {
                                $params = ['act' => 'giaodien', 'page' => $p];
                                if ($keyword !== '') $params['keyword'] = $keyword;
                                if ($currentCategory > 0) $params['category'] = $currentCategory;
                                return '/Duan1/mvc-oop-basic/index.php?' . http_build_query($params) . '#products';
                            };
                            $start = max(1, $page - 2);
                            $end   = min($totalPages, $page + 2);
                        ?>
                        <style>
                            .hdtt-pager{
                                display:flex;justify-content:space-between;align-items:center;
                                flex-wrap:wrap;gap:18px;margin-top:50px;padding-top:30px;
                                border-top:1px solid #ececec;
                            }
                            .hdtt-pager-info{color:#6c757d;font-size:14px}
                            .hdtt-pager-info b{color:#0d6efd}
                            .hdtt-pager-list{
                                display:inline-flex;align-items:center;gap:6px;
                                padding:6px;border-radius:999px;
                                background:#f4f6fb;
                                box-shadow:inset 0 0 0 1px rgba(0,0,0,.04);
                            }
                            .hdtt-page{
                                min-width:40px;height:40px;
                                display:inline-flex;align-items:center;justify-content:center;
                                border-radius:999px;
                                font-weight:600;font-size:14px;color:#475569;
                                text-decoration:none;
                                transition:all .25s ease;
                                padding:0 12px;
                            }
                            .hdtt-page:hover{background:#fff;color:#0d6efd;box-shadow:0 4px 12px rgba(13,110,253,.15)}
                            .hdtt-page.active{
                                background:linear-gradient(135deg,#0d6efd,#6610f2);
                                color:#fff;
                                box-shadow:0 6px 16px rgba(102,16,242,.35);
                                transform:translateY(-1px);
                            }
                            .hdtt-page.active:hover{color:#fff}
                            .hdtt-page.disabled{opacity:.35;pointer-events:none}
                            .hdtt-page.dots{color:#94a3b8;cursor:default;background:transparent}
                            .hdtt-page-arrow{
                                width:42px;height:42px;
                                background:#fff;border:1px solid #e5e7eb;
                            }
                            .hdtt-page-arrow:hover{background:#0d6efd;color:#fff;border-color:#0d6efd}
                        </style>
                        <nav aria-label="Phân trang" class="hdtt-pager">
                            <div class="hdtt-pager-info">
                                <i class="fas fa-cube me-2 text-primary"></i>
                                Hiển thị <b><?= ($page-1)*$perPage + 1 ?></b>–<b><?= min($page*$perPage, $totalItems) ?></b>
                                trên <b><?= $totalItems ?></b> sản phẩm
                            </div>
                            <div class="hdtt-pager-list">
                                <a href="<?= $page > 1 ? $qs($page-1) : '#' ?>"
                                   class="hdtt-page hdtt-page-arrow <?= $page <= 1 ? 'disabled' : '' ?>"
                                   title="Trang trước">
                                    <i class="fas fa-chevron-left"></i>
                                </a>

                                <?php if ($start > 1): ?>
                                    <a href="<?= $qs(1) ?>" class="hdtt-page">1</a>
                                    <?php if ($start > 2): ?>
                                        <span class="hdtt-page dots">…</span>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                    <a href="<?= $qs($i) ?>"
                                       class="hdtt-page <?= $i === $page ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($end < $totalPages): ?>
                                    <?php if ($end < $totalPages - 1): ?>
                                        <span class="hdtt-page dots">…</span>
                                    <?php endif; ?>
                                    <a href="<?= $qs($totalPages) ?>" class="hdtt-page"><?= $totalPages ?></a>
                                <?php endif; ?>

                                <a href="<?= $page < $totalPages ? $qs($page+1) : '#' ?>"
                                   class="hdtt-page hdtt-page-arrow <?= $page >= $totalPages ? 'disabled' : '' ?>"
                                   title="Trang sau">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </nav>
                        <?php endif; ?>
                    </div>

                    <?php
                        $renderCard = function($item, $catNames) {
                            $hasStock = (int)$item['total_stock'] > 0;
                            $catId = (int)($item['category_id'] ?? 0);
                            $catLabel = $catNames[$catId] ?? '';
                            ob_start();
                            ?>
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="pcard wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="pcard-img">
                                        <img src="/Duan1/mvc-oop-basic/uploads/<?= htmlspecialchars($item['image']) ?>"
                                             alt="<?= htmlspecialchars($item['product_name']) ?>" loading="lazy">
                                        <?php if (!$hasStock): ?>
                                            <span class="pcard-tag out">Hết hàng</span>
                                        <?php else: ?>
                                            <span class="pcard-tag new">Mới</span>
                                        <?php endif; ?>
                                        <a href="/Duan1/mvc-oop-basic/index.php?act=detail&id=<?= (int)$item['product_id'] ?>"
                                           class="pcard-eye"><i class="fas fa-eye"></i></a>
                                        <?php if ($catLabel): ?>
                                            <span class="pcard-cat cat-name-<?= $catId ?>"><?= htmlspecialchars($catLabel) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="pcard-body">
                                        <h5 class="pcard-name">
                                            <a href="/Duan1/mvc-oop-basic/index.php?act=detail&id=<?= (int)$item['product_id'] ?>">
                                                <?= htmlspecialchars($item['product_name']) ?>
                                            </a>
                                        </h5>
                                        <div class="pcard-meta">
                                            <span><i class="fas fa-palette me-1"></i><?= count($item['colors']) ?> màu</span>
                                            <span><i class="fas fa-ruler me-1"></i><?= count($item['sizes']) ?> size</span>
                                        </div>
                                        <div class="pcard-price">
                                            <?php if ($item['min_price'] === $item['max_price']): ?>
                                                <?= number_format($item['min_price']) ?>đ
                                            <?php else: ?>
                                                <?= number_format($item['min_price']) ?>đ
                                                <small>– <?= number_format($item['max_price']) ?>đ</small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="pcard-stock <?= $hasStock ? 'in' : 'out' ?>">
                                            <?php if ($hasStock): ?>
                                                <i class="fas fa-check-circle"></i> Còn <?= (int)$item['total_stock'] ?> sản phẩm
                                            <?php else: ?>
                                                <i class="fas fa-times-circle"></i> Tạm hết hàng
                                            <?php endif; ?>
                                        </div>
                                        <a href="/Duan1/mvc-oop-basic/index.php?act=detail&id=<?= (int)$item['product_id'] ?>"
                                           class="pcard-btn <?= !$hasStock ? 'disabled' : '' ?>">
                                            <i class="fas fa-shopping-bag"></i> Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            return ob_get_clean();
                        };
                    ?>

                    <!-- Tab 2: Mới về -->
                    <div id="tab-2" class="tab-pane fade p-0">
                        <div class="row g-4">
                            <?php if (!empty($products)): ?>
                                <?php foreach (array_slice($products, 0, 8) as $item): ?>
                                    <?= $renderCard($item, $catNames ?? [1=>'Áo',2=>'Quần',3=>'Giày']) ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">Chưa có sản phẩm mới.</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tab 3: Còn hàng -->
                    <div id="tab-3" class="tab-pane fade p-0">
                        <div class="row g-4">
                            <?php
                            $inStock = !empty($products)
                                ? array_filter($products, fn($p) => (int)$p['total_stock'] > 0)
                                : [];
                            ?>
                            <?php if (!empty($inStock)): ?>
                                <?php foreach ($inStock as $item): ?>
                                    <?= $renderCard($item, $catNames ?? [1=>'Áo',2=>'Quần',3=>'Giày']) ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">
                                        Hiện chưa có sản phẩm nào còn hàng.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Products End -->

    <!-- Top sản phẩm bán chạy -->
    <?php if (!empty($topSellers)): ?>
    <style>
        .top-section{
            background:linear-gradient(180deg,#fff,#f8fafc);
            padding:60px 0;
            position:relative;
            overflow:hidden;
        }
        .top-section::before{
            content:"";position:absolute;left:-100px;top:-100px;
            width:300px;height:300px;border-radius:50%;
            background:radial-gradient(circle,rgba(13,110,253,.06),transparent 70%);
        }
        .top-section::after{
            content:"";position:absolute;right:-100px;bottom:-100px;
            width:300px;height:300px;border-radius:50%;
            background:radial-gradient(circle,rgba(102,16,242,.06),transparent 70%);
        }
        .top-head{
            text-align:center;
            max-width:600px;margin:0 auto 40px;
            position:relative;
        }
        .top-eyebrow{
            display:inline-flex;align-items:center;gap:6px;
            background:linear-gradient(135deg,#fef3c7,#fde68a);
            color:#92400e;
            padding:6px 14px;border-radius:999px;
            font-size:12px;font-weight:700;letter-spacing:1px;
            text-transform:uppercase;margin-bottom:14px;
        }
        .top-title{
            font-size:38px;font-weight:800;letter-spacing:-1px;
            background:linear-gradient(135deg,#0d6efd,#6610f2);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
            margin:0 0 10px;
        }
        .top-sub{color:#64748b;font-size:15px;margin:0}

        .top-grid{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:18px;
        }
        @media(max-width:992px){.top-grid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:576px){.top-grid{grid-template-columns:1fr}}

        .tcard{
            background:#fff;
            border:1px solid #ececec;
            border-radius:18px;
            overflow:hidden;
            transition:all .3s ease;
            position:relative;
            display:flex;flex-direction:column;
        }
        .tcard:hover{
            transform:translateY(-8px);
            box-shadow:0 20px 50px rgba(15,23,42,.1);
            border-color:transparent;
        }
        .tcard-rank{
            position:absolute;top:14px;left:14px;
            width:38px;height:38px;border-radius:12px;
            display:flex;align-items:center;justify-content:center;
            font-weight:800;font-size:15px;color:#fff;
            box-shadow:0 6px 16px rgba(0,0,0,.15);
            z-index:2;
        }
        .tcard-rank.r1{background:linear-gradient(135deg,#fbbf24,#d97706)}
        .tcard-rank.r2{background:linear-gradient(135deg,#a3a3a3,#737373)}
        .tcard-rank.r3{background:linear-gradient(135deg,#fb923c,#c2410c)}
        .tcard-rank.r-other{background:linear-gradient(135deg,#0d6efd,#6610f2)}

        .tcard-fire{
            position:absolute;top:14px;right:14px;
            background:linear-gradient(135deg,#dc2626,#991b1b);
            color:#fff;
            padding:5px 11px;border-radius:999px;
            font-size:11px;font-weight:700;
            display:flex;align-items:center;gap:5px;
            z-index:2;
            box-shadow:0 4px 10px rgba(220,38,38,.3);
        }
        .tcard-img{
            aspect-ratio:1/1;
            background:linear-gradient(135deg,#f1f5f9,#e2e8f0);
            overflow:hidden;
        }
        .tcard-img img{
            width:100%;height:100%;object-fit:cover;
            transition:transform .5s ease;
        }
        .tcard:hover .tcard-img img{transform:scale(1.08)}
        .tcard-body{padding:16px;flex:1;display:flex;flex-direction:column}
        .tcard-name{
            font-size:14.5px;font-weight:700;
            color:#1e293b;margin:0 0 8px;
            line-height:1.4;
            display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
            overflow:hidden;min-height:40px;
        }
        .tcard-name a{color:inherit;text-decoration:none}
        .tcard-name a:hover{color:#0d6efd}
        .tcard-stats{
            display:flex;align-items:center;justify-content:space-between;
            margin-bottom:10px;
        }
        .tcard-sold{
            font-size:11px;color:#dc2626;font-weight:700;
            background:#fef2f2;padding:3px 9px;border-radius:6px;
            display:inline-flex;align-items:center;gap:4px;
        }
        .tcard-rating{font-size:12px;color:#fbbf24}
        .tcard-rating .text-mut{color:#94a3b8}
        .tcard-price{
            font-size:16px;font-weight:800;color:#0d6efd;
            margin-bottom:12px;
        }
        .tcard-price small{color:#94a3b8;font-size:11px;font-weight:500}
        .tcard-link{
            margin-top:auto;
            display:flex;align-items:center;justify-content:center;gap:6px;
            padding:9px;border-radius:10px;
            background:#f1f5f9;color:#475569;
            font-size:12.5px;font-weight:600;
            text-decoration:none;
            transition:all .25s;
        }
        .tcard-link:hover{
            background:linear-gradient(135deg,#0d6efd,#6610f2);
            color:#fff;
        }
    </style>

    <section class="top-section" id="top-sellers">
        <div class="container">
            <div class="top-head">
                <span class="top-eyebrow"><i class="fas fa-fire"></i> Hot Trend</span>
                <h2 class="top-title">Top sản phẩm bán chạy</h2>
                <p class="top-sub">Những sản phẩm được khách hàng yêu thích và mua nhiều nhất tháng này</p>
            </div>

            <div class="top-grid">
                <?php $rank = 0; ?>
                <?php foreach ($topSellers as $p): $rank++; ?>
                    <?php
                        $rankClass = match (true) {
                            $rank === 1 => 'r1',
                            $rank === 2 => 'r2',
                            $rank === 3 => 'r3',
                            default     => 'r-other',
                        };
                        $rankLabel = match (true) {
                            $rank === 1 => '🥇',
                            $rank === 2 => '🥈',
                            $rank === 3 => '🥉',
                            default     => '#' . $rank,
                        };
                        $sold = (int)($p['sold_qty'] ?? 0);
                    ?>
                    <article class="tcard">
                        <div class="tcard-rank <?= $rankClass ?>"><?= $rankLabel ?></div>
                        <?php if ($sold > 0): ?>
                            <div class="tcard-fire"><i class="fas fa-fire"></i> Đã bán <?= $sold ?></div>
                        <?php endif; ?>

                        <a href="/Duan1/mvc-oop-basic/index.php?act=detail&id=<?= (int)$p['product_id'] ?>" class="tcard-img d-block">
                            <img src="/Duan1/mvc-oop-basic/uploads/<?= htmlspecialchars($p['image']) ?>"
                                 alt="<?= htmlspecialchars($p['product_name']) ?>" loading="lazy">
                        </a>

                        <div class="tcard-body">
                            <h6 class="tcard-name">
                                <a href="/Duan1/mvc-oop-basic/index.php?act=detail&id=<?= (int)$p['product_id'] ?>">
                                    <?= htmlspecialchars($p['product_name']) ?>
                                </a>
                            </h6>

                            <div class="tcard-stats">
                                <span class="tcard-rating">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="text-mut" style="font-weight:600;margin-left:4px">4.5</span>
                                </span>
                            </div>

                            <div class="tcard-price">
                                <?php if ((int)$p['min_price'] === (int)$p['max_price']): ?>
                                    <?= number_format((int)$p['min_price']) ?>đ
                                <?php else: ?>
                                    <?= number_format((int)$p['min_price']) ?>đ
                                    <small>– <?= number_format((int)$p['max_price']) ?>đ</small>
                                <?php endif; ?>
                            </div>

                            <a href="/Duan1/mvc-oop-basic/index.php?act=detail&id=<?= (int)$p['product_id'] ?>"
                               class="tcard-link">
                                <i class="fas fa-shopping-bag"></i> Xem chi tiết
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Cam kết / Dịch vụ -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center bg-white rounded p-4 h-100">
                        <i class="fas fa-truck fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="mb-1">Giao hàng toàn quốc</h5>
                            <small class="text-muted">Phí vận chuyển 30.000đ</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center bg-white rounded p-4 h-100">
                        <i class="fas fa-money-bill-wave fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="mb-1">Thanh toán COD</h5>
                            <small class="text-muted">Nhận hàng rồi mới trả tiền</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center bg-white rounded p-4 h-100">
                        <i class="fas fa-undo fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="mb-1">Đổi trả dễ dàng</h5>
                            <small class="text-muted">Trong vòng 7 ngày</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center bg-white rounded p-4 h-100">
                        <i class="fas fa-headset fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="mb-1">Hỗ trợ 24/7</h5>
                            <small class="text-muted">Hotline 0967807956</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
            border-color: var(--bs-primary) !important;
        }
    </style>

<?php require_once __DIR__ . '/_footer.php'; ?>
