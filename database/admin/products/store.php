<?php

require_once __DIR__ . '/../../database/app.php';
require_once __DIR__ . '/../../database/database.php';

require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/auth-admin.php';

/** Para onde o usuário vai depois de salvar (troque se a sua listagem for outra página) */
$redirect = BASE_URL . '/index.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/products/create.php');
    exit;
}

$name        = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$size        = $_POST['size'] ?? '';
$price       = $_POST['price'] ?? '';
$stock       = $_POST['stock'] ?? '';

if (
    $name === '' || $description === '' ||
    !is_numeric($price) || $price < 0 ||
    !ctype_digit((string) $stock) ||
    !in_array($size, ['PP', 'P', 'M', 'G', 'GG', 'XG'], true)
) {
    $_SESSION['erro'] = 'Preencha todos os campos corretamente.';
    header('Location: ' . BASE_URL . '/admin/products/create.php');
    exit;
}
