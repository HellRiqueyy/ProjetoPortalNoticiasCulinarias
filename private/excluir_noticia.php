<?php
session_start();
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/../classes/Noticia.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de notícia inválido.');
}

$id = (int) $_GET['id'];
$noticia = new Noticia($conexao);
$noticia->deletarNoticia($id);
header('Location: dashboard.php');
exit;
?>