<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa danh mục sản phẩm</title>
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
            background: linear-gradient(135deg, #f59e0b, #f97316);
            color: white;
            padding: 24px;
        }

        .form-header h3 {
            margin: 0;
            font-weight: 700;
        }

        .form-header p {
            margin: 6px 0 0;
            opacity: 0.92;
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
            border-color: #f59e0b;
            box-shadow: 0 0 0 0.2rem rgba(245,158,11,0.15);
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
            background: #fff7ed;
            border: 1px dashed #fdba74;
            border-radius: 14px;
            padding: 18px;
            color: #9a3412;
            font-size: 14px;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .current-image {
            background: #f8fafc;
            border-radius: 14px;
            padding: 16px;
            border: 1px solid #e2e8f0;
        }

        .image-demo {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 12px;
            border: 1px dashed #cbd5e1;
            color: #64748b;
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
            <h2 class="page-title mb-1">Sửa danh mục sản phẩm</h2>
            <p class="text-muted mb-0">Cập nhật thông tin biến thể sản phẩm trong hệ thống quản trị</p>
        </div>

        <a href="?act=CateProduct" class="btn btn-outline-secondary btn-modern">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card form-card">
        <div class="form-header">
            <h3><i class="bi bi-pencil-square me-2"></i>Cập nhật sản phẩm</h3>
            <p>Chỉnh sửa thông tin hiện tại của biến thể sản phẩm</p>
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
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= ($variant['product_id'] == $p['id']) ? 'selected' : '' ?>>
                                    <?= $p['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Màu sắc</label>
                        <select name="color_id" class="form-select" required>
                            <?php foreach ($colors as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($variant['color_id'] == $c['id']) ? 'selected' : '' ?>>
                                    <?= $c['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kích thước</label>
                        <select name="size_id" class="form-select" required>
                            <?php foreach ($sizes as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= ($variant['size_id'] == $s['id']) ? 'selected' : '' ?>>
                                    <?= $s['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="section-title">
                    <i class="bi bi-image me-2"></i>Hình ảnh và giá bán
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-8">
                        <label class="form-label">Ảnh hiện tại</label>
                        <div class="mb-2">
                            <img src="uploads/<?= $variant['image'] ?>" width="100" class="rounded border">
                        </div>

                        <label class="form-label">Chọn ảnh mới</label>
                        <input type="file" name="image" class="form-control" accept="image/*">

                        <input type="hidden" name="old_image" value="<?= $variant['image'] ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Xem nhanh</label>
                        <div class="current-image">
                            <div class="image-demo">
                                <?= htmlspecialchars($variant['image']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Giá sản phẩm</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                            <input type="number" name="price" class="form-control" value="<?= $variant['price'] ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Số lượng tồn kho</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                            <input type="number" name="stock" class="form-control" value="<?= $variant['stock'] ?>" required>
                        </div>
                    </div>
                </div>

                <div class="section-title">
                    <i class="bi bi-info-circle me-2"></i>Ghi chú
                </div>

                <div class="preview-box mb-4">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    Kiểm tra kỹ màu sắc, size, giá và tồn kho trước khi cập nhật để tránh sai dữ liệu hiển thị ngoài trang sản phẩm.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-white btn-modern">
                        <i class="bi bi-save me-1"></i> Cập nhật
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