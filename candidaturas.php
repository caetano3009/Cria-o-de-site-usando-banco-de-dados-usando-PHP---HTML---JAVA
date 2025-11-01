<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["usuario_logado"])) {
    header("Location: login_usuario.php");
    exit;
}

// Verifica se o ID da vaga foi fornecido
if (!isset($_GET["id"]) || empty(trim($_GET["id"]))) {
    header("Location: index.php");
    exit;
}

$id_vaga = trim($_GET["id"]);
$email_usuario = $_SESSION["usuario_logado"]["email"];
$candidaturas_file = "candidaturas.txt";
$nova_candidatura = "$email_usuario|$id_vaga\n";

// Lê as candidaturas existentes
$candidaturas_atuais = file_exists($candidaturas_file) ? file($candidaturas_file) : [];
$ja_candidatado = false;

// Verifica se a candidatura já existe
foreach ($candidaturas_atuais as $linha) {
    list($email, $id) = explode("|", trim($linha));
    if ($email === $email_usuario && trim($id) === $id_vaga) {
        $ja_candidatado = true;
        break;
    }
}

// Se não for um duplicado, salva a nova candidatura
if (!$ja_candidatado) {
    file_put_contents($candidaturas_file, $nova_candidatura, FILE_APPEND);
}

// Redireciona de volta para a página inicial (ou para o perfil)
header("Location: index.php");
exit;
?>