<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm danh mục sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            min-height: 100vh;
        }

        .page-title {
            font-weight: 700;
            color: #0f172a;
        }

        .form-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            padding: 24px;
        }

        .form-header h3 {
            margin: 0;
            font-weight: 700;
        }

        .form-header p {
            margin: 6px 0 0;
            opacity: 0.9;
        }

        .form-body {
            padding: 30px;
            background: white;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            min-height: 46px;
            border: 1px solid #dbe2ea;
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.15);
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #f8fafc;
            border: 1px solid #dbe2ea;
        }

        .btn-modern {
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 18px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .preview-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            padding: 18px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        @media (max-width: 768px) {
            .form-body {
                padding: 20px;
            }

            .top-actions {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="container py-4 py-md-5">
    <div class="top-actions">
        <div>
            <h2 class="page-title mb-1">Thêm danh mục sản phẩm</h2>
            <p class="text-muted mb-0">Tạo mới biến thể sản phẩm cho hệ thống quản trị</p>
        </div>

        <a href="?act=CateProduct" class="btn btn-outline-secondary btn-modern">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card form-card">
        <div class="form-header">
            <h3><i class="bi bi-bag-plus me-2"></i>Thêm mới sản phẩm</h3>
            <p>Nhập đầy đủ thông tin biến thể: sản phẩm, màu sắc, kích thước, giá và tồn kho</p>
        </div>

        <div class="form-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="section-title">
                    <i class="bi bi-card-list me-2"></i>Thông tin cơ bản
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Sản phẩm</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Màu sắc</label>
                        <select name="color_id" class="form-select" required>
                            <option value="">-- Chọn màu --</option>
                            <?php foreach ($colors as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kích thước</label>
                        <select name="size_id" class="form-select" required>
                            <option value="">-- Chọn size --</option>
                            <?php foreach ($sizes as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="section-title">
                    <i class="bi bi-image me-2"></i>Hình ảnh và giá bán
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-12">
                        <label class="form-label">Đường dẫn ảnh</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-image"></i></span>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Giá sản phẩm</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                            <input type="number" name="price" class="form-control" placeholder="Nhập giá sản phẩm" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Số lượng tồn kho</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                            <input type="number" name="stock" class="form-control" placeholder="Nhập số lượng tồn kho" required>
                        </div>
                    </div>
                </div>

                <div class="section-title">
                    <i class="bi bi-eye me-2"></i>Xem trước
                </div>

                <div class="preview-box mb-4">
                    <i class="bi bi-window me-2"></i>
                    Sau khi thêm, biến thể sản phẩm sẽ hiển thị trong trang danh mục sản phẩm của admin.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-modern">
                        <i class="bi bi-plus-circle me-1"></i> Thêm sản phẩm
                    </button>

                    <a href="?act=CateProduct" class="btn btn-light border btn-modern">
                        <i class="bi bi-x-circle me-1"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>