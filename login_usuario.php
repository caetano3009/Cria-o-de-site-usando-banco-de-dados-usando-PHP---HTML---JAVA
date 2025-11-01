<?php
session_start();
$erro = "";

// Pega a mensagem de sucesso do cadastro (se houver)
$sucesso = isset($_SESSION['cadastro_sucesso']) ? $_SESSION['cadastro_sucesso'] : "";
if ($sucesso) {
    unset($_SESSION['cadastro_sucesso']);
}

// Redireciona se já estiver logado (admin ou usuário)
if (isset($_SESSION["admin"]) || isset($_SESSION["usuario_logado"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_login = trim(strtolower($_POST["email"]));
    $senha_login = $_POST["senha"];
    $usuarios_file = "usuarios.txt";
    $login_ok = false;
    $nome_usuario = "";

    if (file_exists($usuarios_file)) {
        $usuarios = file($usuarios_file);
        foreach ($usuarios as $usuario) {
            $dados = explode("|", trim($usuario));
            // Formato: Nome|Email|SenhaHash
            if (count($dados) >= 3 && trim($dados[1]) === $email_login) {
                $nome_usuario = $dados[0];
                $senha_hash = $dados[2];
                // Verifica a senha com o hash
                if (password_verify($senha_login, $senha_hash)) {
                    $login_ok = true;
                    break;
                }
            }
        }
    }

    if ($login_ok) {
        // Loga o usuário e salva suas informações para candidaturas
        $_SESSION["usuario_logado"] = [
            "nome" => $nome_usuario, 
            "email" => $email_login
        ];
        header("Location: index.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos! Verifique suas credenciais.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login de Usuário</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content-wrapper">
             <h1>
                <a href="index.php">
                    <i class="fas fa-sign-in-alt"></i> Login de Usuário
                </a>
            </h1>
            <div class="perfil-usuario-area">
                 <a href="index.php" class="btn-inicio-cabecalho"><i class="fas fa-home"></i> Início</a>
                 <a href="cadastro_usuario.php" class="btn-cadastro-usuario"><i class="fas fa-user-plus"></i> Cadastro Usuário</a>
                 <a href="login.php" class="btn-admin"><i class="fas fa-lock"></i> Área Admin</a>
            </div>
        </div>
        <nav></nav>
    </header>

    <main class="form-container-centralizado">
        <div class="form-box-white">
            <h2><i class="fas fa-user-check"></i> Entrar na Sua Conta</h2>
            
            <?php if($sucesso): ?>
                <p style="color: green; margin-bottom: 10px; font-weight: bold;"><?= $sucesso ?></p>
            <?php endif; ?>
            
            <form action="" method="POST" class="formulario-vertical">
                <div class="campo-grupo">
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div class="campo-grupo">
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" id="senha" required>
                </div>
                
                <button type="submit"><i class="fas fa-sign-in-alt"></i> Entrar</button>

                <?php if($erro): ?>
                    <p style="color:red; margin-top: 10px; font-weight: bold;"><?= $erro ?></p>
                <?php endif; ?>

                <div class="links-rodape">
                    <p>Não tem conta? <a href="cadastro_usuario.php">Crie uma conta</a></p>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <p>© 2025 Impulso Estágio | Desenvolvido por Jimmy Peakes 😎</p>
    </footer>
</body>
</html>