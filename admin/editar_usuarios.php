<?php
session_start();
include_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php');
    exit;
}

$stmt = $conexao->prepare('SELECT nivel FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$currentUser = $result->fetch_assoc();

if (!$currentUser || $currentUser['nivel'] !== 'admin') {
    die('Acesso negado.');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID inválido.');
}

$id = (int) $_GET['id'];

$stmt = $conexao->prepare('SELECT * FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die('Usuário não encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $nivel = $_POST['nivel'];

    $stmt = $conexao->prepare('UPDATE usuarios SET nome = ?, email = ?, nivel = ? WHERE id = ?');
    $stmt->bind_param('sssi', $nome, $email, $nivel, $id);
    $stmt->execute();
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