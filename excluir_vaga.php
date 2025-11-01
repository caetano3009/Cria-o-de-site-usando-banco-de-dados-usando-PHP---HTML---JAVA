<?php
session_start();
include 'conexao.php'; // Incluído a conexão com o DB

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST["id"])) {
    $id = (int) $_POST["id"]; // Agora pega o ID da vaga

    if ($id > 0) {
        $sql = "DELETE FROM vagas WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Sucesso ao deletar
        } else {
            // Erro
            die("Erro ao excluir: " . $conn->error);
        }
    }
}

$conn->close();
header("Location: index.php");
exit;
?>