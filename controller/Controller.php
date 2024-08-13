<?php
require_once 'model/UserModel.php';
require_once 'model/ProductModel.php';

class Controller
{
    private $userModel;
    private $productModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
    }

    public function dashboardData()
    {
        $products = $this->productModel->getProducts();
        include 'view/dashboard.php';
    }

    public function showLogin()
    {
        include 'view/login.php';
    }

    public function addProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $rating = $_POST['rating'];
            $address = $_POST['address'];
            $status = $_POST['status'];

            $productId = $this->productModel->addProduct($name, $description, $price, $rating, $address, $status);

            if ($productId) {
                echo "<script>
                    alert('New Product added successfully');
                    window.location.href = 'index.php?page=dashboard';
                </script>";
                exit();
            } else {
                echo "Error: Unable to add product.";
            }
        }
    }

    public function showProductForm()
    {
        include('view/addProduct.php');
    }

    public function editProductForm()
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $product_id = intval($_GET['id']);

            // Retrieve the product details from the model
            $product = $this->productModel->getProductById($product_id);

            if ($product) {
                include 'view/editProduct.php'; // Pass the product details to the view
            } else {
                echo "Product not found.";
            }
        } else {
            echo "Invalid product ID.";
        }
    }

    public function editProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $rating = $_POST['rating'];
            $address = $_POST['address'];
            $status = $_POST['status'];

            // Update the product in the database
            $success = $this->productModel->editProduct($id, $name, $description, $price, $rating, $address, $status);

            if ($success) {
                echo "<script>
                    alert('Product updated successfully');
                    window.location.href = 'index.php?page=dashboard';
                  </script>";
                exit();
            } else {
                echo "Error updating product.";
            }
        }
    }

    public function deleteProduct()
    {
        try {
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $id = intval($_GET['id']);
                $deleted = $this->productModel->deleteProduct($id);

                if ($deleted) {
                    header('Location: index.php?page=dashboard&msg=Product Deleted');
                    exit;
                } else {
                    echo "Product not found or could not be deleted.";
                }
            } else {
                echo "Invalid product ID.";
            }
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }



    public function handleRegister()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirm_password)) {
                $error = 'Please fill in all fields.';
            } elseif ($password !== $confirm_password) {
                $error = 'Passwords do not match.';
            } else {
                $this->userModel->registerUser($firstname, $lastname, $email, $password);
                echo "<script>
                        alert('Registered successfully');
                        window.location.href = 'index.php?page=login';
                        </script>";
                exit;
            }
        }

        include 'view/register.php';
    }

    public function handleLogout()
    {
        session_start();
        session_unset();
        session_destroy(); // Destroy the session
        header('Location: ?page=login'); // Redirect to the login page
        exit();
    }

    public function handleLogin()
    {
        $error = '';

        session_start(); // Start the session at the beginning of the script

        // Assuming you have form data posted for username and password

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (empty($email) || empty($password)) {
                $error = 'Please fill in all fields.';
            } else {
                $user = $this->userModel->loginUser($email, $password);
                if ($user) {
                    // Store user data in session variables
                    $_SESSION['firstname'] = $user['firstname'];
                    $_SESSION['lastname'] = $user['lastname'];

                    echo "<script>
                    alert('Login successful');
                    window.location.href = 'index.php?page=dashboard';
                    </script>";
                    exit;
                } else {
                    $error = 'Invalid email or password.';
                }
            }
        }

        include 'view/login.php';
    }
}
