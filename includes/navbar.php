<?php

$allowedLanguages = ["en-us", "pt-br"];
if (isset($_GET['lang']) && in_array($_GET['lang'], $allowedLanguages)) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'en-us';
require_once __DIR__ . '/../lang/' . $lang . '.php';

?>

<header>
    <link href="../assets/css/style.css" rel="stylesheet">
    <nav class="navbar md:navbar-expand bg-1">
        <div class="container-fluid">
        <a href="<?= BASE_URL ?>/pages/index.php">Ouro-e-Linho</a>
            <button class="btn-icon navbar-toggler" type="button" data-bs-toggle="drawer" data-bs-target="#navbarDrawer"
                aria-controls="navbarDrawer" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" aria-hidden="true"></span>
            </button>
            <dialog class="drawer drawer-end" tabindex="-1" id="navbarDrawer" aria-labelledby="navbarDrawerLabel">
                <div class="drawer-header">
                    <h5 class="drawer-title" id="navbarDrawerLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="drawer" aria-label="Close"></button>
                </div>
                <div class="drawer-body mb-2 md:mb-0">
                    <ul class="nav navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?= BASE_URL ?>/pages/index.php"><?= $text['home']?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/pages/catalog/catalog.php"><?= $text['products']?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true"><?= $text['cart']?></a>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" type="button" data-bs-toggle="menu" aria-expanded="false">
                                <?= $text['profile']?>
                            </button>
                            <div class="menu">
                                <a class="menu-item" href="<?= BASE_URL ?>/pages/<?= isset($_SESSION['user_id']) ? '#' : 'login.php' ?>"><?= $text['account']?></a>
                                <a class="menu-item disabled" href="#"><?= $text['u-orders']?></a>
                                <a class="menu-item disabled" href="#"><?= $text['settings']?></a>
                                <hr class="menu-divider">
                                <a class="menu-item" href="<?= BASE_URL ?>/pages/logout.php"><?= $text['leave']?></a>
                            </div>
                        </li>
                    </ul>
                    <form class="hstack gap-2" role="search">
                        <input class="form-control w-100" type="search" placeholder="<?= $text['search']?>" aria-label="Search" />
                        <button class="btn-solid btn-icon theme-secondary" type="submit">
                            <svg class="bi" width="16" height="16">
                                <use href="#search"/>
                            </svg>
                        </button>
                    </form>

                    <a href="?lang=en-us" class="btn btn-sm btn-outline-secondary">
                        EN-US
                    </a>
                    <a href="?lang=pt-br" class="btn btn-sm btn-outline-secondary">
                        PT-BR
                    </a>  
                </div>

                
            </dialog>

            
        </div>
    </nav>
</header>