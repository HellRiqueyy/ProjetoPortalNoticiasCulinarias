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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="../assets/css/base.css">
</head>
<body>
    <?php include '../contents/header.html'; ?>

    <main class="container">
        <section class="card">
            <h2>Gerenciamento de Usuários</h2>
            <p>Administre os perfis da equipe editorial e os acessos do portal.</p>
            <a class="btn" href="../private/dashboard.php">⬅ Voltar ao Painel</a>

            <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                <tr style="background: #fff3e0;">
                    <th style="padding: 12px; border: 1px solid #e8d8c7;">Nome</th>
                    <th style="padding: 12px; border: 1px solid #e8d8c7;">E-mail</th>
                    <th style="padding: 12px; border: 1px solid #e8d8c7;">Nível</th>
                    <th style="padding: 12px; border: 1px solid #e8d8c7;">Ações</th>
                </tr>
                <?php while($u = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td style="padding: 12px; border: 1px solid #e8d8c7;"><?= htmlspecialchars($u['nome']); ?></td>
                    <td style="padding: 12px; border: 1px solid #e8d8c7;"><?= htmlspecialchars($u['email']); ?></td>
                    <td style="padding: 12px; border: 1px solid #e8d8c7;"><?= htmlspecialchars($u['nivel']); ?></td>
                    <td style="padding: 12px; border: 1px solid #e8d8c7;">
                        <a href="editar_usuarios.php?id=<?= $u['id'] ?>">Editar</a> |
                        <a href="excluir_usuarios.php?id=<?= $u['id'] ?>">Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </main>

    <?php include '../contents/footer.html'; ?>
</body>
</html>