<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-fluid {
            padding: 0;
        }
        .row {
            margin: 0;
        }
        .side-bar {
            background-color: #f8f9fa;
            height: 100vh;
            padding-top: 20px;
        }
        .main-content {
            padding: 20px;
        }
        .search-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .search-bar input {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 side-bar">
                <h2>Admin Panel</h2>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addItemModal">Add Item</button>
            </div>
            <div class="col-md-9 main-content">
                <div class="search-bar">
                    <input type="text" class="form-control" placeholder="Search...">
                    <button class="btn btn-primary">Search</button>
                </div>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Star Rating</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products) && is_array($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['description']) ?></td>
                                <td><?= htmlspecialchars($product['price']) ?></td>
                                <td><?= htmlspecialchars($product['rating']) ?></td>
                                <td><?= htmlspecialchars($product['address']) ?></td>
                                <td><?= htmlspecialchars($product['status']) ?></td>
                                <td>
                                    <button class="btn btn-warning" onclick="editItem(<?= $product['id'] ?>)">Edit</button>
                                    <button class="btn btn-danger" onclick="deleteItem(<?= $product['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="index.php?page=add_product" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" id="description" name="description" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" id="price" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="rating">Star Rating</label>
                            <input type="number" id="rating" name="rating" class="form-control" min="1" max="5" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <select id="address" name="address" class="form-control" required>
                                <option value="Mumbai">Mumbai</option>
                                <option value="Bangalore">Bangalore</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="Available">Available</option>
                                <option value="Out of Stock">Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function editItem(id) {
            // Implement the logic to edit item
        }

        function deleteItem(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                window.location.href = 'index.php?page=delete_product&id=' + id;
            }
        }
    </script>
</body>
</html>
