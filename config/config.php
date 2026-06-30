<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "dbportal";


$conexao = new mysqli($servidor, $usuario, $senha);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

if (!$conexao->select_db($banco)) {
    $sqlCriarBanco = "CREATE DATABASE IF NOT EXISTS `$banco` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!$conexao->query($sqlCriarBanco)) {
        die("Falha ao criar banco de dados: " . $conexao->error);
    }
    $conexao->select_db($banco);
}

$sqlCriarTabelaUsuarios = "CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `nivel` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if (!$conexao->query($sqlCriarTabelaUsuarios)) {
    die("Falha ao criar tabela usuarios: " . $conexao->error);
}

// 2. TABELA DE NOTÍCIAS (Corrigido AUTO_INCREMENT e SET NULL)
$sqlCriarTabela = "CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int(11) NOT NULL AUTO_INCREMENT, -- Adicionado AUTO_INCREMENT para não dar erro ao inserir
  `titulo` varchar(255) NOT NULL,
  `noticia` text NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `autor` int(11) DEFAULT NULL, -- Mudado para DEFAULT NULL para o comando abaixo funcionar
  `imagem` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL -- Ajustado para o padrão correto do MySQL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if (!$conexao->query($sqlCriarTabela)) {
    die("Falha ao criar tabela noticias: " . $conexao->error);
}

// 3. TABELA DE COMENTÁRIOS (Corrigido AUTO_INCREMENT, TEXT e SET NULL)
$sqlCriarTabelaComentario = "CREATE TABLE IF NOT EXISTS `comentarios` (
    `id` int(11) NOT NULL AUTO_INCREMENT, -- Adicionado AUTO_INCREMENT
    `comentario` text NOT NULL, -- Removido o (1000) do TEXT, que causava erro de sintaxe
    `autor` int(11) DEFAULT NULL, -- Mudado para DEFAULT NULL para aceitar o SET NULL abaixo
    `data` datetime DEFAULT current_timestamp(),
    `noticia` int (11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`noticia`) REFERENCES `noticias` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL -- Ajustado para o padrão correto do MySQL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
if (!$conexao->query($sqlCriarTabelaComentario)) {
    die("Falha ao criar tabela noticias: " . $conexao->error);
}

$sqlCriarTabelaLikes = "CREATE TABLE IF NOT EXISTS `likes_noticias` (
    `usuario_id` int(11) NOT NULL,
    `noticia_id` int(11) NOT NULL,
    `data_curtida` datetime DEFAULT current_timestamp(),
    
    -- Define a união dos dois campos como a Chave Primária.
    -- Isso impede o mesmo usuário de curtir a mesma notícia 2 vezes!
    PRIMARY KEY (`usuario_id`, `noticia_id`),
    
    -- Chaves Estrangeiras: Se o usuário ou a notícia forem deletados, o like some
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`noticia_id`) REFERENCES `noticias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if (!$conexao->query($sqlCriarTabelaLikes)) {
    die("Falha ao criar tabela likes_noticias: " . $conexao->error);
}

$resultadoTabela = $conexao->query("SELECT COUNT(*) AS total FROM noticias");
if ($resultadoTabela) {
    $row = $resultadoTabela->fetch_assoc();
    if ($row['total'] == 0) {
        $sqlInserirExemplos = "INSERT INTO `noticias` (`titulo`, `noticia`, `data`) VALUES
            ('Lançamento da nova plataforma', 'Estamos felizes em anunciar que nossa nova plataforma já está online.', NOW()),
            ('Atualização de recursos', 'Novos recursos foram adicionados para melhorar a experiência do usuário.', NOW()),
            ('Dicas de desenvolvimento', 'Confira nossas dicas de desenvolvimento para criar sites mais rápidos e seguros.', NOW())";
        $conexao->query($sqlInserirExemplos);
    }
}

$conexao->set_charset("utf8");
?>