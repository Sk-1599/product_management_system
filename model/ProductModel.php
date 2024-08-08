<?php
require_once 'Database.php';

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function getProducts() {
        $stmt = $this->db->prepare('SELECT * FROM products');
        // echo "<script>console.log($stmt)</script>";
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProduct($name, $description, $price, $rating, $address, $status) {
        $stmt = $this->db->prepare('INSERT INTO products (name, description, price, rating, address, status) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$name, $description, $price, $rating, $address, $status]);
    }

    public function deleteProduct($id) {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
    }

    // Update an item
    public function editProduct($id, $item, $description, $price, $address, $rating, $status) {
        $query = 'UPDATE item_details SET name = :name, description = :description, price = :price,rating = :rating, address = :address, status = :status WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            'id' => $id,
            'item' => $item,
            'description' => $description,
            'price' => $price,
            'rating' => $rating,
            'address' => $address,
            'status' => $status
        ]);
        return $result;
    }

    // Implement editProduct method similarly
}
?>
