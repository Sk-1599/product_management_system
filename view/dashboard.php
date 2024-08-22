<?php include 'view/header.php' ?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Item Data</h6>
        </div>
        <div class="card-body">
            <?php if (isset($products) && is_iterable($products)) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Product Name
                                    <a href="#" class="sort" data-sort="product_name" data-order="asc">
                                        <i class="fa fa-arrow-up"></i>
                                    </a>
                                    <a href="#" class="sort" data-sort="product_name" data-order="desc">
                                        <i class="fa fa-arrow-down"></i>
                                    </a>
                                </th>
                                <th>SKU
                                    <a href="#" class="sort" data-sort="sku" data-order="asc">
                                        <i class="fa fa-arrow-up"></i>
                                    </a>
                                    <a href="#" class="sort" data-sort="sku" data-order="desc">
                                        <i class="fa fa-arrow-down"></i>
                                    </a>
                                </th>
                                <th>Category
                                    <a href="#" class="sort" data-sort="category" data-order="asc">
                                        <i class="fa fa-arrow-up"></i>
                                    </a>
                                    <a href="#" class="sort" data-sort="category" data-order="desc">
                                        <i class="fa fa-arrow-down"></i>
                                    </a>
                                </th>
                                <th>Shipping Days
                                    <!-- <a href="#" class="sort" data-sort="shipping_days" data-order="asc">
                                                        <i class="fa fa-arrow-up"></i>
                                                    </a>
                                                    <a href="#" class="sort" data-sort="shipping_days" data-order="desc">
                                                        <i class="fa fa-arrow-down"></i>
                                                    </a> -->
                                </th>
                                <th>Gender
                                    <a href="#" class="sort" data-sort="gender" data-order="asc">
                                        <i class="fa fa-arrow-up"></i>
                                    </a>
                                    <a href="#" class="sort" data-sort="gender" data-order="desc">
                                        <i class="fa fa-arrow-down"></i>
                                    </a>
                                </th>
                                <th>Inventory
                                    <!-- <a href="#" class="sort" data-sort="inventory" data-order="asc">
                                                        <i class="fa fa-arrow-up"></i>
                                                    </a>
                                                    <a href="#" class="sort" data-sort="inventory" data-order="desc">
                                                        <i class="fa fa-arrow-down"></i>
                                                    </a> -->
                                </th>
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
                                        <a href="index.php?page=editProductForm&id=<?= htmlspecialchars($product['id']); ?>" class="btn btn-primary my-1">Edit</a>
                                        <a href="index.php?page=deleteproduct&id=<?= htmlspecialchars($product['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
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
<!-- End of Main Content -->

<script>
    $(document).ready(function() {
        $('#searchForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the normal way

            $.ajax({
                type: 'POST',
                url: '?page=filterData', // Adjust the URL to match your routing
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                        window.location.href = '?page=showLogin';
                    } else {
                        $('#table').empty();
                        $.each(response, function(index, product) {
                            $('#table').append(`
                            <tr>
                                <td>${product.product_name}</td>
                                <td>${product.sku}</td>
                                <td>${product.category}</td>
                                <td>${product.shipping_days}</td>
                                <td>${product.gender}</td>
                                <td>${product.inventory}</td>
                                <td>
                                    <a href="index.php?page=editProductForm&id=${product.product_id}" class="btn btn-primary my-1">Edit</a>
                                    <a href="index.php?page=deleteproduct&id=${product.product_id}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                </td>
                            </tr>
                        `);
                        });
                    }
                },
                error: function() {
                    alert('Error occurred while fetching data.');
                }
            });
        });
    });
</script>

<div id="pagination" class="pagination justify-content-center"></div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sortLinks = document.querySelectorAll(".sort");

        sortLinks.forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault();

                const sortField = this.getAttribute("data-sort");
                const sortOrder = this.getAttribute("data-order");

                fetchSortedData(sortField, sortOrder);
            });
        });
    });

    function fetchSortedData(sortField, sortOrder) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "index.php?page=sortProducts", true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                document.getElementById("table").innerHTML = xhr.responseText;
            }
        };

        const data = JSON.stringify({
            sort_field: sortField,
            sort_order: sortOrder
        });
        xhr.send(data);
    }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#searchForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the normal way

            $.ajax({
                type: 'POST',
                url: '?page=filterData', // Adjust the URL to match your routing
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                        window.location.href = '?page=showLogin';
                    } else {
                        $('#table').empty();
                        $.each(response, function(index, product) {
                            $('#table').append(`
            <tr>
                <td>${product.product_name}</td>
                <td>${product.sku}</td>
                <td>${product.category}</td>
                <td>${product.shipping_days}</td>
                <td>${product.gender}</td>
                <td>${product.inventory}</td>
                <td>
                    <a href="index.php?page=editProductForm&id=<?= $product['id']; ?>" class="btn btn-primary my-1">Edit</a>
                </td>
            </tr>
        `);
                        });
                    }
                },
                error: function() {
                    alert('Error occurred while fetching data.');
                }
            });
        });
    });
</script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function loadProducts(page = 1) {
            const productName = $('input[name="product_name"]').val();
            const sku = $('input[name="sku"]').val();
            const category = $('input[name="category"]').val();

            $.ajax({
                url: 'index.php?page=getProducts',
                method: 'GET',
                data: {
                    pageno: page,
                    product_name: productName,
                    sku: sku,
                    category: category
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    const products = data.products;
                    const total_pages = data.total_pages;
                    const current_page = data.current_page;

                    let tableRows = '';
                    if (products.length > 0) {
                        products.forEach(function(product) {
                            tableRows += `
            <tr>
                <td>${product.product_name}</td>
                <td>${product.sku}</td>
                <td>${product.category}</td>
                <td>${product.shipping_days}</td>
                <td>${product.gender}</td>
                <td>${product.inventory}</td>
                <td>
                    <a href="index.php?page=editVendorProductForm&id=<?= $product['id']; ?>" class="btn btn-primary my-1">Edit</a>
                </td>
            </tr>
        `;
                        });
                    } else {
                        tableRows = '<tr><td colspan="7">No products available.</td></tr>';
                    }

                    $('#table').html(tableRows);

                    // Generate pagination UI
                    let paginationUI = '';
                    if (total_pages > 1) {
                        if (current_page > 1) {
                            paginationUI += `<a href="#" class="page-link" data-page="${current_page - 1}">Previous</a>`;
                        }
                        for (let i = 1; i <= total_pages; i++) {
                            paginationUI += `<a href="#" class="page-link ${i === current_page ? 'active' : ''}" data-page="${i}">${i}</a>`;
                        }
                        if (current_page < total_pages) {
                            paginationUI += `<a href="#" class="page-link" data-page="${current_page + 1}">Next</a>`;
                        }
                    }

                    $('#pagination').html(paginationUI);
                }
            });
        }

        // Load initial products
        loadProducts();

        // Handle pagination click
        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            loadProducts(page);
        });

        // Handle search form submission
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            loadProducts(1);
        });
    });
</script>


<?php include 'view/footer.php' ?>