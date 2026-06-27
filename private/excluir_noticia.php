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
$stmt = $conexao->prepare('DELETE FROM noticias WHERE id = ?');
$stmt->bind_param('i', $id);

if (!$stmt->execute()) {
    die('Erro ao excluir notícia: ' . $stmt->error);
}

$stmt->close();
header('Location: dashboard.php');
exit;
?>