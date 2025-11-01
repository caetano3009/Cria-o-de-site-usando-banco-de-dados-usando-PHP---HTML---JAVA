<?php include 'conexao.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST["titulo"]);
    $area = trim($_POST["area"]);
    $descricao = trim($_POST["descricao"]);
    $contato = trim($_POST["contato"]);
    $tipo = trim($_POST["tipo"]);

    if ($titulo && $area && $descricao && $contato && $tipo) {
        
        $sql = "INSERT INTO vagas (titulo, localizacao, descricao, salario, id_usuario)
                VALUES ('$titulo', '$area', '$descricao', '$contato', '$tipo')";

        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit;
        } else {
            echo "Erro ao cadastrar: " . $conn->error;
        }

    } else {
        echo "Preencha todos os campos corretamente!";
    }
} else {
    header("Location: index.php");
    exit;
}

$conn->close();
?>
