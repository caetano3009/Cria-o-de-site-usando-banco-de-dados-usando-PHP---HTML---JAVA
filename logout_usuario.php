<?php
session_start();
// Simulação de autenticação
$erro = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simulação de login de usuário (substitua pela sua lógica de banco de dados)
    if ($_POST["email"] === "usuario@teste.com" && $_POST["senha"] === "123") {
        $_SESSION["usuario_logado"] = ["nome" => "Usuário Teste"]; 
        header("Location: index.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login do Usuário</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content-wrapper">
            <h1><i class="fas fa-user-circle"></i> Login de Usuário</h1>
            <div class="perfil-usuario-area">
                 <a href="index.php" class="btn-inicio-cabecalho"><i class="fas fa-home"></i> Início</a>
            </div>
        </div>
        <nav></nav>
    </header>
    <main class="form-container-centralizado">
        <div class="form-box-white">
             <h2>Login de Usuário</h2>

            <form action="" method="POST" class="form-login">
                <div class="form-campos-inline">
                    <div class="campo-wrapper">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" placeholder="seu@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="campo-wrapper">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" placeholder="Sua senha" required>
                        <a href="#" class="link-esqueci">Esqueceu sua senha?</a>
                    </div>
                    
                    <button type="submit" class="btn-inline-submit">Entrar</button>
                </div>

                <?php if($erro): ?>
                    <p style="color:red; margin-top: 15px; text-align: center; font-size: 0.9rem;"><?= $erro ?></p>
                <?php endif; ?>

                <div class="links-rodape">
                    <p>Não tem conta? <a href="cadastro_usuario.php">Criar conta</a></p>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <p>© 2025 Impulso Estágio | Desenvolvido por Jimmy Peakes  😎</p>
    </footer>
</body>
</html>