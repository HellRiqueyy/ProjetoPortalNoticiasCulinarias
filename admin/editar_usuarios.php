<?php
session_start();
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/../classes/usuario.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php');
    exit;
}

$usuarioModel = new Usuario($conexao);
$currentUser = $usuarioModel->lerPorIdUsuario($_SESSION['usuario_id']);

if (!$currentUser || !$usuarioModel->ehAdmin($_SESSION['usuario_id'])) {
    die('Acesso negado.');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID inválido.');
}

$id = (int) $_GET['id'];
$user = $usuarioModel->lerPorIdUsuario($id);

if (!$user) {
    die('Usuário não encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $nivel = $_POST['nivel'];

    $usuarioModel->atualizarUsuario($id, $nome, $email, $nivel);
    header('Location: usuarios.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Editar Usuário</title></head>
<body>
<form method="POST">
    <h2>Editar Usuário</h2>
    Nível: 
    <select name="nivel">
        <option value="user" <?= $user['nivel'] === 'user' ? 'selected' : '' ?>>Usuário Comum</option>
        <option value="admin" <?= $user['nivel'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
    </select><br><br>
    Nome: <input type="text" name="nome" value="<?= htmlspecialchars($user['nome']); ?>"><br><br>
    E-mail: <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>"><br><br>
    <button type="submit">Salvar</button> | <a href="usuarios.php">Voltar</a>
</form>
</body>
</html>