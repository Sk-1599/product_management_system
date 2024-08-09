<?php
require_once 'Database.php';

class ProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function getProducts()
    {
        $stmt = $this->db->prepare('SELECT * FROM products');
        // echo "<script>console.log($stmt)</script>";
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProduct($name, $description, $price, $rating, $address, $status)
    {
        $stmt = $this->db->prepare('INSERT INTO products (name, description, price, rating, address, status) VALUES (?, ?, ?, ?, ?, ?)');
        $success = $stmt->execute([$name, $description, $price, $rating, $address, $status]);

        if ($stmt === false) {
            die("Error: " . $this->db->errorInfo()[2]);
        }

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);

        if ($success) {
            echo "<script>
                    alert('New Book added successfully');
                    window.location.href ='?page=dashboard';
                </script>";
            exit(); // Ensure to call exit after the redirect
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    }

    public function deleteProduct($id)
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
    }

    // Update an item
    public function editProduct($name, $description, $price, $address, $rating, $status)
    {
        $query = 'UPDATE products SET name = :name, description = :description, price = :price,rating = :rating, address = :address, status = :status WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'rating' => $rating,
            'address' => $address,
            'status' => $status
        ]);
        return $result;
    }

    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Implement editProduct method similarly
}
