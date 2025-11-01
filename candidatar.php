<?php
session_start();

// 1. Redireciona se o usuário não estiver logado
if (!isset($_SESSION["usuario_logado"])) {
    header("Location: login_usuario.php");
    exit;
}

// 2. Verifica se o ID da vaga foi passado na URL
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    // Redireciona de volta para a página inicial se não houver ID
    header("Location: index.php");
    exit;
}

$id_vaga = trim($_GET["id"]);
$email_usuario = $_SESSION["usuario_logado"]["email"];
$candidaturas_file = "candidaturas.txt";
$nova_candidatura = "$email_usuario|$id_vaga\n";

// 3. Verifica se o usuário já se candidatou a esta vaga (evita duplicidade)
$ja_candidatado = false;
if (file_exists($candidaturas_file)) {
    $candidaturas_existentes = file($candidaturas_file);
    foreach ($candidaturas_existentes as $linha) {
        // Formato: email_usuario|ID_VAGA
        list($email, $id) = explode("|", trim($linha));
        if ($email === $email_usuario && $id === $id_vaga) {
            $ja_candidatado = true;
            break;
        }
    }
}

// 4. Salva a nova candidatura, se não for duplicada
if (!$ja_candidatado) {
    file_put_contents($candidaturas_file, $nova_candidatura, FILE_APPEND);
    $_SESSION['feedback_candidatura'] = "Parabéns, você se candidatou com sucesso! A vaga ID $id_vaga foi adicionada ao seu perfil.";
} else {
    $_SESSION['feedback_candidatura'] = "Você já está candidatado a esta vaga!";
}

// 5. Redireciona para o Perfil
header("Location: perfil.php");
exit;
?>