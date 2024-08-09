<?php
require_once 'model/Model.php';
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
    
            $this->productModel->addProduct($name, $description, $price, $rating, $address, $status);
            header('Location: index.php?page=dashboard');

            exit();
        }
    }
    public function showProductForm()
    {
        include ('view/addProduct.php');
    }

    public function editProductForm(){

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $product_id = intval($_GET['id']);

                // Retrieve the book details from the model
                $product = $this->productModel->getProductById($product_id);
     
                if ($product) {
                    // Pass the book details to the view
                    include 'view/editProduct.php'; // Adjust path if necessary
                } else {
                    echo "Product not found.";
                }
            } else {
                echo "Invalid product ID.";
            }
        }
    }
    
    public function editProduct(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle form submission
            $name = $_POST['name'];
            $description = $_POST['description'];
            $address = $_POST['address'];
            $rating = $_POST['rating'];
            $price = $_POST['price'];
            $status = $_POST['status'];

            // Call the editProduct method from the model
            $this->productModel->editProduct($name, $description, $price, $address, $rating, $status);

            // Redirect to dashboard after editing
            header('Location: index.php?page=dashboard');
            exit();
        } else {
            // Display edit form
            // $item = $this->productModel->getItemById($id);
            require 'view/dashboard.php';
        }
    }

    public function deleteProduct()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->productModel->deleteProduct($id);
            header('Location: index.php?page=dashboard');
            exit;
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

    public function handleLogin()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (empty($email) || empty($password)) {
                $error = 'Please fill in all fields.';
            } else {
                $user = $this->userModel->loginUser($email, $password);
                if ($user) {
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
