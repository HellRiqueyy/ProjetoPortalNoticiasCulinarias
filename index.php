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
            <article class="news-card">
                <div class="news-card__content">
                    <h2 class="news-card__title">Novidades do mês</h2>
                    <p class="news-card__meta">Por Redação • 27 jun 2026</p>
                    <p>Confira as principais tendências de sabores, ingredientes e experiências gastronômicas que estão marcando o cenário atual.</p>
                </div>
            </article>

            <article class="news-card">
                <div class="news-card__content">
                    <h2 class="news-card__title">Receita do dia</h2>
                    <p class="news-card__meta">Por Ana Souza • 26 jun 2026</p>
                    <p>Uma receita prática, elegante e perfeita para surpreender em qualquer ocasião.</p>
                </div>
            </article>
        </section>
    </main>

    <?php include 'contents/footer.html'; ?>
</body>
</html>