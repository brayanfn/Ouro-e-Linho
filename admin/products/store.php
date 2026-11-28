<?php

require_once __DIR__ . '/../../database/app.php';
require_once __DIR__ . '/../../database/database.php';

require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/auth-admin.php';

$redirect = BASE_URL . '/admin/products/index.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/products/create.php');
    exit;
}

// Recebe os dados do formulário
$name        = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$category    = $_POST['category'] ?? '';
$size        = $_POST['size'] ?? '';
$price       = $_POST['price'] ?? '';
$stock       = $_POST['stock'] ?? '';

// Valida os dados
if (
    $name === '' || $description === '' || $created_at === '' ||
    !is_numeric($price) || $price < 0 ||
    !ctype_digit((string) $stock) ||
    !in_array($size, ['PP', 'P', 'M', 'G', 'GG', 'XG', 'XXG'], true)
) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'msg' => 'Preencha todos os campos corretamente.'
    ];

    header('Location: ' . BASE_URL . '/admin/products/create.php');
    exit;
}

// Upload da imagem
$image = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

    $file = $_FILES['image'];

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 2 * 1024 * 1024) {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'msg' => 'Erro no envio da imagem. Tamanho máximo: 2 MB.'
        ];

        header('Location: ' . BASE_URL . '/admin/products/create.php');
        exit;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'msg' => 'Formato inválido. Use JPG, PNG ou WEBP.'
        ];

        header('Location: ' . BASE_URL . '/admin/products/create.php');
        exit;
    }

    $dir = __DIR__ . '/../../assets/uploads/';

    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];

    if (!move_uploaded_file($file['tmp_name'], $dir . $filename)) {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'msg' => 'Não foi possível salvar a imagem.'
        ];

        header('Location: ' . BASE_URL . '/admin/products/create.php');
        exit;
    }

    $image = BASE_URL . '/assets/uploads/' . $filename;
}

// Salva no banco de dados
$sql = "INSERT INTO products
        (name, description, created_at, price, image, stock, size)
        VALUES
        (:name, :description, :created_at, :price, :image, :stock, :size)";

$stmt = $con->prepare($sql);

$stmt->execute([
    ':name'        => $name,
    ':description' => $description,
    ':created_at'  => $created_at,
    ':price'       => $price,
    ':image'       => $image,
    ':stock'       => $stock,
    ':size'        => $size,
]);

$_SESSION['flash'] = [
    'type' => 'success',
    'msg' => 'Produto cadastrado com sucesso!'
];

header('Location: ' . $redirect);
exit;
