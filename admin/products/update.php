<?php 
require_once __DIR__ . '/../../database/app.php';
require_once __DIR__ . '/../../database/database.php';

require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/auth-admin.php';

$redirect = BASE_URL . '/admin/products/index.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $redirect);
    exit;
}

$id          = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
$name        = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$category    = $_POST['category'] ?? '';
$size        = $_POST['size'] ?? '';
$price       = $_POST['price'] ?? '';
$stock       = $_POST['stock'] ?? ''; 

if (!$id) {
    header('Location: ' . $redirect);
    exit;
}
?>