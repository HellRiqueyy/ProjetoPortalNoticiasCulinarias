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

$usuarios = $usuarioModel->lerUsuarios();
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