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

$editUrl = BASE_URL . '/admin/products/edit.php?id=' . $id;

if (
    $name === '' || $description === '' ||
    !is_numeric($price) || $price < 0 ||
    !ctype_digit((string) $stock) ||
    !in_array($size, ['PP', 'P', 'M', 'G', 'GG', 'XG'], true) ||
    !in_array($category, ['camiseta', 'calca', 'jaqueta'], true)
) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'msg' => 'Preencha todos os campos corretamente.'
    ];

    header('Location: ' . $editUrl);
    exit;
}

$stmt = $con->prepare("SELECT image FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$current = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$current) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'msg' => 'Produto não encontrado.'
    ];

    header('Location: ' . $redirect);
    exit;
}

$image    = $current['image'];
$newImage = false;

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

        header('Location: ' . $editUrl);
        exit;
    }
}
?>