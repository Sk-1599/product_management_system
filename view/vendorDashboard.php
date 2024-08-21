<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php?page=showlogin');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="view/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="view/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="view/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Kale Mart <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Divider -->
            <hr class="sidebar-divider">

        </ul>
        <!-- End of Sidebar -->



        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->
                    <form id="searchForm" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" name="product_name" class="form-control bg-light border-primary small ml-2" placeholder="Search product name ..." aria-label="Search" aria-describedby="basic-addon2">
                            <input type="text" name="sku" class="form-control bg-light border-primary small ml-2" placeholder="Search SKU ..." aria-label="Search" aria-describedby="basic-addon2">
                            <input type="text" name="category" class="form-control bg-light border-primary small ml-2" placeholder="Search category ..." aria-label="Search" aria-describedby="basic-addon2">
                            <button class="rounded btn btn-primary mx-1" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Welcome! <?php echo htmlspecialchars($_SESSION['firstname']) ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="?page=logout" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400" Alert></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->




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
                                                        <a href="index.php?page=editVendorProductForm&id=<?= htmlspecialchars($product['id']); ?>" class="btn btn-primary my-1">Edit</a>
                                                        <!-- <a href="index.php?page=deleteproduct&id=<?= htmlspecialchars($product['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a> -->
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




                <?php include 'view/footer.php' ?>