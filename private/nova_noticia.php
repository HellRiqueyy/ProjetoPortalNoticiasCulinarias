<?php
session_start();
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ .'/../classes/Noticia.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php');
    exit;
}

$message = '';

function uploadImagem($file) {
    $uploadDir = __DIR__ . '/../public/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . $ext;
    $dest = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return 'uploads/' . $filename;
    }

    return null;
}

$noticiaModel = new Noticia($conexao);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $conteudoNoticia = $_POST['noticia'];
    $imagem = null;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem = uploadImagem($_FILES['imagem']);
    }

    $noticiaModel->criarNoticia($titulo, $conteudoNoticia, $imagem);
    $message = 'Notícia cadastrada com sucesso!';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Notícia</title>
</head>
<body>
    <h1>Cadastrar Nova Notícia</h1>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>
        <br><br>
        <label for="noticia">Notícia:</label>
        <textarea id="noticia" name="noticia" required></textarea>
        <br><br>
        <label for="imagem">Imagem (opcional):</label>
        <input type="file" id="imagem" name="imagem" accept="image/*">
        <br><br>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>