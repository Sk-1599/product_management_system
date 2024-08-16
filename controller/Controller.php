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
        // Start the  if it hasn't already been started
        // Unset all of the session variables
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_unset();
        session_destroy(); // Destroy the session
        error_log("User logged out and session destroyed.");
        header('Location: ?page=showlogin'); // Redirect to the login page

        exit();
    }

    public function handleLogin()
    {
        // if (session_status() == PHP_SESSION_NONE) {
        //     session_start();
        // }

        $error = '';

       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve the email and password from the POST request
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (empty($email) || empty($password)) {
                $error = 'Please fill in all fields.';
            } else {
                // Call the loginUser function from the userModel
                $user = $this->userModel->loginUser($email, $password);
                if ($user) {
                    // Store user data in session variables
                    $_SESSION['loggedin'] = true;
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


    public function filterdata()
    {
        $name_query = $_POST['product_name'] ?? '';
        $price_query = $_POST['price'] ?? '';
        $status_query = $_POST['status'] ?? '';

        $products = $this->productModel->searchProducts($name_query, $price_query, $status_query);

        // Check if it's an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            include_once 'view/filterdata.php'; // Only include the part of the view that displays the products
        } else {
            // Fallback to the regular way if not an AJAX request
            include_once 'view/filterdata.php';
        }
    }
}
