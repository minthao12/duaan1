<?php
class Product {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "php-oop-basic");

        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

    // =========================
    // SẢN PHẨM GỐC
    // =========================

    public function getProductDetailById($id) {
        $sql = "SELECT p.id, p.name AS product_name, p.description,
                       pv.image, pv.price, pv.stock,
                       c.name AS color_name, s.name AS size_name
                FROM products p
                LEFT JOIN product_variants pv ON p.id = pv.product_id
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

        if ($keyword == '') {
            $sql = "SELECT p.*, c.name AS category_name
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id";
            $result = $this->conn->query($sql);
        } else {
            $sql = "SELECT p.*, c.name AS category_name
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.id = ?
                       OR p.name = ?
                       OR p.name LIKE ?
                       OR c.name LIKE ?
                       OR p.description LIKE ?";

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
        $sql = "UPDATE products SET name = ?, category_id = ?, description = ? WHERE id = ?";
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
        $sql = "SELECT * FROM categories";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
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
                    WHERE pv.id = ?
                       OR p.name LIKE ?
                       OR c.name LIKE ?
                       OR s.name LIKE ?
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
                WHERE pv.id = ?";
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
        $sql = "DELETE FROM product_variants WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getProducts() {
        $sql = "SELECT id, name FROM products";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getColors() {
        $sql = "SELECT id, name FROM color";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getSizes() {
        $sql = "SELECT id, name FROM size";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
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

    //==================
    //Cart
    //==================
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
            WHERE p.id = ?
            ORDER BY c.id, s.id";
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
    $sql = "SELECT 
                pv.*,
                p.name AS product_name,
                p.description,
                c.name AS color_name,
                s.name AS size_name
            FROM product_variants pv
            JOIN products p ON pv.product_id = p.id
            JOIN color c ON pv.color_id = c.id
            JOIN size s ON pv.size_id = s.id
            WHERE pv.product_id = ? AND pv.color_id = ? AND pv.size_id = ?
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iii", $productId, $colorId, $sizeId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

public function addToCart($userId, $variantId, $quantity) {
    $sqlCheck = "SELECT * FROM cart WHERE user_id = ? AND variant_id = ?";
    $stmtCheck = $this->conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $userId, $variantId);
    $stmtCheck->execute();
    $cartItem = $stmtCheck->get_result()->fetch_assoc();

    if ($cartItem) {
        $sqlUpdate = "UPDATE cart SET quantity = quantity + ? WHERE id = ?";
        $stmtUpdate = $this->conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ii", $quantity, $cartItem['id']);
        return $stmtUpdate->execute();
    } else {
        $sqlInsert = "INSERT INTO cart(user_id, variant_id, quantity) VALUES (?, ?, ?)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        $stmtInsert->bind_param("iii", $userId, $variantId, $quantity);
        return $stmtInsert->execute();
    }
}

public function getCartByUser($userId) {
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

    public function updateCartQuantity($cartId, $quantity, $userId) {
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
}