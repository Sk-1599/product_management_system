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

    // Implement editProduct method similarly
}
?>
