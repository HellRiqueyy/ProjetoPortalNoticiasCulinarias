<?php
session_start();
include_once __DIR__ . '/config/config.php';
include_once __DIR__ . '/classes/Noticia.php';
$noticiaModel = new Noticia($conexao);

$noticias = $noticiaModel->lerNoticiasPorData();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Notícias Culinárias</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/home.css">
</head>

<body>
    <?php include 'contents/header.html'; ?>

    <main class="container">
        <section class="hero">
            <h1>Descubra o melhor da gastronomia</h1>
            <p>Receitas, tendências, entrevistas e novidades do universo culinário em um só lugar.</p>
        </section>

        <section class="news-list">
            <?php foreach ($noticias as $noticia): ?>
                <article class="news-card">
                    <div class="news-card__content">
                        <a href="public/noticia.php?id=<?php echo htmlspecialchars($noticia['id']); ?>">
                            <h2 class="news-card__title"><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                        </a>
                        <p class="news-card__meta">Por <?php echo htmlspecialchars($noticia['autorNome']); ?> •
                            <?php echo date('d M Y', strtotime($noticia['data'])); ?>
                        </p>
                        <p><?php echo nl2br(htmlspecialchars($noticiaModel->resumirTexto($noticia['noticia'],50))); ?></p>
                        <?php
                        $imagemSrc = $noticiaModel->resolverImagemUrl($noticia['imagem']);
                        ?>
                        <?php if (!empty($imagemSrc)): ?>
                            <img src="<?php echo htmlspecialchars($imagemSrc); ?>" alt="Imagem da notícia">
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <?php include 'contents/footer.html'; ?>
</body>
</html>