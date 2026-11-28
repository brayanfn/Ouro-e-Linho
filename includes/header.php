<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/app.php';

$lang = $_SESSION['lang'] ?? 'pt-br';

if (isset($_GET['lang'])) {
    $allowedLanguages = ['pt-br' , 'en-us'];

    if (in_array($_GET['lang'], $allowedLanguages)) {
        $_SESSION['lang'] = $_GET['lang'];
        $lang = $_GET['lang'];
    }
}

require_once __DIR__ . '/../lang/' . $lang . '.php';
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ouro e Linho</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body data-bs-theme="light">
