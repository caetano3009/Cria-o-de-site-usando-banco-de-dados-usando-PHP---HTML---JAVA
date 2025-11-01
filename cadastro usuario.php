<?php
session_start();
$erro = "";
$sucesso = "";

// Verifica e limpa mensagens de feedback da sessão
if (isset($_SESSION['cadastro_sucesso'])) {
    $sucesso = $_SESSION['cadastro_sucesso'];
    unset($_SESSION['cadastro_sucesso']);
}
if (isset($_SESSION['cadastro_erro'])) {
    $erro = $_SESSION['cadastro_erro'];
    unset($_SESSION['cadastro_erro']);
}

// Redireciona se já estiver logado (admin ou usuário)
if (isset($_SESSION["admin"]) || isset($_SESSION["usuario_logado"])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content-wrapper">
             <h1>
                <a href="index.php">
                    <i class="fas fa-user-plus"></i> Cadastro de Usuário
                </a>
            </h1>
            <div class="perfil-usuario-area">
                 <a href="index.php" class="btn-inicio-cabecalho"><i class="fas fa-home"></i> Início</a>
                 <a href="login_usuario.php" class="btn-login-usuario"><i class="fas fa-sign-in-alt"></i> Login Usuário</a>
                 <a href="login.php" class="btn-admin"><i class="fas fa-lock"></i> Área Admin</a>
            </div>
        </div>
        <nav></nav>
    </header>

    <main class="form-container-centralizado">
        <div class="form-box-white">
            <h2>Crie sua Conta</h2>
            
            <?php if($sucesso): ?>
                <p style="color: green; margin-bottom: 20px; font-weight: bold;"><?= $sucesso ?></p>
            <?php endif; ?>
            
            <?php if($erro): ?>
                <p style="color: red; margin-bottom: 20px; font-weight: bold;"><?= $erro ?></p>
            <?php endif; ?>

            <form action="salvar_usuario.php" method="POST" class="formulario-vertical">
                <div class="campo-grupo">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" name="nome" id="nome" placeholder="Seu nome" required>
                </div>
                
                <div class="campo-grupo">
                    <label for="email">Endereço de e-mail:</label>
                    <input type="email" name="email" id="email" placeholder="seu@email.com" required>
                </div>
                
                <div class="campo-grupo">
                    <label for="senha">Crie uma Senha:</label>
                    <input type="password" name="senha" id="senha" placeholder="Mínimo 6 caracteres" minlength="6" required>
                </div>
                
                <button type="submit" style="background-color: #28a745;"><i class="fas fa-user-plus"></i> Cadastrar</button>

                <div class="links-rodape">
                    <p>Já tem uma conta? <a href="login_usuario.php">Faça login aqui</a></p>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <p>© 2025 Impulso Estágio | Desenvolvido por Jimmy Peakes 😎</p>
    </footer>
</body>
</html>