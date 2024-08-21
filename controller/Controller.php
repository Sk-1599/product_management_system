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

    public function showVendorForm()
    {
        include 'view/showVendorForm.php';
    }


    public function registerVendor()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = isset($_POST['first_name']) ? $_POST['first_name'] : '';
            $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

            // Basic validation to check if fields are not empty
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
                echo "All fields are required.";
                return;
            }

            // Check if the email already exists
            if ($this->userModel->emailExists($email)) {
                echo "Error: User already exists. Please use a different email.";
                return;
            }

            // Password confirmation check
            if ($password !== $confirmPassword) {
                // Handle error - passwords don't match
                echo "Passwords do not match.";
                return;
            }

            // Hash the password before saving
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Assuming you have a user model to handle database operations
            $this->userModel->addVendor($firstName, $lastName, $email, $hashedPassword);

            // Redirect or show a success message
            header("Location: index.php?page=dashboard");
            exit();
        } else {
            include 'view/register.php';
        }
    }

    public function addProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $sku = $_POST['sku'];
            $category = $_POST['category'];
            $shipping_days = $_POST['shipping_days'];
            $gender = $_POST['gender'];
            $inventory = $_POST['inventory'];

            $productId = $this->productModel->addProduct($name, $sku, $category, $shipping_days, $gender, $inventory);

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
                include 'view/editProductForm.php'; // Pass the product details to the view
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
            $product_name = $_POST['product_name'];
            $sku = $_POST['sku'];
            $category = $_POST['category'];
            $shipping_days = $_POST['shipping_days'];
            $gender = $_POST['gender'];
            $inventory = $_POST['inventory'];

            // Update the product in the database
            $success = $this->productModel->editProduct($id, $product_name, $sku, $category, $shipping_days, $gender, $inventory);

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

    public function vendorDashboard()
    {
        $products = $this->productModel->getProducts();
        include 'view/vendorDashboard.php';
    }

    public function handleLogin()
    {
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

                    // Check if the user is an admin or a vendor
                    $isAdmin = $this->userModel->isAdmin($email);
                    // $isAdmin = '1';
                    if ($isAdmin === 1) {
                        echo "<script>
                        alert('Login successful. Redirecting to Admin Dashboard.');
                        window.location.href = 'index.php?page=dashboard';
                    </script>";
                    } else {
                        echo "<script>
                        alert('Login successful. Redirecting to Vendor Dashboard.');
                        window.location.href = 'index.php?page=vendorDashboard';
                    </script>";
                    }
                    exit;
                } else {
                    $error = 'Invalid email or password.';
                }
            }
        }

        include 'view/login.php';
    }


    public function filterData()
{
    $name_query = $_POST['product_name'] ?? '';
    $sku_query = $_POST['sku'] ?? '';
    $category_query = $_POST['category'] ?? '';
    $shipping_days_query = $_POST['shipping_days'] ?? '';
    $gender_query = $_POST['gender'] ?? '';
    $inventory_query = $_POST['inventory'] ?? '';

    $products = $this->productModel->searchProducts($name_query, $sku_query, $category_query, $shipping_days_query, $gender_query, $inventory_query);

    
    // Return the products data as JSON
    header('Content-Type: application/json');
    echo json_encode($products);
}



    public function editVendorProductForm()
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $product_id = intval($_GET['id']);

            // Retrieve the product details from the model
            $product = $this->productModel->getVendorProductById($product_id);

            if ($product) {
                include 'view/editVendorProductForm.php'; // Pass the product details to the view
            } else {
                echo "Product not found.";
            }
        } else {
            echo "Invalid product ID.";
        }
    }

    public function editVendorProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['product_id']) && isset($_POST['inventory'])) {
                $id = intval($_POST['product_id']);
                $inventory = $_POST['inventory'];

                // Update only the inventory in the database
                $success = $this->productModel->updateInventory($id, $inventory);

                if ($success) {
                    echo "<script>
                alert('Inventory updated successfully');
                window.location.href = 'index.php?page=vendorDashboard';
              </script>";
                    exit();
                } else {
                    echo "Error updating inventory.";
                }
            } else {
                echo "Product ID or Inventory not set.";
            }
        }
    }

    public function sortProducts()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['page']) && $_GET['page'] === 'sortProducts') {
            $data = json_decode(file_get_contents("php://input"), true);
            $sortField = $data['sort_field'];
            $sortOrder = $data['sort_order'];

            $products = $this->productModel->sortProducts($sortField, $sortOrder);

            foreach ($products as $product) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($product['product_name']) . "</td>";
                echo "<td>" . htmlspecialchars($product['sku']) . "</td>";
                echo "<td>" . htmlspecialchars($product['category']) . "</td>";
                echo "<td>" . htmlspecialchars($product['shipping_days']) . "</td>";
                echo "<td>" . htmlspecialchars($product['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($product['inventory']) . "</td>";
                echo "<td><a href='index.php?page=editVendorProductForm&id=" . htmlspecialchars($product['product_id']) . "' class='btn btn-primary my-1'>Edit</a></td>";
                echo "</tr>";
            }

            exit;
        }
    }
}
