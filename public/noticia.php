<?php
session_start();
include_once '../config/config.php';
include_once '../classes/Noticia.php';
include_once '../classes/Comentario.php';
include_once '../classes/Like.php';
$noticiaModel = new Noticia($conexao);
$comentarioModel = new Comentario($conexao);
$likeModel = new Like($conexao);
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de notícia inválido.');
}
$id = (int) $_GET['id'];
$noticia = $noticiaModel->lerNoticiaPorId($id);
if (!$noticia) {
    die('Notícia não encontrada.');
}

// Tratamento de comentários e likes
$nivel = $_SESSION['usuario_nivel'] ?? null;
$usuario = $_SESSION['usuario_id'] ?? null;

$comentariosResult = $comentarioModel->lerComentarios($id);
$comentarios = [];
while ($comentario = $comentariosResult->fetch_assoc()) {
    $comentarios[] = $comentario;
}

$editingCommentId = null;
if (isset($_GET['action'], $_GET['comment_id']) && $_GET['action'] === 'editar' && is_numeric($_GET['comment_id'])) {
    $editingCommentId = (int) $_GET['comment_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['usuario_id'])) {
        $error_msg = 'Você precisa estar logado para comentar ou curtir.';
    } elseif (isset($_POST['action']) && in_array($_POST['action'], ['curtir', 'descurtir'], true)) {
        if (isset($_POST['noticia_id']) && is_numeric($_POST['noticia_id']) && (int) $_POST['noticia_id'] === $id) {
            $usuarioId = (int) $usuario;
            if ($_POST['action'] === 'curtir') {
                $likeModel->curtir($usuarioId, $id);
            } else {
                $likeModel->descurtir($usuarioId, $id);
            }
            header('Location: noticia.php?id=' . $id);
            exit;
        }
    } elseif (isset($_POST['action'], $_POST['comment_id']) && is_numeric($_POST['comment_id'])) {
        $commentId = (int) $_POST['comment_id'];
        $comentarioSelecionado = null;
        foreach ($comentarios as $comentario) {
            if ((int) $comentario['id'] === $commentId) {
                $comentarioSelecionado = $comentario;
                break;
            }
        }

        if (!$comentarioSelecionado || $comentarioSelecionado['autor'] !== $usuario) {
            $error_msg = 'Ação não autorizada.';
            if ($_POST['action'] === 'atualizar') {
                $editingCommentId = $commentId;
            }
        } elseif ($_POST['action'] === 'apagar') {
            $comentarioModel->apagarComentario($commentId);
            header('Location: noticia.php?id=' . $id);
            exit;
        } elseif ($_POST['action'] === 'atualizar' && isset($_POST['comentario'])) {
            $comentarioModel->atualizarComentario($commentId, trim($_POST['comentario']));
            header('Location: noticia.php?id=' . $id);
            exit;
        }
    } elseif (isset($_POST['comentario'])) {
        $_SESSION['noticia_id'] = $id;
        $comentarioModel->criarComentario(trim($_POST['comentario']));
        header('Location: noticia.php?id=' . $id);
        exit;
    }
}

$likesCount = $likeModel->contarLikes($id);
$curtiu = $usuario ? $likeModel->usuarioCurtiu($usuario, $id) : false;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $noticia['titulo']; ?></title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/noticia.css">
</head>

<body>
    <?php include '../contents/header.html'; ?>
    <main class="container">
        <article class="article-card">
            <h1 class="article-title"><?php echo $noticia['titulo']; ?></h1>
            <p class="article-meta">Por <?php echo $noticia['autorNome']; ?> em <?php echo date('d M Y - H:i', strtotime($noticia['data'])); ?></p>
            <?php
    $imagemSrc = $noticiaModel->resolverImagemUrl($noticia['imagem']);
    ?>
            <?php if (!empty($imagemSrc)): ?>
                <div class="article-image">
                    <img src="<?php echo htmlspecialchars($imagemSrc); ?>" alt="Imagem da notícia">
                </div>
            <?php endif; ?>

            <div class="article-body">
                <?php echo nl2br($noticia['noticia']); ?>
            </div>

            <div class="article-actions">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <form method="post" action="noticia.php?id=<?php echo $id; ?>">
                        <input type="hidden" name="action" value="<?php echo $curtiu ? 'descurtir' : 'curtir'; ?>">
                        <input type="hidden" name="noticia_id" value="<?php echo $id; ?>">
                        <button type="submit" class="btn"><?php echo $curtiu ? '❤️ Descurtir' : '🤍 Curtir'; ?></button>
                    </form>
                <?php else: ?>
                    <p><a href="login.php" class="btn btn--secondary">Faça login</a> para curtir.</p>
                <?php endif; ?>
                <span class="article-actions__label">Curtidas <strong>(<?php echo $likesCount; ?>)</strong></span>
            </div>
        </article>

        <section class="comment-section">
            <div class="comment-section__header">
                <h2 class="comment-section__title">Comentários (<?php echo count($comentarios); ?>)</h2>
            </div>

            <?php foreach ($comentarios as $comentario): ?>
                <article class="comment-card">
                    <div class="comment-card__header">
                        <div>
                            <p class="comment-card__author"><?php echo htmlspecialchars($comentario['usuarioNome']); ?></p>
                            <p class="comment-card__date">Comentou em <?php echo date('d/m/Y H:i', strtotime($comentario['data'])); ?></p>
                        </div>

                        <?php if ($usuario === $comentario['autor'] && $editingCommentId !== (int) $comentario['id']): ?>
                            <div class="comment-card__actions">
                                <form method="get" action="noticia.php" style="display:inline">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="action" value="editar">
                                    <input type="hidden" name="comment_id" value="<?php echo $comentario['id']; ?>">
                                    <button type="submit" class="btn btn--text">Editar</button>
                                </form>
                                <form method="post" action="noticia.php?id=<?php echo $id; ?>" style="display:inline">
                                    <input type="hidden" name="action" value="apagar">
                                    <input type="hidden" name="comment_id" value="<?php echo $comentario['id']; ?>">
                                    <button type="submit" class="btn btn--text" onclick="return confirm('Tem certeza que deseja apagar este comentário?')">Apagar</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($editingCommentId === (int) $comentario['id']): ?>
                        <form method="post" action="noticia.php?id=<?php echo $id; ?>" class="comment-form">
                            <input type="hidden" name="action" value="atualizar">
                            <input type="hidden" name="comment_id" value="<?php echo $comentario['id']; ?>">
                            <textarea name="comentario" rows="4" required><?php echo htmlspecialchars($comentario['comentario']); ?></textarea>
                            <div class="comment-card__actions">
                                <button type="submit" class="btn">Salvar comentário</button>
                                <a href="noticia.php?id=<?php echo $id; ?>" class="btn btn--secondary">Cancelar</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="comment-card__body"><?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?></div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>

            <?php if (!empty($error_msg)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error_msg); ?></p>
            <?php endif; ?>

            <?php if (isset($_SESSION['usuario_id'])): ?>
                <form method="post" action="noticia.php?id=<?php echo $id; ?>" class="comment-form">
                    <label for="comentario">Deixe um comentário</label>
                    <textarea id="comentario" name="comentario" rows="4" required></textarea>
                    <button type="submit" class="btn">Enviar comentário</button>
                </form>
            <?php else: ?>
                <p><a href="login.php" class="btn btn--secondary">Faça login</a> para comentar.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include '../contents/footer.html'; ?>
</body>

</html>