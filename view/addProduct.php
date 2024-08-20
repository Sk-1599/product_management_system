<?php include 'view/header.php' ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <!-- Add Product Form -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add New Product</h6>
                    </div>
                    <div class="card-body">
                        <form action="index.php?page=addProduct" method="POST">
                            <div class="form-group">
                                <label for="name">Product name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="sku">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" required>
                            </div>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <textarea class="form-control" id="category" name="category" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="shipping">Shipping days</label>
                                <input type="number" step="0.01" class="form-control" id="shipping_days" name="shipping_days" required>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <input type="text" class="form-control" id="gender" name="gender" required>
                            </div>
                            <div class="form-group">
                                <label for="inventory">Inventory</label>
                                <input type="number" step="0.01" class="form-control" class="form-control" id="inventory" name="inventory" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->



<?php include 'view/footer.php' ?>