

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h3>👤 Quản lý người dùng</h3>

    <a href="?act=/" class="btn btn-secondary mb-3">← Quay lại</a>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= $u['username'] ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= $u['std'] ?></td>
                <td><?= $u['diachi'] ?></td>
                <td><?= $u['role'] ?></td>
                <td>
                    <a href="?act=editUser&id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="?act=deleteUser&id=<?= $u['id'] ?>" 
                       onclick="return confirm('Xóa user này?')" 
                       class="btn btn-danger btn-sm">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

</body>
</html>