<?php
session_start();
include_once '../config/config.php';
include_once '../classes/usuario.php';


$usuario = new Usuario($conexao);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Processar login
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        if ($dados_usuario = $usuario->login($email, $senha)) {
            $_SESSION['usuario_id'] = $dados_usuario['id'];
            $_SESSION['usuario_nome'] = $dados_usuario['nome'];
            $_SESSION['usuario_nivel'] = $dados_usuario['nivel'];
            header('Location: ../index.php');
            exit();
        } else {
            $mensagem_erro = "Credenciais inválidas!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Culinária em Foco</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <?php include '../contents/header.html'; ?>

    <main class="auth-shell">
        <section class="auth-card">
            <h1>Entrar</h1>
            <p>Acesse sua conta e continue acompanhando o melhor da culinária.</p>

            <form method="POST" class="form-grid">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>

                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>

                <button type="submit" name="login" class="btn">Entrar</button>
            </form>

            <p>Não tem uma conta? <a href="./cadastro.php">Registre-se aqui</a></p>
            <div class="mensagem">
                <?php if (isset($mensagem_erro)) echo '<p>' . $mensagem_erro . '</p>'; ?>
            </div>
        </section>
    </main>

    <?php include '../contents/footer.html'; ?>
</body>
</html>
