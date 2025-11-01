<?php
session_start();
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    // login padrão: admin / 1234
    if ($usuario === "admin" && $senha === "1234") {
        $_SESSION["admin"] = true;
        header("Location: index.php");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login do Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content-wrapper">
            <h1>
                <a href="index.php">
                    <i class="fas fa-lock"></i> Login do Administrador
                </a>
            </h1>
            <div class="perfil-usuario-area">
                <a href="index.php" class="btn-inicio-cabecalho"><i class="fas fa-home"></i> Início</a>
            </div>
        </div>
        <nav></nav>
    </header>

    <main class="form-container-centralizado">
        <div class="form-box-white">
            <h2><i class="fas fa-user-lock"></i> Entrar na Área Admin</h2>
            <form action="" method="POST" class="formulario-vertical">
                <div class="campo-grupo">
                    <label for="usuario">Usuário:</label>
                    <input type="text" name="usuario" id="usuario" required>
                </div>
                
                <div class="campo-grupo">
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" id="senha" required>
                </div>
                
                <button type="submit"><i class="fas fa-sign-in-alt"></i> Entrar</button>

                <?php if($erro): ?>
                    <p style="color:red; margin-top: 10px;"><?= $erro ?></p>
                <?php endif; ?>

                <div class="links-rodape">
                    <a href="index.php">Voltar para o Início</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>