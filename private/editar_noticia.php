<?php
session_start();
include_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de notícia inválido.');
}

$id = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $noticia = $_POST['noticia'];

    $stmt = $conexao->prepare('UPDATE noticias SET titulo = ?, noticia = ? WHERE id = ?');
    $stmt->bind_param('ssi', $titulo, $noticia, $id);
    $stmt->execute();

    header('Location: dashboard.php');
    exit;
}

$stmt = $conexao->prepare('SELECT * FROM noticias WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();

if (!$noticia) {
    die('Notícia não encontrada.');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Notícia</title>
</head>
<body>
<form method="POST">
    <h2>Editar Publicação</h2>
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($noticia['titulo']); ?>" required>
    <label for="noticia">Notícia:</label>
    <textarea id="noticia" name="noticia" required><?= htmlspecialchars($noticia['noticia']); ?></textarea>
    <button type="submit">Atualizar</button>
</form>
</body>
</html>