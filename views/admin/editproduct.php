<form method="POST">
    <input type="text" name="name" value="<?= $product['name'] ?>">

    <input type="number" name="price" value="<?= $product['price'] ?>">
    <input type="number" name="stock" value="<?= $product['stock'] ?>">

    <button type="submit">Cập nhật</button>
</form>