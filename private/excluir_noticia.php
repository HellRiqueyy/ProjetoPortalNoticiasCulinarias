<?php include 'conexao.php'; include 'funcoes.php';

$id = $_GET['id'];
$stmt = $conexao->prepare("DELETE FROM noticias WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

?>