<?php

$currentpage = 'products';

require_once __DIR__ . '/../../database/app.php';
require_once __DIR__ . '/../../database/database.php';

require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/auth-admin.php';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';

?>

<main class="container py-4">
    <section class="mb-4">
        <h1 class="fw-bold mb-1">
            <?= $text['new_product'] ?>
        </h1>
        <p class="text-muted mb-0"><?= $text['create_product'] ?></p>   
    </section>

    <section class ="card">

        <form action="store.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label"><?= $text['product_name'] ?></label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label"><?= $text['product_description'] ?></label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label"><?= $text['price'] ?></label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label"><?= $text['category'] ?></label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label"><?= $text['image'] ?></label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary"><?= $text['create_product'] ?></button>
        </form>
     </section>
</main>
<?php
  require_once __DIR__ . '/../../includes/footer.php';
?>