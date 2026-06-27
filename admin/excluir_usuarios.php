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

if ($id === $_SESSION['usuario_id']) {
    die('Você não pode excluir sua própria conta.');
}

$stmt = $conexao->prepare('DELETE FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

header('Location: usuarios.php');
exit;
?>