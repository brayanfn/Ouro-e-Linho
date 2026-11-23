<?php

$currentpage = 'products';

require_once __DIR__ . '/../../database/app.php';
require_once __DIR__ . '/../../database/database.php';

require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/auth-admin.php';

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$stmt = $con->prepare("SELECT * FROM products WHERE id = :id"); //Procura pelo produto que será editado pelo id
$stmt->execute([':id' => $id]); //executa o id (id vira uma variavél)
$product = $stmt->fetch(PDO::FETCH_ASSOC); //pega o produto que foi encontrado

if (! $product){ //Se o produto não for encontrado, aparece a menssagem de erro e redireciona para a página inicial
    header ('product not found: '. BASE_URL . '/index.php');
    exit;
}

/** Mensagem de erro vinda do update.php (se houver) */
$erro = $_SESSION['erro'] ?? null;
unset($_SESSION['erro']);

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

?>
<main class="container py-4">
    <section class="mb-4">
        <h1 class="fw-bold mb-1">
            <?= $text['edit_product'] ?>
        </h1>
        <p class="text-muted mb-0"><?= $text['edit_product_description'] ?></p>
    </section>  

    <?php if ($erro): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>
</main>
<?php
  require_once __DIR__ . '/../../includes/footer.php';
?>
