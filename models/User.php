<?php
class User {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "php-oop-basic");

        if ($this->conn->connect_error) {
            die("Lỗi DB: " . $this->conn->connect_error);
        }
    }

    // Đăng ký
    public function register($username, $email, $std, $diachi, $password) {
        $sql = "INSERT INTO users(username, email, std, diachi, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bind_param("sssss", $username, $email, $std, $diachi, $hashedPassword);

        return $stmt->execute();
    }

    // Đăng nhập
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    // Người dùng

    // Lấy tất cả user
    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
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
    $sql = "UPDATE users 
            SET username=?, email=?, std=?, diachi=? 
            WHERE id=?";

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
    // Xóa user
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}