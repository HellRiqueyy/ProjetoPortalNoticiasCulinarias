<?php
session_start();
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ .'/../classes/Noticia.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de notícia inválido.');
}

$id = (int) $_GET['id'];
$noticiaModel = new Noticia($conexao);
$noticia = $noticiaModel->lerNoticiaPorId($id);

if (!$noticia) {
    die('Notícia não encontrada.');
}

function uploadImagem($file)
{
    $uploadDir = __DIR__ . '/../public/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . $ext;
    $dest = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return '/public/uploads/' . $filename;
    }

    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $noticiaTexto = $_POST['noticia'];
    $imagem = $noticia['imagem'];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem = uploadImagem($_FILES['imagem']);
    }

    $noticiaModel->atualizarNoticia($id, $titulo, $noticiaTexto, $imagem);

    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia | Culinária em Foco</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <?php include '../contents/header.html'; ?>

    <main class="auth-shell">
        <section class="auth-card auth-card--wide">
            <h1>Editar Publicação</h1>
            <form method="POST" enctype="multipart/form-data" class="form-grid">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($noticia['titulo']); ?>" required>

                <label for="noticia">Notícia</label>
                <textarea id="noticia" name="noticia" rows="8" required><?= htmlspecialchars($noticia['noticia']); ?></textarea>

                <?php if (!empty($noticia['imagem'])): ?>
                    <div class="current-image">
                        <p>Imagem atual</p>
                        <img src="<?= htmlspecialchars($noticiaModel->resolverImagemUrl($noticia['imagem'])); ?>" alt="Imagem atual da notícia">
                    </div>
                <?php endif; ?>

                <div class="image-field">
                    <label for="imagem">Nova imagem (opcional)</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*">
                </div>

                <button type="submit" class="btn">Atualizar</button>
            </form>
        </section>
    </main>

    <?php include '../contents/footer.html'; ?>
</body>
</html>