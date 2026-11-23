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
    <section class="card">
        <div class="card-body">

        <form actioon="update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
            <div class="mb-3">
                <label for="name" class="form-label"><?= $text['product_name'] ?></label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label"><?= $text['product_description'] ?></label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="price" class="form-label"><?= $text['price'] ?></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="create_at" class="form-label"><?= $text['create_at'] ?></label>
                        <input type="text" class="form-control" id="create_at" name="create_at" value="<?= htmlspecialchars($product['create_at']) ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for size class="form=label"><?= $text['size'] ?></label>
                        <select class="form-control" id="size" name="size" required>
                            <?php foreach ($sizes as $size): ?>
                                <option value="<?= $size ?>" <?= $product['size'] === $size ? 'selected' : '' ?>><?= $size ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label"><?= $text['product_image'] ?></label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                </div>
            </div>
        </form>
    </section>  
</main>
<?php
  require_once __DIR__ . '/../../includes/footer.php';
?>
