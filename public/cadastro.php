<?php
include_once '../config/config.php';
include_once '../classes/usuario.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario($conexao);
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $imagem = $_POST['imagem'];
    $usuario->criarUsuario($nome, $email, $senha, $imagem);
    $alert=true;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Usuário</title>
</head>
<body>
    <h1>Adicionar Usuário</h1>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>
        <br><br>
        <label for="imagem">Foto:</label>
        <input type="text" name="imagem" required>
        <br><br>
        <input type="submit" value="Adicionar">
        <br><br>
        <p>Já tem uma conta? <a href="./login.php">Faça login aqui</a></p>
        <?php if (isset($alert) && $alert) echo '<p>Usuário adicionado com sucesso!</p>'; ?>
    </form>
</body>
</html>
