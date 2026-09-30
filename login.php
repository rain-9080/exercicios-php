<?php

session_start();

require_once "conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    $stmt = $pdo->prepare(
        "SELECT * FROM usuarios WHERE email = ?"
    );

    $stmt->execute([$email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario["senha"])) {

        $_SESSION["usuario_id"] = $usuario["id"];

        header("Location: perfil.php");
        exit;

    } else {

        $erro = "E-mail ou senha incorretos.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Bem-vindo</h1>

        <p class="subtitle">
            Entre na sua conta
        </p>

        <?php if ($erro): ?>

            <div class="mensagem erro">
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <label for="email">
                E-mail
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Digite seu e-mail"
                required
            >

            <label for="senha">
                Senha
            </label>

            <input
                type="password"
                id="senha"
                name="senha"
                placeholder="Digite sua senha"
                required
            >

            <button type="submit">
                Entrar
            </button>

        </form>

        <p class="link">
            Ainda não possui uma conta?
            <a href="cadastro.php">Criar conta</a>
        </p>

    </div>

</div>

</body>

</html>