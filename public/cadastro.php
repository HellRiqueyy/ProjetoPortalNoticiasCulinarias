<?php
include_once '../config/config.php';
include_once '../classes/Usuario.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario($conexao);
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $imagem = $_POST['imagem'];
    $usuario->registrar($nome, $email, $senha);
    $alert = true;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | Culinária em Foco</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <?php include '../contents/header.html'; ?>

    <main class="auth-shell">
        <section class="auth-card">
            <h1>Criar conta</h1>
            <p>Junte-se ao portal e compartilhe sua paixão por gastronomia.</p>

            <form method="POST" class="form-grid">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" required>

                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>

                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>

                <label for="imagem">Foto de perfil (opcional)</label>
                <input type="file" name="imagem" id="imagem" accept="image/*">

                <button type="submit" class="btn">Cadastrar</button>
            </form>

            <p>Já tem uma conta? <a href="./login.php">Faça login aqui</a></p>
            <?php if (isset($alert) && $alert) echo '<p>Usuário adicionado com sucesso!</p>'; ?>
        </section>
    </main>

    <?php include '../contents/footer.html'; ?>
</body>
</html>