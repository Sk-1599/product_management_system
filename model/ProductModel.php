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
        $query = '
            SELECT p.product_id, p.product_name, a.attribute_name, v.value
            FROM products p
            LEFT JOIN product_values v ON p.product_id = v.product_id
            LEFT JOIN attribute_table a ON v.attribute_id = a.attribute_id
            ORDER BY p.product_id
        ';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $products = [];
        foreach ($results as $row) {
            $productId = $row['product_id'];
            if (!isset($products[$productId])) {
                $products[$productId] = [
                    'id' => $productId,
                    'product_name' => $row['product_name'],
                    'description' => '',
                    'price' => '',
                    'rating' => '',
                    'address' => '',
                    'status' => ''
                ];
            }
            $attributeName = strtolower($row['attribute_name']); // Ensure the attribute name is in lowercase
            if (array_key_exists($attributeName, $products[$productId])) {
                $products[$productId][$attributeName] = $row['value'];
            }
        }

        return $products;
    }

    public function addProduct($name, $description, $price, $rating, $address, $status)
    {
        // Insert into products table
        $stmt = $this->db->prepare('INSERT INTO products (product_name) VALUES (:name)');
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        if ($stmt === false) {
            die("Error: " . $this->db->errorInfo()[2]);
        }

        // Get the last inserted product ID
        $productId = $this->db->lastInsertId();

        // Insert into value_table for each attribute
        $attributes = [
            1 => $description, // Description
            2 => $price,       // Price
            3 => $rating,      // Rating
            4 => $address,     // Address
            5 => $status       // Status
        ];

        foreach ($attributes as $attributeId => $value) {
            $stmt = $this->db->prepare('INSERT INTO product_values (product_id, attribute_id, value) VALUES (:product_id, :attribute_id, :value)');
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':attribute_id', $attributeId);
            $stmt->bindParam(':value', $value);
            $stmt->execute();

            if ($stmt === false) {
                die("Error: " . $this->db->errorInfo()[2]);
            }
        }

        return $productId;
    }


    public function deleteProduct($id)
    {
        try {
            $this->db->beginTransaction();

            // First, delete related rows from the product_values table
            $stmt = $this->db->prepare('DELETE FROM product_values WHERE product_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Then, delete the product from the products table
            $stmt = $this->db->prepare('DELETE FROM products WHERE product_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();

            return $stmt->rowCount() > 0; // Return true if a row was deleted, otherwise false
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception('Error deleting product: ' . $e->getMessage());
        }
    }



    // Update an item
    public function editProduct($id, $name, $description, $price, $rating, $address, $status)
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE products SET product_name = :name WHERE product_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $sql = "UPDATE product_values SET value = :value WHERE product_id = :id AND attribute_id = :attr_id";
            $stmt = $this->db->prepare($sql);

            $attributes = [
                1 => $description,
                2 => $price,
                3 => $rating,
                4 => $address,
                5 => $status
            ];

            foreach ($attributes as $attr_id => $value) {
                $stmt->bindParam(':value', $value);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':attr_id', $attr_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getProductById($id)
    {
        $sql = "SELECT p.product_id, p.product_name, pv.attribute_id, pv.value
            FROM products p
            JOIN product_values pv ON p.product_id = pv.product_id
            WHERE p.product_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $product = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (empty($product)) {
                $product['id'] = $row['product_id'];
                $product['name'] = $row['product_name'];
            }
            switch ($row['attribute_id']) {
                case 1:
                    $product['description'] = $row['value'];
                    break;
                case 2:
                    $product['price'] = $row['value'];
                    break;
                case 3:
                    $product['rating'] = $row['value'];
                    break;
                case 4:
                    $product['address'] = $row['value'];
                    break;
                case 5:
                    $product['status'] = $row['value'];
                    break;
            }
        }
        return $product;
    }

    // Implement editProduct method similarly
}
