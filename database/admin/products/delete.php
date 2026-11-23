<?php

require_once __DIR__ . '/../../database/app.php';
require_once __DIR__ . '/../../database/database.php';

require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/auth-admin.php';

$redirect = BASE_URL . '/index.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $redirect);
    exit;
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: ' . $redirect);
    exit;
}

$stmt = $con->prepare("SELECT image FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product) {
    $stmt = $con->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if (strpos($product['image'], BASE_URL . '/assets/uploads/') === 0) {
        $file = __DIR__ . '/../../assets/uploads/' . basename($product['image']);

        if (is_file($file)) {
            unlink($file);
        }
    }
}

header('Location: ' . $redirect);
exit;
