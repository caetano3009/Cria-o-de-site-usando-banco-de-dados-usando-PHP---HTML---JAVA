<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Coleta e Limpa os Dados
    $nome = trim($_POST["nome"]);
    $email = trim(strtolower($_POST["email"]));
    $senha = $_POST["senha"];
    $usuarios_file = "usuarios.txt"; // Arquivo onde os dados serão salvos

    // 2. Validação básica
    if (empty($nome) || empty($email) || strlen($senha) < 6) {
        $_SESSION['cadastro_erro'] = "Preencha todos os campos e use uma senha com no mínimo 6 caracteres.";
        header("Location: cadastro_usuario.php");
        exit;
    }

    // 3. Verifica se o e-mail já existe
    $usuarios = [];
    if (file_exists($usuarios_file)) {
        $usuarios = file($usuarios_file); 
    }
    
    foreach ($usuarios as $usuario) {
        $dados = explode("|", trim($usuario));
        // O e-mail está na segunda posição (índice 1)
        if (count($dados) >= 2 && trim($dados[1]) === $email) {
            $_SESSION['cadastro_erro'] = "Este e-mail já está cadastrado. Tente outro ou faça login.";
            header("Location: cadastro_usuario.php");
            exit;
        }
    }

    // 4. Hash da Senha (Segurança)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // 5. Salva o novo usuário (Formato: Nome|Email|SenhaHash)
    $nova_linha = "$nome|$email|$senha_hash\n";
    
    // FILE_APPEND garante que a linha será adicionada ao final
    if (file_put_contents($usuarios_file, $nova_linha, FILE_APPEND)) {
        // 6. Sucesso
        $_SESSION['cadastro_sucesso'] = "Cadastro realizado com sucesso! Faça login para continuar.";
        header("Location: login_usuario.php"); // Redireciona para login
        exit;
    } else {
        // 6. Falha na Escrita (Pode ser permissão de pasta no servidor)
        $_SESSION['cadastro_erro'] = "Erro ao salvar o usuário. Verifique as permissões de escrita do arquivo.";
        header("Location: cadastro_usuario.php");
        exit;
    }

} else {
    // Redireciona se for acesso direto (sem POST)
    header("Location: cadastro_usuario.php");
    exit;
}
?>