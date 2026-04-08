<?php

class Product
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = $this->connect();
    }

    private function connect(): PDO
    {
        $host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
        $dbname = defined('DB_NAME') ? DB_NAME : 'php-oop-basic';
        $user = defined('DB_USERNAME') ? DB_USERNAME : (defined('DB_USER') ? DB_USER : 'root');
        $pass = defined('DB_PASSWORD') ? DB_PASSWORD : (defined('DB_PASS') ? DB_PASS : '');

        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    private function buildSearchCondition(string $keyword, string $field = 'p.name'): array
    {
        $keyword = trim($keyword);

        if ($keyword === '') {
            return ['sql' => '', 'params' => []];
        }

        if (
            mb_strlen($keyword) >= 2 &&
            $keyword[0] === '"' &&
            mb_substr($keyword, -1) === '"'
        ) {
            $exact = trim($keyword, '"');
            return [
                'sql' => " WHERE {$field} = ? ",
                'params' => [$exact]
            ];
        }

        return [
            'sql' => " WHERE {$field} LIKE ? ",
            'params' => ['%' . $keyword . '%']
        ];
    }

    public function getAllProducts(string $keyword = ''): array
    {
        $search = $this->buildSearchCondition($keyword, 'p.name');

        $sql = "
            SELECT 
                p.*,
                c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            {$search['sql']}
            ORDER BY p.id DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($search['params']);

        return $stmt->fetchAll();
    }

    public function getSimpleProducts(): array
    {
        $stmt = $this->conn->query("
            SELECT id, name
            FROM products
            ORDER BY id DESC
        ");

        return $stmt->fetchAll();
    }

    public function getProductById(int $id): array|false
    {
        $stmt = $this->conn->prepare("
            SELECT p.*, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE p.id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function findProductByCode(string $code): array|false
    {
        return false;
    }

    public function createProduct(array $data): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO products(name, description)
            VALUES (?, ?)
        ");

        return $stmt->execute([
            $data['name'],
            $data['description']
        ]);
    }

    public function updateProduct(int $id, array $data): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE products
            SET name = ?, description = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            $data['description'],
            $id
        ]);
    }

    public function deleteProduct(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getProductsForClient(string $keyword = ''): array
    {
        $sql = "
            SELECT
                pv.id,
                pv.product_id,
                p.name AS product_name,
                p.description,
                c.name AS color_name,
                s.name AS size_name,
                pv.price,
                pv.stock AS quantity,
                pv.stock,
                pv.image,
                cat.name AS category_name
            FROM product_variants pv
            INNER JOIN products p ON p.id = pv.product_id
            LEFT JOIN color c ON c.id = pv.color_id
            LEFT JOIN size s ON s.id = pv.size_id
            LEFT JOIN categories cat ON cat.id = p.category_id
        ";

        $params = [];
        $keyword = trim($keyword);

        if ($keyword !== '') {
            if (
                mb_strlen($keyword) >= 2 &&
                $keyword[0] === '"' &&
                mb_substr($keyword, -1) === '"'
            ) {
                $keyword = trim($keyword, '"');
                $sql .= " WHERE p.name = ? ";
                $params[] = $keyword;
            } else {
                $sql .= " WHERE p.name LIKE ? ";
                $params[] = '%' . $keyword . '%';
            }
        }

        $sql .= " ORDER BY pv.id DESC ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getProductVariantsByProductId(int $productId): array
    {
        $stmt = $this->conn->prepare("
            SELECT
                pv.*,
                p.name AS product_name,
                p.description,
                c.name AS color_name,
                s.name AS size_name,
                pv.stock AS quantity
            FROM product_variants pv
            INNER JOIN products p ON p.id = pv.product_id
            LEFT JOIN color c ON c.id = pv.color_id
            LEFT JOIN size s ON s.id = pv.size_id
            WHERE pv.product_id = ?
            ORDER BY pv.id ASC
        ");
        $stmt->execute([$productId]);

        return $stmt->fetchAll();
    }

    public function findVariantByOption(int $productId, int $colorId, int $sizeId): array|false
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM product_variants
            WHERE product_id = ? AND color_id = ? AND size_id = ?
            LIMIT 1
        ");
        $stmt->execute([$productId, $colorId, $sizeId]);

        return $stmt->fetch();
    }

    public function getAllVariants(string $keyword = ''): array
    {
        $sql = "
            SELECT
                pv.id,
                pv.product_id,
                pv.color_id,
                pv.size_id,
                pv.image,
                pv.price,
                pv.stock AS quantity,
                pv.stock,
                p.name AS product_name,
                c.name AS color_name,
                s.name AS size_name
            FROM product_variants pv
            INNER JOIN products p ON p.id = pv.product_id
            LEFT JOIN color c ON c.id = pv.color_id
            LEFT JOIN size s ON s.id = pv.size_id
        ";

        $params = [];

        if (trim($keyword) !== '') {
            $sql .= "
                WHERE p.name LIKE ? OR c.name LIKE ? OR s.name LIKE ?
            ";
            $search = '%' . trim($keyword) . '%';
            $params = [$search, $search, $search];
        }

        $sql .= " ORDER BY pv.id DESC ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getVariantById(int $id): array|false
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM product_variants
            WHERE id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function createVariant(array $data): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO product_variants(product_id, color_id, size_id, image, price, stock)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['product_id'],
            $data['color_id'],
            $data['size_id'],
            $data['image'],
            $data['price'],
            $data['quantity']
        ]);
    }

    public function updateVariant(int $id, array $data): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE product_variants
            SET product_id = ?, color_id = ?, size_id = ?, image = ?, price = ?, stock = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['product_id'],
            $data['color_id'],
            $data['size_id'],
            $data['image'],
            $data['price'],
            $data['quantity'],
            $id
        ]);
    }

    public function deleteVariant(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM product_variants WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getColors(): array
    {
        $stmt = $this->conn->query("
            SELECT id, name
            FROM color
            ORDER BY id ASC
        ");

        return $stmt->fetchAll();
    }

    public function getSizes(): array
    {
        $stmt = $this->conn->query("
            SELECT id, name
            FROM size
            ORDER BY id ASC
        ");

        return $stmt->fetchAll();
    }

    public function addCartItem(int $userId, int $variantId, int $quantity, int $price): bool
    {
        $check = $this->conn->prepare("
            SELECT id, quantity
            FROM cart
            WHERE user_id = ? AND variant_id = ?
            LIMIT 1
        ");
        $check->execute([$userId, $variantId]);
        $cart = $check->fetch();

        if ($cart) {
            $newQty = (int)$cart['quantity'] + $quantity;
            $stmt = $this->conn->prepare("
                UPDATE cart
                SET quantity = ?
                WHERE id = ?
            ");
            return $stmt->execute([$newQty, $cart['id']]);
        }

        $stmt = $this->conn->prepare("
            INSERT INTO cart(user_id, variant_id, quantity)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([$userId, $variantId, $quantity]);
    }

    public function getCartItemsByUserId(int $userId): array
    {
        $stmt = $this->conn->prepare("
            SELECT
                c.id,
                c.quantity,
                pv.price,
                pv.image,
                p.name AS product_name,
                cl.name AS color_name,
                sz.name AS size_name
            FROM cart c
            INNER JOIN product_variants pv ON pv.id = c.variant_id
            INNER JOIN products p ON p.id = pv.product_id
            LEFT JOIN color cl ON cl.id = pv.color_id
            LEFT JOIN size sz ON sz.id = pv.size_id
            WHERE c.user_id = ?
            ORDER BY c.id DESC
        ");
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function updateCartQuantity(int $cartId, int $quantity, int $userId): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE cart
            SET quantity = ?
            WHERE id = ? AND user_id = ?
        ");

        return $stmt->execute([$quantity, $cartId, $userId]);
    }

    public function deleteCartItem(int $cartId, int $userId): bool
    {
        $stmt = $this->conn->prepare("
            DELETE FROM cart
            WHERE id = ? AND user_id = ?
        ");

        return $stmt->execute([$cartId, $userId]);
    }
        // =========================
    // ORDER / CHECKOUT
    // =========================

    public function getCartItemsByIds($userId, $cartIds = [])
    {
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

    public function createOrder($data)
    {
        $sql = "INSERT INTO orders(
                    user_id,
                    total,
                    status,
                    payment_status,
                    online,
                    receiver_name,
                    receiver_phone,
                    receiver_address,
                    shipping_fee,
                    payment_method
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "idssssssds",
            $data['user_id'],
            $data['total'],
            $data['status'],
            $data['payment_status'],
            $data['online'],
            $data['receiver_name'],
            $data['receiver_phone'],
            $data['receiver_address'],
            $data['shipping_fee'],
            $data['payment_method']
        );

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }

        return false;
    }

    public function addOrderDetail($orderId, $variantId, $quantity, $price)
    {
        $sql = "INSERT INTO order_details(order_id, variant_id, quantity, price)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiii", $orderId, $variantId, $quantity, $price);
        return $stmt->execute();
    }

    public function removeManyCartItems($userId, $cartIds = [])
    {
        if (empty($cartIds)) {
            return false;
        }

        $cartIds = array_map('intval', $cartIds);
        $placeholders = implode(',', array_fill(0, count($cartIds), '?'));

        $types = 'i' . str_repeat('i', count($cartIds));
        $params = array_merge([$userId], $cartIds);

        $sql = "DELETE FROM cart
                WHERE user_id = ?
                  AND id IN ($placeholders)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

    public function updateVariantStock($variantId, $quantity)
    {
        $sql = "UPDATE product_variants
                SET stock = stock - ?
                WHERE id = ? AND stock >= ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $variantId, $quantity);
        return $stmt->execute();
    }

    public function getOrdersByUser($userId)
    {
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

    public function getAllOrders()
    {
        $sql = "SELECT o.*, u.username, u.email
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.id DESC";

        $result = $this->conn->query($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function getOrderById($id)
    {
        $sql = "SELECT o.*, u.username, u.email
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE o.id = ?
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function updateOrderStatusByAdmin($id, $status, $paymentStatus)
    {
        $sql = "UPDATE orders
                SET status = ?, payment_status = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $status, $paymentStatus, $id);
        return $stmt->execute();
    }

    public function getOrderDetails($orderId)
    {
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
                ORDER BY od.id ASC";

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
}