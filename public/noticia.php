<?php
include_once '../config/config.php';
include_once '../classes/Noticia.php';
$noticiaModel = new Noticia($conexao);
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de notícia inválido.');
}
$id = $_GET['id'];
$noticia = $noticiaModel->lerNoticiaPorId($id);
if (!$noticia) {
    die('Notícia não encontrada.');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $noticia['titulo']; ?></title>
</head>

<body>
    <?php include '../contents/header.html'; ?>
    <h1><?php echo $noticia['titulo']; ?></h1>
    <p>Por <?php echo $noticia['autorNome']; ?> em <?php echo date('d M Y - H:i', strtotime($noticia['data'])); ?></p>
    <?php if (!empty($noticia['imagem'])): ?>
        <img src="<?php echo $noticia['imagem']; ?>" alt="Imagem da notícia">
    <?php endif; ?>
    <p><?php echo nl2br($noticia['noticia']); ?></p>
    <?php include '../contents/footer.html'; ?>
</body>

</html>