<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>HDTT Admin</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            background: #1e293b;
        }

        .sidebar a {
            color: #cbd5e1;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px 10px;
        }

        .sidebar a:hover {
            background: #334155;
            color: #fff;
        }

        .sidebar .active {
            background: #3b82f6;
            color: #fff;
        }

        /* Header */
        .header {
            background: white;
            border-radius: 12px;
        }

        /* Card */
        .card {
            border: none;
            border-radius: 15px;
        }

        .card-gradient-1 {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .card-gradient-2 {
            background: linear-gradient(45deg, #43e97b, #38f9d7);
            color: white;
        }

        .card-gradient-3 {
            background: linear-gradient(45deg, #fa709a, #fee140);
            color: white;
        }

        .card-gradient-4 {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        /* Table */
        .table img {
            border-radius: 10px;
        }

        .badge {
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <?php
        $currentAct = $_GET['act'] ?? '/';
        ?>

        <div class="col-md-2 sidebar p-3">
            <h4 class="text-white text-center mb-4">HDTT</h4>

            <a href="?act=/" class="<?= ($currentAct == '/' || $currentAct == 'admin') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="?act=ProductUser" class="<?= ($currentAct == 'ProductUser') ? 'active' : '' ?>">
                <i class="bi bi-bag"></i>Danh mục Sản phẩm
            </a>

            <a href="?act=donhang" class="<?= ($currentAct == 'donhang') ? 'active' : '' ?>">
                <i class="bi bi-receipt"></i> Đơn hàng
            </a>

            <a href="?act=users" class="<?= ($currentAct == 'users' || $currentAct == 'editUser') ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Người dùng
            </a>

            <a href="?act=thongke" class="<?= ($currentAct == 'thongke') ? 'active' : '' ?>">
                <i class="bi bi-bar-chart"></i> Thống kê
            </a>
        </div>

        <!-- Main -->
        <div class="col-md-10 p-3">

            <!-- Header -->
            <div class="header d-flex justify-content-between align-items-center p-3 shadow-sm mb-4">
                <h5 class="mb-0">Dashboard</h5>

                <div class="d-flex align-items-center gap-3">
                    <form method="GET" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="act" value="ProductUser">

                        <input 
                            type="text" 
                            name="keyword" 
                            class="form-control form-control-sm" 
                            placeholder="Tìm theo sản phẩm, màu, size..."
                            value="<?= $_GET['keyword'] ?? '' ?>"
                        >

                        <button type="submit" class="btn btn-primary btn-sm">Tìm</button>
                    </form>
                    <i class="bi bi-bell"></i>

                    <?php if (isset($_SESSION['user'])): ?>
                        <span><?= $_SESSION['user'] ?></span>
                        <a href="?act=logout" class="btn btn-danger btn-sm">Đăng xuất</a>
                    <?php else: ?>
                        <a href="?act=login" class="btn btn-primary btn-sm">Đăng nhập</a>
                        <a href="?act=register" class="btn btn-success btn-sm">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Cards -->
            <div class="row g-3 mb-4">

                <div class="col-md-3">
                    <div class="card card-gradient-1 p-3 shadow-sm">
                        <h4>100</h4>
                        <p>Sản phẩm</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-gradient-2 p-3 shadow-sm">
                        <h4>50</h4>
                        <p>Đơn hàng</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-gradient-3 p-3 shadow-sm">
                        <h4>20</h4>
                        <p>Khách hàng</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-gradient-4 p-3 shadow-sm">
                        <h4>10tr</h4>
                        <p>Doanh thu</p>
                    </div>
                </div>

            </div>
                        <?php if (!empty($_GET['keyword'])): ?>
                            <p>Bạn đang tìm: <b><?= $_GET['keyword'] ?></b></p>
                        <?php endif; ?>
            <!-- Table -->
            <table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Sản phẩm</th>
            <th>Màu</th>
            <th>Size</th>
            <th>Giá</th>
            <th>Tồn kho</th>
            
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($variants)): ?>
            <?php foreach ($variants as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><img src="uploads/<?= $item['image'] ?>" width="80"></td>
                    <td><?= $item['product_name'] ?></td>
                    <td><?= $item['color_name'] ?></td>
                    <td><?= $item['size_name'] ?></td>
                    <td><?= number_format($item['price']) ?>đ</td>
                    <td>
                        <?php if ($item['stock'] > 0): ?>
                            <span class="badge bg-success"><?= $item['stock'] ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger">Hết hàng</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-danger">Không tìm thấy sản phẩm</td>
            </tr>
        <?php endif; ?>
    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>

</body>
</html>