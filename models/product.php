<?php
class Product {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "php-oop-basic");

        // kiểm tra lỗi kết nối
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }


    // sản phẩm gốc

    public function getProductDetailById($id) {
        $sql = "SELECT p.id, p.name AS product_name, p.description, 
                       pv.image, pv.price, pv.stock, 
                       c.name AS color_name, s.name AS size_name
                FROM products p
                LEFT JOIN product_variants pv ON p.id = pv.product_id
                LEFT JOIN color c ON pv.color_id = c.id
                LEFT JOIN size s ON pv.size_id = s.id
                WHERE p.id = ?
                LIMIT 1"; // Lấy đại diện 1 biến thể đầu tiên (nếu có)
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); 
    }

    // 
    public function getAllVariants($keyword = '') {
    $keyword = trim($keyword);

    if ($keyword == '') {
        $sql = "SELECT pv.*, p.name AS product_name, c.name AS color_name, s.name AS size_name
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                JOIN color c ON pv.color_id = c.id
                JOIN size s ON pv.size_id = s.id";
    } else {
        $sql = "SELECT pv.*, p.name AS product_name, c.name AS color_name, s.name AS size_name
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                JOIN color c ON pv.color_id = c.id
                JOIN size s ON pv.size_id = s.id
                WHERE pv.id = '$keyword'
                   OR p.name LIKE '%$keyword%'
                   OR c.name LIKE '%$keyword%'
                   OR s.name LIKE '%$keyword%'
                   OR pv.price LIKE '%$keyword%'
                   OR pv.stock LIKE '%$keyword%'";
    }

    $result = $this->conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}
    public function getVariantById($id) {
        // Câu truy vấn tương tự getAllVariants nhưng thêm điều kiện WHERE
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

    // hàm này để lấy tất cả sản phẩm gốc
   public function getAllProducts($keyword = '') {
    $keyword = trim($keyword);

    if ($keyword == '') {
        $sql = "SELECT p.*, c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id";
    } else {
        $sql = "SELECT p.*, c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = '$keyword'
                   OR p.name = '$keyword'
                   OR p.name LIKE '%$keyword%'
                   OR c.name LIKE '%$keyword%'
                   OR p.description LIKE '%$keyword%'";
    }

    $result = $this->conn->query($sql);

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

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

    // danh mục sản phẩm

    public function addVariant($data) {
    $sql = "INSERT INTO product_variants(product_id, color_id, size_id, image, price, stock)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iiisii", $data['product_id'], $data['color_id'], $data['size_id'], $data['image'], $data['price'], $data['stock']);
    return $stmt->execute();
}

public function updateVariant($id, $data) {
    $sql = "UPDATE product_variants
            SET product_id=?, color_id=?, size_id=?, image=?, price=?, stock=?
            WHERE id=?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iiisiii", $data['product_id'], $data['color_id'], $data['size_id'], $data['image'], $data['price'], $data['stock'], $id);
    return $stmt->execute();
}

public function deleteVariant($id) {
    $sql = "DELETE FROM product_variants WHERE id=?";
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
}