<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php?page=showlogin');
    exit();
}
?><h1>Welcome home</h1>