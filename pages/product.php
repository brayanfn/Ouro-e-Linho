<?php

require_once __DIR__ . '/../database/app.php';
require_once __DIR__ . '/../database/database.php';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

$id = $_GET['id'];

$sql = "SELECT *
        FROM products
        WHERE id = :id";

$stmt = $con->prepare($sql);
$stmt->execute([
    ':id' => $id
]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

$sqlRelated = "SELECT * FROM products WHERE category = :category AND id != :id LIMIT 4";
$stmtRelated = $con->prepare($sqlRelated);
$stmtRelated->execute([
    ':category' => $product['category'],
    ':id' => $id
]);
$related = $stmtRelated->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <ul class="nav nav__categories py-6">
        <li class="nav-item">
            <a class="nav-link" href="catalog.php"><?= $text['general'] ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="catalog.php?category=calca"><?= $text['pants'] ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="catalog.php?category=camiseta"><?= $text['shirts']?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><?= $text['jackets']?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><?= $text['coats']?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><?= $text['sweatshirts']?></a>
        </li>
    </ul>
    <div class="container product-detail">
        <section class="product-detail__visual">
            <div class="product-detail__visual-content">
                <?php if(!empty($product['image'])):?>
                    <div class="product-detail__img-wrapper py-8">
                        <img src="<?= BASE_URL ?>/assets/uploads/products/<?= htmlspecialchars($product['image']) ?>" class="card-img-top card__img">
                    </div>
                    <?php else: ?>
                        <div class="product-detail__img-placeholder py-8">
                            <p class="product-detail__text">
                                <?= $text['no_image_is_set_to_this_product'] ?>
                            </p>
                        </div>
                    <?php endif; ?>
            </div>
        </section>
        <section class="hero-section">
            <div class="container text-center py-6 hero-container">
                <div class="product-detail__content">
                    <span class="product-detail__title py-2">
                        <?= htmlspecialchars($product['name']) ?>
                    </span>
                    <p class="product-detail__price py-2">
                        R$ <?= htmlspecialchars($product['price']) ?>
                    </p>
                    <div class="product-detail__action d-flex flex-column gap-3">
                        <div class="product-detail__select">
                            <label for="size" class="form-label"><?=php $text['size'] ?></label>
                            <select class="form-control" type="select" id="size" name = "size" min = "0" step="0.001" required>
                                <option value="" selected disabled><?=php $text['select_a_size'] ?></option>
                                <option value="P">P</option>
                                <option value="M">M</option>
                                <option value="G">g</option>
                            </select>
                        </div>
                        <a class="product-detail__cart btn btn-solid theme-primary" href="#">
                            <?=php $text['put_in_cart'] ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <section class="container product-detail__body py-8">
        <div class="product-detail__description">
            <p>
                <?= htmlspecialchars($product['description']) ?>
            </p>
        </div>
        <div class="container product-detail__related-products">
            <h2> Produtos Semelhantes </h2>
            <div class="container text-center py-6" data-bs-theme="light">
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); column-gap: 10px; row-gap: 10px">
                    <?php foreach ($related as $item): ?>
                        <section class="card card-product h-100 py-6">
                            <?php if(!empty($item['image'])):?>
                                <div class="card-product__img-wrapper">
                                    <img src="<?= BASE_URL ?>/assets/uploads/products/<?= htmlspecialchars($item['image']) ?>" class="card-img-top card__img">
                                </div>
                                <?php else: ?>
                                    <div class="card-product__img-placeholder">
                                        <p class="card-text card-product__text">
                                            No Image is set to this product
                                        </p>
                                    </div>
                                <?php endif; ?>
                            <div class="card-body card-product__body">
                                <h5 class="card-title card__name">
                                    <?= htmlspecialchars($item['name']) ?>
                                </h5>
                                <p class="card-text card-product__price">
                                    R$ <?= htmlspecialchars($item['price']) ?>
                                </p>
                            </div>
                            <div class="card-action card-product__actions">
                                <a href="product.php?id=<?= $item['id'] ?>" class="btn btn-solid theme-primary card-product__button">
                                <?=php $text['buy'] ?>
                                </a>
                            </div>
                        </section>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php' ?>