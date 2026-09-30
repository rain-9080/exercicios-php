<?php

require_once "conexao.php";

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Digite um e-mail válido.";

    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve possuir pelo menos 6 caracteres.";

    } else {

        // Verifica se o e-mail já existe
        $stmt = $pdo->prepare(
            "SELECT id FROM usuarios WHERE email = ?"
        );

        $stmt->execute([$email]);

        if ($stmt->fetch()) {

            $erro = "Esse e-mail já está cadastrado.";

        } else {

            $foto = null;

            // Verifica se uma imagem foi enviada
            if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE) {

                if ($_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
                    $erro = "Erro ao enviar a imagem.";
                } else {

                    $tiposPermitidos = [
                        "image/jpeg",
                        "image/png",
                        "image/webp"
                    ];

                    if (!in_array($_FILES["foto"]["type"], $tiposPermitidos)) {

                        $erro = "A imagem deve ser JPG, PNG ou WEBP.";

                    } elseif ($_FILES["foto"]["size"] > 5 * 1024 * 1024) {

                        $erro = "A imagem deve ter no máximo 5 MB.";

                    } else {

                        $extensao = strtolower(
                            pathinfo(
                                $_FILES["foto"]["name"],
                                PATHINFO_EXTENSION
                            )
                        );

                        $nomeArquivo = uniqid("perfil_", true) . "." . $extensao;

                        $destino = "imagens" . $nomeArquivo;

                        if (move_uploaded_file(
                            $_FILES["foto"]["tmp_name"],
                            $destino
                        )) {

                            $foto = $destino;

                        } else {

                            $erro = "Não foi possível salvar a imagem.";
                        }
                    }
                }
            }

            if (empty($erro)) {

                $senhaHash = password_hash(
                    $senha,
                    PASSWORD_DEFAULT
                );

                $stmt = $pdo->prepare(
                    "INSERT INTO usuarios
                    (nome, email, senha, foto)
                    VALUES (?, ?, ?, ?)"
                );

                $stmt->execute([
                    $nome,
                    $email,
                    $senhaHash,
                    $foto
                ]);

                $sucesso = "Cadastro realizado com sucesso!";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Criar conta</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <div class="card">

        <h1>Criar conta</h1>

        <p class="subtitle">
            Crie sua conta para continuar
        </p>

        <?php if ($erro): ?>

            <div class="mensagem erro">
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>

        <?php if ($sucesso): ?>

            <div class="mensagem sucesso">
                <?= htmlspecialchars($sucesso) ?>
            </div>

        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <label for="nome">
                Nome
            </label>

            <input
                type="text"
                id="nome"
                name="nome"
                placeholder="Digite seu nome"
                required
            >

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
                placeholder="Mínimo de 6 caracteres"
                required
            >

            <label for="foto">
                Foto de perfil
            </label>

            <input
                type="file"
                id="foto"
                name="foto"
                accept="image/jpeg,image/png,image/webp"
            >

            <button type="submit">
                Criar conta
            </button>

        </form>

        <p class="link">
            Já possui uma conta?
            <a href="login.php">Entrar</a>
        </p>

    </div>

</div>

</body>
</html>