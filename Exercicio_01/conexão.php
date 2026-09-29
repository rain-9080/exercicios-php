<?php

$host = "db";
$dbname = "db";
$user = "renatoreidelas";
$password = "1234";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conectado ao banco com sucesso!";

} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}