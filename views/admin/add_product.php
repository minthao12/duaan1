<!DOCTYPE html>
<html>
<head>
    <title>Thêm sản phẩm</title>
</head>
<body>

<h2>Thêm sản phẩm</h2>

<?php if (isset($_GET['msg'])): ?>
    <p style="color: green"><?= $_GET['msg'] ?></p>
<?php endif; ?>

<form method="POST">
    <label>Tên sản phẩm:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Danh mục:</label><br>
    <input type="number" name="category_id" required><br><br>

    <button type="submit">Thêm</button>
</form>

</body>
</html>