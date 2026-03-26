<?php
class Product {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "duaan1");

        // kiểm tra lỗi kết nối
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

    public function getAllVariants() {
        $sql = "SELECT pv.*, p.name AS product_name, c.name AS color_name, s.name AS size_name
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                JOIN color c ON pv.color_id = c.id
                JOIN size s ON pv.size_id = s.id";

        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
}