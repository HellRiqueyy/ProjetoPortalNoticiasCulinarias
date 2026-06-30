<?php
session_start();
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/../classes/usuario.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php');
    exit;
}

$usuarioModel = new Usuario($conexao);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nome !== '' && $email !== '') {
        $usuarioModel->atualizarUsuario($_SESSION['usuario_id'], $nome, $email);
        $_SESSION['usuario_nome'] = $nome;
        $message = 'Dados atualizados com sucesso!';
    } else {
        $message = 'Nome e e-mail são obrigatórios.';
    }
}

$stmt = $conexao->prepare('SELECT nome, email, nivel FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    session_destroy();
    header('Location: ../public/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | Culinária em Foco</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <?php include '../contents/header.html'; ?>

    <main class="auth-shell">
        <section class="auth-card">
            <h1>Meu perfil</h1>
            <p>Atualize seu nome e e-mail abaixo.</p>

            <?php if ($message): ?>
                <p><?= htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <form method="POST" class="form-grid">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']); ?>" required>

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" required>

                <button type="submit" class="btn">Salvar</button>
            </form>
        </section>
    </main>

    <?php include '../contents/footer.html'; ?>
</body>
</html>
