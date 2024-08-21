<?php include 'view/header.php' ?>
<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ?page=showlogin');
    exit();
}
if (!isset($products)) {
    $products = [];
  }
?>
    
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Item Data</h6>
    </div>
    <div class="card-body">
        <?php if (!empty($products)): ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Shipping Days</th>
                            <th>Gender</th>
                            <th>Inventory</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="table">
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td><?= htmlspecialchars($product['product_name']); ?></td>
                                <td><?= htmlspecialchars($product['sku']); ?></td>
                                <td><?= htmlspecialchars($product['category']); ?></td>
                                <td><?= htmlspecialchars($product['shipping_days']); ?></td>
                                <td><?= htmlspecialchars($product['gender']); ?></td>
                                <td><?= htmlspecialchars($product['inventory']); ?></td>
                                <td>
                                    <a href="index.php?page=editProductForm&id=<?= htmlspecialchars($product['product_id']); ?>" class="btn btn-primary my-1">Edit</a>
                                    <a href="index.php?page=deleteproduct&id=<?= htmlspecialchars($product['product_id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No products available.</p>
            <?php endif; ?>
            </div>
    </div>
</div>

</div>
<!-- /.container-fluid -->

</div>
   
 
    <?php include 'view/footer.php' ?>