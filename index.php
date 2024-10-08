<?php
session_start();

include_once("controller/Controller.php");

$controller = new Controller();

$page = isset($_GET['page']) ? $_GET['page'] : '';


switch ($page) {
	case 'dashboard':
		$controller->dashboardData();
		break;
	case 'vendorDashboard':
		$controller->vendorDashboard();
		break;
	case 'editVendorProductForm':
		$controller->editVendorProductForm();
		break;
	case 'editVendorProduct':
		$controller->editVendorProduct();
		break;
	case 'showVendorForm':
		$controller->showVendorForm();
		break;
	case 'login':
		$controller->handleLogin();
		break;
	case 'showlogin':
		$controller->showLogin();
		break;
	case 'logout':
		$controller->handleLogout();
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
	case 'deleteproduct':
		$controller->deleteProduct();
		break;
	case 'filterData':
		$controller->filterData();
		break;
	case 'sortProducts':
		$controller->sortProducts();
		break;
	case 'getProducts':
		$controller->getProducts();
		break;
	default:
		$controller->showLogin();
		break;
}
