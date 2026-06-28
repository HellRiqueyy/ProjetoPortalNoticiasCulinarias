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

if ($id === $_SESSION['usuario_id']) {
    die('Você não pode excluir sua própria conta.');
}

$usuarioModel->deletarUsuario($id);

header('Location: usuarios.php');
exit;
?>