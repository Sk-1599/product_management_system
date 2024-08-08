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
            header('Location: index.php?page=admin_panel');
            exit;
        }
    }

    public function editProduct($id){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle form submission
            $item = $_POST['item'];
            $description = $_POST['description'];
            $address = $_POST['address'];
            $rating = $_POST['rating'];
            $price = $_POST['price'];
            $status = $_POST['status'];

            // Call the editProduct method from the model
            $this->productModel->editProduct($id, $item, $description, $price, $address, $rating, $status);

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
            header('Location: index.php?page=admin_panel');
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
