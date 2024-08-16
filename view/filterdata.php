<?php
if (!isset($products)) {
  $products = [];
}
?>
<tbody id="table">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?= htmlspecialchars($product['product_name']); ?></td>
                <td><?= htmlspecialchars($product['description']); ?></td>
                <td><?= htmlspecialchars($product['Price']); ?></td>
                <td><?= htmlspecialchars($product['rating']); ?></td>
                <td><?= htmlspecialchars($product['address']); ?></td>
                <td><?= htmlspecialchars($product['Status']); ?></td>
                <td>
                    <a href="index.php?page=editProductForm&id=<?= htmlspecialchars($product['id']); ?>" class="btn btn-primary my-1">Edit</a>
                    <a href="index.php?page=deleteproduct&id=<?= htmlspecialchars($product['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</tbody>