<?php

session_start();

require_once "conexao.php";

if (!isset($_SESSION["usuario_id"])) {

    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT * FROM usuarios WHERE id = ?"
);

$stmt->execute([
    $_SESSION["usuario_id"]
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Meu perfil</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="card perfil">

        <?php if ($usuario["foto"]): ?>

            <img
                src="<?= htmlspecialchars($usuario["foto"]) ?>"
                class="foto-perfil"
                alt="Foto de perfil"
            >

        <?php else: ?>

            <div class="foto-placeholder">
                <?= strtoupper(substr($usuario["nome"], 0, 1)) ?>
            </div>

        <?php endif; ?>

        <h1>
            <?= htmlspecialchars($usuario["nome"]) ?>
        </h1>

        <p>
            <?= htmlspecialchars($usuario["email"]) ?>
        </p>

        <a
            href="logout.php"
            class="botao-secundario"
        >
            Sair
        </a>

    </div>

</div>

</body>

</html>