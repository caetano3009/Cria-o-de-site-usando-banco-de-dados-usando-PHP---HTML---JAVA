<?php
session_start();

// Redireciona se não for admin
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Vaga</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content-wrapper">
            <h1>
                <a href="index.php">
                    <i class="fas fa-clipboard-list"></i> Cadastrar Vaga de Estágio
                </a>
            </h1>
            <div class="perfil-usuario-area">
                 <a href="index.php" class="btn-inicio-cabecalho"><i class="fas fa-home"></i> Início</a>
                 <a href="logout.php" class="btn-admin"><i class="fas fa-lock-open"></i> Sair (Admin)</a>
            </div>
        </div>
        <nav></nav>
    </header>

    <main>
        <h2>Preencha os Dados da Vaga</h2>
        
        <form action="salvar_vagas.php" method="POST" class="formulario-vaga">
            <label for="titulo">Título da vaga:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="area">Área:</label>
            <input type="text" id="area" name="area" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="4" required></textarea>

            <label for="contato">Contato (email ou telefone):</label>
            <input type="text" id="contato" name="contato" required>

            <label for="tipo">Tipo de vaga:</label>
            <select id="tipo" name="tipo" required>
                <option value="">Selecione</option>
                <option value="Presencial">Presencial</option>
                <option value="Remoto">Remoto</option>
                <option value="Híbrido">Híbrido</option>
            </select>

            <button type="submit"><i class="fas fa-save"></i> Salvar Vaga</button>
        </form>
    </main>
    <footer>
        <p>© 2025 Impulso Estágio | Desenvolvido por Jimmy Peakes  😎</p>
    </footer>
</body>
</html>