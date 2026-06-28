<?php
session_start();
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ .'/../classes/Noticia.php';
$noticiaModel = new Noticia($conexao);
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php');
    exit;
}

//$stmt = $conexao->prepare('SELECT nome, nivel FROM usuarios WHERE id = ?');
//$stmt->bind_param('i', $_SESSION['usuario_id']);
//$stmt->execute();
//$result = $stmt->get_result();
//$usuario = $result->fetch_assoc();

if (!$usuario) {
    session_destroy();
    header('Location: ../public/login.php');
    exit;
}

$nome = $_SESSION['usuario_nome'];
$nivel = $_SESSION['usuario_nivel'];
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - Portal de Notícias Culinárias</title>
    </head>
<body>
    <?php include '../contents/header.html'; ?>
    <h1>Olá, <?php echo htmlspecialchars($nome); ?>!</h1>
    <p>Esta é o seu dashboard.</p>

    <a href="nova_noticia.php">Cadastrar nova notícia</a>
    <?php if ($nivel === 'admin'): ?>
        <a href="../admin/usuarios.php">Gerenciar usuários (Admin)</a>
    <?php endif; ?>
    <a href="../public/logout.php">Sair</a>

    <h3>Suas publicações</h3>
    <?php $resultadoNoticias = $noticiaModel->lerNoticiasPorAutor(); ?>
    <?php while ($noticia = $resultadoNoticias->fetch_assoc()): ?>
        <div>
            <h4><?php echo htmlspecialchars($noticia['titulo']); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($noticia['noticia'])); ?></p>
            <p><strong>Autor:</strong> <?php echo htmlspecialchars($noticia['autor']); ?></p>
            <?php if (!empty($noticia['imagem'])): ?>
                <img src="<?php echo htmlspecialchars($noticia['imagem']); ?>" alt="Imagem da notícia" width="200">
            <?php endif; ?>
            <a href="editar_noticia.php?id=<?php echo $noticia['id']; ?>">Editar</a>
            <a href="excluir_noticia.php?id=<?php echo $noticia['id']; ?>">Excluir</a>
        </div>
    <?php endwhile; ?>
        <?php include '../contents/footer.html'; ?>
</body>
</html>