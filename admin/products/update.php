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

// Recebe os dados do formulário
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

// Para onde voltar se algo der errado
$editUrl = BASE_URL . '/admin/products/edit.php?id=' . $id;

// Valida os dados
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

// Busca o produto atual (precisamos da imagem antiga)
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

// Por padrão, mantém a imagem antiga
$image    = $current['image'];
$newImage = false;

// Upload da nova imagem (só se o usuário enviou uma)
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

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'msg' => 'Formato inválido. Use JPG, PNG ou WEBP.'
        ];

        header('Location: ' . $editUrl);
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

        header('Location: ' . $editUrl);
        exit;
    }

    $image    = BASE_URL . '/assets/uploads/' . $filename;
    $newImage = true;
}

// Atualiza no banco de dados
$sql = "UPDATE products
        SET name = :name,
            description = :description,
            category = :category,
            price = :price,
            image = :image,
            stock = :stock,
            size = :size
        WHERE id = :id";

$stmt = $con->prepare($sql);

$stmt->execute([
    ':name'        => $name,
    ':description' => $description,
    ':category'    => $category,
    ':price'       => $price,
    ':image'       => $image,
    ':stock'       => $stock,
    ':size'        => $size,
    ':id'          => $id,
]);


if ($newImage && strpos($current['image'], BASE_URL . '/assets/uploads/') === 0) {
    $oldFile = __DIR__ . '/../../assets/uploads/' . basename($current['image']);

    if (is_file($oldFile)) {
        unlink($oldFile);
    }
}

$_SESSION['flash'] = [
    'type' => 'success',
    'msg' => 'Produto atualizado com sucesso!'
];

header('Location: ' . $redirect);
exit;

?>