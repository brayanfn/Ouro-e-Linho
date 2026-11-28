<?php
require_once __DIR__ . '/../database/app.php';
require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$id = $_POST['add'] ?? null;

if ($id) {
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header('Location: ' . BASE_URL . '/pages/cart.php');
    exit;
}

$remove = $_POST['remove'] ?? null;
$removesingle = $_POST['removesingle'] ?? null;

if ($remove && isset($_SESSION['cart'][$remove])) {
    unset($_SESSION['cart'][$remove]);
    header('Location: ' . BASE_URL . '/pages/cart.php');
    exit;
}

if ($removesingle && isset($_SESSION['cart'][$removesingle])) {
    $_SESSION['cart'][$removesingle]--;


    if ($_SESSION['cart'][$removesingle] <= 0) {
        unset($_SESSION['cart'][$removesingle]);
    }

    header('Location: ' . BASE_URL . '/pages/cart.php');
    exit;
}

$products = [];

foreach ($_SESSION['cart'] as $id => $qtd) {
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $con->prepare($sql);
    $stmt->execute([':id' => $id]);

    $products[] = $stmt->fetch(PDO::FETCH_ASSOC);
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>
<main class="min-vh-100">
    <div class="w-100 d-flex flex-column p-4">

        <?php if (empty($products)): ?>
            <div class="text-center py-5">
                <h3><?= $text['no_product_found'] ?></h3>
                <a href="<?= BASE_URL ?>/pages/products.php" class="btn-solid theme-primary mt-3">
                    <?= $text['go_to_products'] ?>
                </a>
            </div>

        <?php else: ?>

            <?php foreach ($products as $product) : ?>

                <div class="w-100 flex-row">
                    <div class="card cart-card d-flex flex-row mt-4">
                        <?php if (!empty($product['image'])): ?>
                            <div class="cart-card__image-wrapper">
                                <img src="<?= htmlspecialchars($product['image']) ?>" class="cart-card__image" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                        <?php else: ?>
                            <div class="cart-card__image-placeholder">
                                <p class="card-text cart-card__text">
                                    <?= $text['no_image_set_to_this_product'] ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div class="card-body cart-card__body">
                            <h5 class="card-title cart-card__title">
                                <?= htmlspecialchars($product['name']) ?>
                            </h5>
                            <p class="card-text cart-card__price">
                                R$ <?= htmlspecialchars($product['price']) ?>
                            </p>
                            <p>
                                <?= $text['quantity'] ?> : <?= $_SESSION['cart'][$product['id']] ?>
                            </p>
                            <p>
                                <?= $text['total_price'] ?> : R$ <?= number_format($product['price'] * $_SESSION['cart'][$product['id']], 2, ',', '.') ?>
                            </p>

                            <div class="cart-card__actions">

                                <div>
                                    <a href="product.php?id=<?= $product['id'] ?>" class="btn-solid theme-secondary w-100 text-nowrap">
                                        <?= $text['details'] ?>
                                    </a>
                                </div>

                                <div class="d-flex gap-2 mt-3">

                                    <form action="<?= BASE_URL ?>/pages/cart.php" method="POST" class="flex-grow-1 w-100">
                                        <input type="hidden" name="remove" value="<?= $product['id'] ?>">
                                        <button type="submit" class="btn-solid theme-danger w-100 text-nowrap cart-card__link--delete">
                                            <?= $text['delete'] ?>
                                        </button>
                                    </form>

                                    <form action="<?= BASE_URL ?>/pages/cart.php" method="POST">
                                        <input type="hidden" name="add" value="<?= $product['id'] ?>">
                                        <button type="submit" class="btn-solid theme-primary cart-card__link--add"> + </button>
                                    </form>

                                    <form action="<?= BASE_URL ?>/pages/cart.php" method="POST">
                                        <input type="hidden" name="removesingle" value="<?= $product['id'] ?>">
                                        <button type="submit" class="btn-solid theme-primary cart-card__link--remove"> - </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>