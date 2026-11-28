<?php


include_once __DIR__ . '/../../database/app.php';
include_once __DIR__ . '/../../database/database.php';

include_once __DIR__ . '/../../includes/header.php';
include_once __DIR__ . '/../../includes/navbar.php';


$sql = "SELECT *
        FROM products
        ORDER BY name ASC";

$stmt = $con->prepare($sql);

$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<main>
    <ul class="nav nav__categories py-6">
        <li class="nav-item">
            <a class="nav-link" href="catalog.php"><?= $text['general'] ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="pants.php"><?= $text['pants'] ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="shirts.php"><?= $text['shirts']?></a>
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
    <div class="container text-center py-6" data-bs-theme="light">
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); column-gap: 10px; row-gap: 10px"> 
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <section class="card card-product h-100 py-6">
                        <?php if(!empty($product['image'])):?>
                                    <div class="card-product__img-wrapper">
                                        <img src="<?= BASE_URL ?>/assets/uploads/products/<?= htmlspecialchars($product['image']) ?>" class="card-img-top card__img">
                                    </div>
                                <?php else: ?>
                                    <div class="card-product__img-placeholder">
                                        <p class="card-text card__text">
                                            <?= $text['no_image_is_set_to_this_product'] ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                        <div class="card-body card-product__body">
                            <h5 class="card-title card-product__name">
                                <?= htmlspecialchars($product['name']) ?>
                            </h5>
                            <p class="card-text card-product__price">
                                R$ <?= htmlspecialchars($product['price']) ?>
                            </p>
                        </div>
                        <div class="card-action card-product__action">
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-solid theme-primary card-product__button">COMPRAR</a>
                        </div>
                    </section>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</main>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>