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
                header('Location: index.php?page=login');
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
                    header('Location: view/admin_panel.php'); // Redirect to a protected page
                    exit;
                } else {
                    $error = 'Invalid email or password.';
                }
            }
        }

        include 'view/login.php';
    }

    public function showLogin()
    {
        include 'view/login.php';
    }

    public function showAdminPanel()
    {
        $products = $this->productModel->getProducts();
        include 'view/admin_panel.php';
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

    public function deleteProduct()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->productModel->deleteProduct($id);
            header('Location: index.php?page=admin_panel');
            exit;
        }
    }
}
