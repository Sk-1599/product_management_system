<?php
// Both functions work as same and produce same output but if any error arises then differences come.
// Example:
// If we don’t have a file named header.inc.php, then in the case of the include_once(), the output will be shown with warnings about missing file, but at least the output will be shown from the index.php file.
// In the case of the require_once(), if the file PHP file is missing, then a fatal error will arise and no output is shown and the execution halts.
include_once("controller/Controller.php");

$controller = new Controller();
$page = isset($_GET['page']) ? $_GET['page'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch ($page) {
	case 'dashboard':
		$controller->dashboardData();
		break;
	case 'login':
		$controller->handleLogin();
		break;
	case 'register':
		$controller->handleRegister();
		break;
	case 'showProductForm':
		$controller->showProductForm();
		break;
	case 'addProduct':
		$controller->addProduct();
		break;
	case 'editProduct':
		$controller->editProduct();
		break;
	case 'editProductForm':
		$controller->editProductForm();
		break;
	case 'delete_product':
		$controller->deleteProduct();
		break;
	default:
		$controller->showLogin();
		break;
}
