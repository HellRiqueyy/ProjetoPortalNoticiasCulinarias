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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Notícia | Culinária em Foco</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <?php include '../contents/header.html'; ?>

    <main class="auth-shell">
        <section class="auth-card">
            <h1>Cadastrar Nova Notícia</h1>
            <?php if ($message): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form-grid">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" required>

                <label for="noticia">Notícia</label>
                <textarea id="noticia" name="noticia" rows="6" required></textarea>

                <label for="imagem">Imagem (opcional)</label>
                <input type="file" id="imagem" name="imagem" accept="image/*">

                <button type="submit" class="btn">Cadastrar</button>
            </form>
        </section>
    </main>

    <?php include '../contents/footer.html'; ?>
</body>
</html>