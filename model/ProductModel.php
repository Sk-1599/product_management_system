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
                    'sku' => '',
                    'category' => '',
                    'shipping_days' => '',
                    'gender' => '',
                    'inventory' => ''
                ];
            }

            $attributeName = strtolower($row['attribute_name']);
            $attributeMap = [
                'sku' => 'sku',
                'category' => 'category',
                'shipping days' => 'shipping_days',
                'gender' => 'gender',
                'inventory' => 'inventory'
            ];

            // Set the attribute value if it exists in the map
            if (array_key_exists($attributeName, $attributeMap)) {
                $products[$productId][$attributeMap[$attributeName]] = $row['value'];
            }
        }

        return $products;
    }


    public function addProduct($name, $sku, $category, $shipping_days, $gender, $inventory)
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

        // Define attribute IDs for each attribute
        $attributes = [
            1 => $sku,            // SKU
            2 => $category,       // Category
            3 => $shipping_days,  // Shipping Days
            4 => $gender,         // Gender
            5 => $inventory       // Inventory
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
    public function searchProducts($name_query, $price_query, $status_query)
    {
        $sql = "SELECT p.product_id, p.product_name,
            MAX(CASE WHEN at.attribute_name = 'Description' THEN pv.value END) as description,
            MAX(CASE WHEN at.attribute_name = 'Price' THEN pv.value END) as price,
            MAX(CASE WHEN at.attribute_name = 'Rating' THEN pv.value END) as rating,
            MAX(CASE WHEN at.attribute_name = 'Status' THEN pv.value END) as status
        FROM products p
        LEFT JOIN product_values pv ON p.product_id = pv.product_id
        LEFT JOIN attribute_table at ON pv.attribute_id = at.attribute_id
        LEFT JOIN product_values pv_description ON p.product_id = pv_description.product_id
        LEFT JOIN attribute_table at_description ON pv_description.attribute_id = at_description.attribute_id AND at_description.attribute_name = 'description'
        LEFT JOIN product_values pv_price ON p.product_id = pv_price.product_id
        LEFT JOIN attribute_table at_price ON pv_price.attribute_id = at_price.attribute_id AND at_price.attribute_name = 'price'
        LEFT JOIN product_values pv_status ON p.product_id = pv_status.product_id
        LEFT JOIN attribute_table at_status ON pv_status.attribute_id = at_status.attribute_id AND at_status.attribute_name = 'status'
        WHERE 1=1";

        $params = [];

        if (!empty($name_query)) {
            $sql .= " AND p.product_name LIKE ?";
            $params[] = "%$name_query%";
        }

        if (!empty($price_query)) {
            $sql .= " AND p.product_id IN (
                        SELECT p2.product_id
                        FROM products p2
                        LEFT JOIN product_values pv2 ON p2.product_id = pv2.product_id
                        LEFT JOIN attribute_table at2 ON pv2.attribute_id = at2.attribute_id
                        WHERE at2.attribute_name = 'price' AND pv2.value LIKE ?
                    )";
            $params[] = "%$price_query%";
        }

        if (!empty($status_query)) {
            $sql .= " AND p.product_id IN (
                        SELECT p3.product_id
                        FROM products p3
                        LEFT JOIN product_values pv3 ON p3.product_id = pv3.product_id
                        LEFT JOIN attribute_table at3 ON pv3.attribute_id = at3.attribute_id
                        WHERE at3.attribute_name = 'status' AND pv3.value LIKE ?
                    )";
            $params[] = "%$status_query%";
        }

        $sql .= " GROUP BY p.product_id, p.product_name";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
