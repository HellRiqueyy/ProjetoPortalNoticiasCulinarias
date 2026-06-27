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

$usuarios = $conexao->query('SELECT id, nome, email, nivel FROM usuarios');
?>
<!DOCTYPE html>
<html>
<head><title>Gerenciar Usuários</title></head>
<body>
    <h2>Gerenciamento de Usuários</h2>
    <a href="../private/dashboard.php">⬅ Voltar ao Painel</a>
    <table border="1" style="width: 100%; margin-top: 20px;">
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Nível</th>
            <th>Ações</th>
        </tr>
        <?php while($u = $usuarios->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($u['nome']); ?></td>
            <td><?= htmlspecialchars($u['email']); ?></td>
            <td><?= htmlspecialchars($u['nivel']); ?></td>
            <td>
                <a href="editar_usuarios.php?id=<?= $u['id'] ?>">Editar</a> | 
                <a href="excluir_usuarios.php?id=<?= $u['id'] ?>">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>