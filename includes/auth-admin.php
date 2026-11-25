<?php
if ($user['role'] !== 'admin') {
    header('Location: ' .BASE_URL. '/pages/index.php');
    exit;
}
