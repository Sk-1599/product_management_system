<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ?page=showlogin');
    exit();
}
if (!isset($products)) {
    $products = [];
  }
?>
    <?php if (empty($products)):?>
        <tr>
            <td colspan="5">No products found.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <tr>
                
                <td><?= htmlspecialchars($product['product_name']); ?></td>
                <td><?= htmlspecialchars($product['description']); ?></td>
                <td><?= htmlspecialchars($product['price']); ?></td>
                <td><?= htmlspecialchars($product['rating']); ?></td>
                <td><?= htmlspecialchars($product['status']); ?></td>
                <td>
                    <a href="index.php?page=editProductForm&id=<?= htmlspecialchars($product['product_id']); ?>" class="btn btn-primary my-1">Edit</a>
                    <a href="index.php?page=deleteproduct&id=<?= htmlspecialchars($product['product_id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
   
 
 