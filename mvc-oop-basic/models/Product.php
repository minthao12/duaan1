<?php
class Product {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "php-oop-basic");

        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    // =========================
    // SẢN PHẨM GỐC
    // =========================

    public function getProductDetailById($id) {
    $sql = "SELECT p.id, p.name AS product_name, p.description,
                   pv.image, pv.price, pv.stock,
                   c.name AS color_name, s.name AS size_name
            FROM products p
            LEFT JOIN product_variants pv ON p.id = pv.product_id AND pv.status = 1
            LEFT JOIN color c ON pv.color_id = c.id
            LEFT JOIN size s ON pv.size_id = s.id
            WHERE p.id = ?
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
    }

    public function getAllProducts($keyword = '') {
        $keyword = trim($keyword);

        if ($keyword === '') {
            $sql = "SELECT p.*, c.name AS category_name
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    ORDER BY p.id DESC";
            $result = $this->conn->query($sql);
        } else {
            $sql = "SELECT p.*, c.name AS category_name
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.id = ?
                       OR p.name = ?
                       OR p.name LIKE ?
                       OR c.name LIKE ?
                       OR p.description LIKE ?
                    ORDER BY p.id DESC";

            $stmt = $this->conn->prepare($sql);
            $like = "%$keyword%";
            $id = is_numeric($keyword) ? (int)$keyword : 0;
            $stmt->bind_param("issss", $id, $keyword, $like, $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
        }

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function addProduct($data) {
        $sql = "INSERT INTO products(name, category_id, description) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sis", $data['name'], $data['category_id'], $data['description']);
        return $stmt->execute();
    }

    public function updateProduct($id, $data) {
        $sql = "UPDATE products
                SET name = ?, category_id = ?, description = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisi", $data['name'], $data['category_id'], $data['description'], $id);
        return $stmt->execute();
    }

    public function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getProducts() {
        $sql = "SELECT id, name FROM products ORDER BY id DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function hasOrder($productId) {
    $sql = "SELECT COUNT(*) AS total
            FROM order_details od
            JOIN product_variants pv ON od.variant_id = pv.id
            WHERE pv.product_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return (int)$result['total'] > 0;
}

public function deleteProductSafe($productId) {
    if ($this->hasOrder($productId)) {
        return false;
    }

    $this->conn->begin_transaction();

    try {
        $sqlCart = "DELETE c
                    FROM cart c
                    JOIN product_variants pv ON c.variant_id = pv.id
                    WHERE pv.product_id = ?";
        $stmtCart = $this->conn->prepare($sqlCart);
        $stmtCart->bind_param("i", $productId);
        $stmtCart->execute();

        $sqlVariant = "UPDATE product_variants SET status = 0 WHERE product_id = ?";
        $stmtVariant = $this->conn->prepare($sqlVariant);
        $stmtVariant->bind_param("i", $productId);
        $stmtVariant->execute();

        $sqlProduct = "DELETE FROM products WHERE id = ?";
        $stmtProduct = $this->conn->prepare($sqlProduct);
        $stmtProduct->bind_param("i", $productId);
        $stmtProduct->execute();

        $this->conn->commit();
        return true;
    } catch (Exception $e) {
        $this->conn->rollback();
        throw $e;
    }
    }

    // =========================
    // BIẾN THỂ SẢN PHẨM
    // =========================

    public function getAllVariants($keyword = '') {
    $keyword = trim($keyword);

    if ($keyword == '') {
        $sql = "SELECT 
                    pv.id,
                    pv.product_id,
                    pv.image,
                    pv.price,
                    pv.stock,
                    p.name AS product_name,
                    p.description,
                    c.name AS color_name,
                    s.name AS size_name
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                JOIN color c ON pv.color_id = c.id
                JOIN size s ON pv.size_id = s.id
                WHERE pv.status = 1
                ORDER BY pv.id DESC";
        $result = $this->conn->query($sql);
    } else {
        $sql = "SELECT 
                    pv.id,
                    pv.product_id,
                    pv.image,
                    pv.price,
                    pv.stock,
                    p.name AS product_name,
                    p.description,
                    c.name AS color_name,
                    s.name AS size_name
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                JOIN color c ON pv.color_id = c.id
                JOIN size s ON pv.size_id = s.id
                WHERE pv.status = 1
                  AND (
                        pv.id = ?
                        OR p.name LIKE ?
                        OR c.name LIKE ?
                        OR s.name LIKE ?
                  )
                ORDER BY pv.id DESC";

        $stmt = $this->conn->prepare($sql);
        $id = is_numeric($keyword) ? (int)$keyword : 0;
        $like = "%$keyword%";
        $stmt->bind_param("isss", $id, $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
    }

    public function getVariantById($id) {
    $sql = "SELECT pv.*, p.name AS product_name, c.name AS color_name, s.name AS size_name
            FROM product_variants pv
            JOIN products p ON pv.product_id = p.id
            JOIN color c ON pv.color_id = c.id
            JOIN size s ON pv.size_id = s.id
            WHERE pv.id = ? AND pv.status = 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
    }

    public function addVariant($data) {
        $sql = "INSERT INTO product_variants(product_id, color_id, size_id, image, price, stock)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iiisii",
            $data['product_id'],
            $data['color_id'],
            $data['size_id'],
            $data['image'],
            $data['price'],
            $data['stock']
        );
        return $stmt->execute();
    }

    public function updateVariant($id, $data) {
        $sql = "UPDATE product_variants
                SET product_id = ?, color_id = ?, size_id = ?, image = ?, price = ?, stock = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iiisiii",
            $data['product_id'],
            $data['color_id'],
            $data['size_id'],
            $data['image'],
            $data['price'],
            $data['stock'],
            $id
        );
        return $stmt->execute();
    }

    public function deleteVariant($id) {
    $sql = "UPDATE product_variants SET status = 0 WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
    }

    public function getColors() {
        $sql = "SELECT id, name FROM color ORDER BY id DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getSizes() {
        $sql = "SELECT id, name FROM size ORDER BY id DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // =========================
    // USER
    // =========================

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateUser($id, $data) {
        $sql = "UPDATE users SET username=?, email=?, std=?, diachi=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssi",
            $data['username'],
            $data['email'],
            $data['std'],
            $data['diachi'],
            $id
        );
        return $stmt->execute();
    }

    // =========================
    // CLIENT / CART / ORDER
    // =========================

    public function getProductDetailWithVariants($productId) {
    $sql = "SELECT 
                p.id AS product_id,
                p.name AS product_name,
                p.description,
                pv.id AS variant_id,
                pv.image,
                pv.price,
                pv.stock,
                c.id AS color_id,
                c.name AS color_name,
                s.id AS size_id,
                s.name AS size_name
            FROM products p
            JOIN product_variants pv ON p.id = pv.product_id
            JOIN color c ON pv.color_id = c.id
            JOIN size s ON pv.size_id = s.id
            WHERE p.id = ? AND pv.status = 1
            ORDER BY pv.id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
    }

    public function getVariantByProductColorSize($productId, $colorId, $sizeId) {
    $sql = "SELECT *
            FROM product_variants
            WHERE product_id = ? AND color_id = ? AND size_id = ? AND status = 1
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iii", $productId, $colorId, $sizeId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }

    public function addToCart($userId, $variantId, $quantity) {
        $checkSql = "SELECT id, quantity FROM cart WHERE user_id = ? AND variant_id = ?";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $userId, $variantId);
        $checkStmt->execute();
        $exists = $checkStmt->get_result()->fetch_assoc();

        if ($exists) {
            $newQty = $exists['quantity'] + $quantity;
            $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $newQty, $exists['id']);
            return $stmt->execute();
        }

        $sql = "INSERT INTO cart(user_id, variant_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $variantId, $quantity);
        return $stmt->execute();
    }

    public function getCartByUser($userId) {
    $sql = "SELECT 
                cart.id,
                cart.quantity,
                pv.id AS variant_id,
                pv.image,
                pv.price,
                pv.stock,
                p.name AS product_name,
                c.name AS color_name,
                s.name AS size_name
            FROM cart
            JOIN product_variants pv ON cart.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            JOIN color c ON pv.color_id = c.id
            JOIN size s ON pv.size_id = s.id
            WHERE cart.user_id = ? AND pv.status = 1
            ORDER BY cart.id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
    }

    public function updateCartQuantity($cartId, $userId, $quantity) {
    $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $cartId, $userId);
    return $stmt->execute();
    }

    public function deleteCartItem($cartId, $userId) {
        $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $cartId, $userId);
        return $stmt->execute();
    }

    public function getCartItemsByIds($userId, $cartIds) {
    if (empty($cartIds)) {
        return [];
    }

    $cartIds = array_map('intval', $cartIds);
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
    $types = 'i' . str_repeat('i', count($cartIds));
    $params = array_merge([$userId], $cartIds);

    $sql = "SELECT 
                cart.id,
                cart.quantity,
                pv.id AS variant_id,
                pv.image,
                pv.price,
                pv.stock,
                p.id AS product_id,
                p.name AS product_name,
                c.name AS color_name,
                s.name AS size_name
            FROM cart
            JOIN product_variants pv ON cart.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            JOIN color c ON pv.color_id = c.id
            JOIN size s ON pv.size_id = s.id
            WHERE cart.user_id = ?
              AND cart.id IN ($placeholders)
              AND pv.status = 1
            ORDER BY cart.id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
    }

    public function createOrder($data) {
    $sql = "INSERT INTO orders (
                user_id,
                total,
                shipping_fee,
                payment_method,
                status,
                payment_status,
                receiver_name,
                receiver_phone,
                receiver_address,
                online
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
        "iddsssssss",
        $data['user_id'],
        $data['total'],
        $data['shipping_fee'],
        $data['payment_method'],
        $data['status'],
        $data['payment_status'],
        $data['receiver_name'],
        $data['receiver_phone'],
        $data['receiver_address'],
        $data['online']
    );

    if ($stmt->execute()) {
        return $this->conn->insert_id;
    }

    return false;
}

    public function addOrderDetail($orderId, $variantId, $quantity, $price) {
        $sql = "INSERT INTO order_details(order_id, variant_id, quantity, price)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiii", $orderId, $variantId, $quantity, $price);
        return $stmt->execute();
    }

    public function updateVariantStock($variantId, $quantityBought) {
        $sql = "UPDATE product_variants
                SET stock = stock - ?
                WHERE id = ? AND stock >= ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $quantityBought, $variantId, $quantityBought);
        return $stmt->execute();
    }

    public function removeManyCartItems($userId, $cartIds) {
        if (empty($cartIds)) {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
        $types = str_repeat('i', count($cartIds) + 1);

        $sql = "DELETE FROM cart WHERE user_id = ? AND id IN ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        $params = array_merge([$userId], $cartIds);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

    public function getOrdersByUser($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    //=========================

    public function getCartItemById($cartId, $userId) {
    $sql = "SELECT 
                c.id,
                c.quantity,
                pv.stock,
                p.name AS product_name
            FROM cart c
            JOIN product_variants pv ON c.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            WHERE c.id = ? AND c.user_id = ? AND pv.status = 1
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $cartId, $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }

public function getAllOrders() {
    $sql = "SELECT 
                o.*,
                u.username
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.id DESC";
    $result = $this->conn->query($sql);

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

public function getOrderById($id) {
    $sql = "SELECT * FROM orders WHERE id = ? LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

public function updateOrderStatusById($id, $status) {
    if ($status === 'hoan_thanh') {
        $sql = "UPDATE orders 
                SET status = ?, payment_status = 'paid'
                WHERE id = ?";
    } else {
        $sql = "UPDATE orders 
                SET status = ?
                WHERE id = ?";
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    return $stmt->execute();
}

public function getOrderHistoryByUser($userId) {
    $sql = "SELECT *
            FROM orders
            WHERE user_id = ?
            ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

public function getOrderDetails($orderId) {
    $sql = "SELECT 
                od.*,
                p.name AS product_name,
                pv.image,
                c.name AS color_name,
                s.name AS size_name
            FROM order_details od
            JOIN product_variants pv ON od.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            LEFT JOIN color c ON pv.color_id = c.id
            LEFT JOIN size s ON pv.size_id = s.id
            WHERE od.order_id = ?
            ORDER BY od.id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

public function getOrderByIdAndUser($orderId, $userId) {
    $sql = "SELECT * 
            FROM orders 
            WHERE id = ? AND user_id = ?
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

public function getThongKeDoanhThu() {
    $data = [];

    $sqlTongDon = "SELECT COUNT(*) AS total_orders FROM orders";
    $resultTongDon = $this->conn->query($sqlTongDon);
    $data['total_orders'] = $resultTongDon ? (int)$resultTongDon->fetch_assoc()['total_orders'] : 0;

    $sqlHoanThanh = "SELECT COUNT(*) AS completed_orders 
                     FROM orders 
                     WHERE status = 'hoan_thanh'";
    $resultHoanThanh = $this->conn->query($sqlHoanThanh);
    $data['completed_orders'] = $resultHoanThanh ? (int)$resultHoanThanh->fetch_assoc()['completed_orders'] : 0;

    $sqlDoanhThu = "SELECT COALESCE(SUM(total), 0) AS revenue
                    FROM orders
                    WHERE status = 'hoan_thanh'";
    $resultDoanhThu = $this->conn->query($sqlDoanhThu);
    $data['revenue'] = $resultDoanhThu ? (float)$resultDoanhThu->fetch_assoc()['revenue'] : 0;

    $sqlChuaThanhToan = "SELECT COUNT(*) AS unpaid_orders
                         FROM orders
                         WHERE payment_status = 'unpaid'";
    $resultChuaThanhToan = $this->conn->query($sqlChuaThanhToan);
    $data['unpaid_orders'] = $resultChuaThanhToan ? (int)$resultChuaThanhToan->fetch_assoc()['unpaid_orders'] : 0;

    // chỉ chạy thống kê theo ngày khi bảng orders có cột created_at
    $checkColumn = $this->conn->query("SHOW COLUMNS FROM orders LIKE 'created_at'");
    $data['daily_revenue'] = [];

    if ($checkColumn && $checkColumn->num_rows > 0) {
        $sqlTheoNgay = "SELECT DATE(created_at) AS order_date, COALESCE(SUM(total),0) AS daily_revenue
                        FROM orders
                        WHERE status = 'hoan_thanh'
                        GROUP BY DATE(created_at)
                        ORDER BY order_date DESC
                        LIMIT 10";
        $resultTheoNgay = $this->conn->query($sqlTheoNgay);

        if ($resultTheoNgay) {
            while ($row = $resultTheoNgay->fetch_assoc()) {
                $data['daily_revenue'][] = $row;
            }
        }
    }

    return $data;
}
}