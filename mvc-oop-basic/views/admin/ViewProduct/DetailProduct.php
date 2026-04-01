<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
        }
        .sidebar {
            min-height: 100vh;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }
    </style>
</head>
<style>
    .card-modern {
    border-radius: 20px;
    transition: 0.3s;
}

.card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

/* size button */   
.size-btn {
    border-radius: 8px;
    padding: 4px 10px;
}

.size-btn.active {
    background: #2563eb;
    color: white;
}

/* quantity */
.qty-input {
    font-weight: 600;
}
    body {
    background: #f1f5f9;
    font-family: 'Inter', sans-serif;
}

/* Sidebar */
.sidebar {
    background: #0f172a;
    min-height: 100vh;
}

.sidebar .nav-link {
    color: #94a3b8;
    border-radius: 10px;
    margin-bottom: 8px;
    transition: 0.25s;
}

.sidebar .nav-link:hover {
    background: #1e293b;
    color: #fff;
}

.sidebar .nav-link.active {
    background: #2563eb;
    color: #fff;
}

/* Header */
.header {
    background: white;
    border-radius: 16px;
}

/* Card */
.card-modern {
    border: none;
    border-radius: 18px;
    background: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    transition: 0.3s;
}

.card-modern:hover {
    transform: translateY(-6px);
}

/* Gradient card */
.card-gradient {
    border-radius: 18px;
    color: white;
    border: none;
}

/* Table */
.table-container {
    background: white;
    border-radius: 18px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    padding: 15px;
}

.table thead th {
    font-size: 14px;
    color: #64748b;
}

.table tbody tr:hover {
    background: #f8fafc;
}

/* Avatar */
.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
</style>
<script>
    // tăng giảm số lượng
    document.querySelectorAll('.plus').forEach(btn => {
        btn.onclick = () => {
            let input = btn.parentElement.querySelector('.qty-input');
            input.value = parseInt(input.value) + 1;
        }
    });

    document.querySelectorAll('.minus').forEach(btn => {
        btn.onclick = () => {
            let input = btn.parentElement.querySelector('.qty-input');
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    });

    // chọn size
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.onclick = () => {
            let group = btn.parentElement.querySelectorAll('.size-btn');
            group.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }
    });
</script>
<body>
<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 sidebar p-3">
            <h5 class="text-white text-center mb-4">HDTT Admin</h5>

            <ul class="nav flex-column">
                <li><a class="nav-link active" href="#"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                <li><a class="nav-link" href="#"><i class="bi bi-bag me-2"></i>Sản phẩm</a></li>
                <li><a class="nav-link" href="#"><i class="bi bi-receipt me-2"></i>Đơn hàng</a></li>
                <li><a class="nav-link" href="#"><i class="bi bi-people me-2"></i>Người dùng</a></li>
            </ul>
        </div>

        <!-- MAIN -->
        <div class="col-md-10 p-4">

            <!-- HEADER -->
            <div class="header d-flex justify-content-between align-items-center p-3 mb-4">
                <h5 class="mb-0 fw-semibold">Dashboard</h5>

                <div class="d-flex align-items-center gap-3">
                    <input class="form-control form-control-sm" style="width:200px" placeholder="Tìm kiếm...">
                    <i class="bi bi-bell fs-5"></i>
                    <img src="https://i.pravatar.cc/40" class="avatar">
                </div>
            </div>

            <!-- CARDS -->
            <div class="row g-3 mb-4">

                <div class="col-md-3">
                    <div class="card card-gradient p-3" style="background: linear-gradient(135deg,#3b82f6,#6366f1)">
                        <h4>100</h4>
                        <small>Sản phẩm</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-gradient p-3" style="background: linear-gradient(135deg,#10b981,#34d399)">
                        <h4>50</h4>
                        <small>Đơn hàng</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-gradient p-3" style="background: linear-gradient(135deg,#f59e0b,#fbbf24)">
                        <h4>20</h4>
                        <small>Khách hàng</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-gradient p-3" style="background: linear-gradient(135deg,#ef4444,#f87171)">
                        <h4>10tr</h4>
                        <small>Doanh thu</small>
                    </div>
                </div>

            </div>

            <!-- TABLE -->
            <div class="table-container">
                <div class="d-flex justify-content-between mb-3">
                    <h6 class="fw-semibold">Sản phẩm gần đây</h6>
                    <button class="btn btn-primary btn-sm">+ Thêm</button>
                </div>

                <!-- PRODUCT CARD -->
<div class="col-md-4">
                <div class="card-modern p-3">

                    <div class="text-center mb-3">
                        <img src="<?= $item['image'] ?? 'https://placehold.co/400x400?text=Chua+co+anh' ?>" style="height:180px;object-fit:cover;border-radius:12px">
                    </div>

                    <h6 class="fw-semibold"><?= $item['product_name'] ?></h6>

                    <h5 class="text-danger">
                        <?= isset($item['price']) ? number_format($item['price']) . 'đ' : 'Chưa cập nhật giá' ?>
                    </h5>

                    <div class="mb-2">
                        <small class="text-muted">Size:</small>
                        <div class="d-flex gap-2 mt-1">
                            <?php if (isset($item['size_name'])): ?>
                                <button class="btn btn-primary btn-sm size-btn active"><?= $item['size_name'] ?></button>
                            <?php else: ?>
                                <span class="badge bg-light text-dark">Chưa có</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Màu:</small>
                        <div class="d-flex gap-2 mt-1">
                            <span class="fw-bold"><?= $item['color_name'] ?? 'Chưa cập nhật' ?></span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <small class="text-muted">Số lượng:</small>
                        <div>
                            <?php if (isset($item['stock']) && $item['stock'] > 0): ?>
                                <span class="badge bg-success"><?= $item['stock'] ?></span>
                            <?php elseif (isset($item['stock']) && $item['stock'] == 0): ?>
                                <span class="badge bg-danger">Hết hàng</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Chưa cập nhật</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Sửa thông tin
                        </button>
                    </div>

                </div>
            </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>