<?php

/** @var string $host Endereço do servidor */
$host = "localhost";

/** @var string $dbname Nome do banco de dados */
$dbname = "ouro-linho";

/** @var string $username Nome do usuário que acessa o banco */
$username = "root";

/** @var string $password Senha do usuário que acessa o banco */
$password = "";

try{
    $con = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $username,
    $password
    );

    $con ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e){

    die('Database connection error' . $e->getMessage());
}

?>