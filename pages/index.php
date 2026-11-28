<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

require_once __DIR__ . '/../database/app.php';  
require_once __DIR__ . '/../database/database.php'; 

$stmt = $con->prepare("SELECT DISTINCT category FROM products LIMIT 5");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

?>

    <section class="hero p-12 w-100 d-flex flex-column">
        <div class="hero__content">
            <span class="hero__subtitle"><?= $text['new_collection'] ?></span>
            <h2 class="hero__title py-4"><?= $text['the_basics_with_a_touch_of_tailoring'] ?></h2>
            <button class="hero__button px-10 py-4"><?= $text['shop_now'] ?></button>
        </div>
    </section>

    <?php foreach ($categories as $c => $category): ?>
        <?php
        $stmt = $con->prepare("SELECT * FROM products WHERE category = :categoria");
        $stmt->execute([':categoria' => $category]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if ($products): ?>
            <section class="product-carousel">
                <div id="carousel<?= $c ?>" class="carousel slide product-carousel__slider" data-bs-ends="stop">
                    <div class="product-carousel__header d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row justify-content-between align-items-center w-100">
                            <h5 class="product-carousel__title mb-0"><?= htmlspecialchars($category) ?></h5>
                            <div class="d-flex product-carousel__controls">
                                <p class="px-4"><?= $text['see_all'] ?></p>
                                <div>
                                    <button class="product-carousel__button btn-icon btn-sm" type="button" data-bs-target="#carousel<?= $c ?>" data-bs-slide="prev" style="--bs-carousel-control-icon-width: 1rem;">
                                        <span class="carousel-icon-prev" aria-hidden="true"></span>
                                        <span class="visually-hidden">Anterior</span>
                                    </button>
                                    <button class="product-carousel__button btn-icon btn-sm" type="button" data-bs-target="#carousel<?= $c ?>" data-bs-slide="next" style="--bs-carousel-control-icon-width: 1rem;">
                                        <span class="carousel-icon-next" aria-hidden="true"></span>
                                        <span class="visually-hidden">Próximo</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-inner">
                        <?php foreach ($products as $product => $p): ?>
                            <div class="carousel-item product-carousel__item">
                                <article class="card product-card">
                                    <img src="<?= htmlspecialchars($p['image']) ?>" class="card-img-top product-card__image" alt="<?= htmlspecialchars($p['name']) ?>">
                                    <div class="card-body product-card__body">
                                        <h4 class="card-title product-card__title"><?= htmlspecialchars($p['name']) ?></h4>
                                        <p class="card-text product-card__price">R$ <?= $p['price'] ?></p>
                                        <a href="" class="btn-solid theme-primary product-card__link"><?= $text['view_product'] ?></a>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>