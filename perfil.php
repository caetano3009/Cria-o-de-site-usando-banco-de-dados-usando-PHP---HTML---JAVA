<?php
session_start();

// 1. Verifica se o usuário está logado. Se não, redireciona para o login.
if (!isset($_SESSION["usuario_logado"])) {
    header("Location: login_usuario.php");
    exit;
}

$nome_usuario = $_SESSION["usuario_logado"]["nome"];
$email_usuario = $_SESSION["usuario_logado"]["email"];
$feedback = isset($_SESSION['feedback_candidatura']) ? $_SESSION['feedback_candidatura'] : "";

// Limpa o feedback após exibir
if ($feedback) {
    unset($_SESSION['feedback_candidatura']);
}

// #############################################
// 2. Carregar todas as vagas disponíveis (incluindo as de demonstração)
// #############################################
$vagas_demo_text = [
    "Engenheiro de Software ADS|Tecnologia (Google)|Desenvolvimento...|rh@google.com|Remoto|R$ 15.000,00|VAGA001",
    "Analista de Qualidade e Processos|Controle de Produção (Samsung)|Monitorar...|suporte@samsung.br|Presencial|R$ 7.500,00|VAGA002",
    "Stormtrooper |Segurança e Ordem (Rep. Star Wars)|Garantir a ordem...|imperador@starwars.com|Híbrido|2.000 Créditos|VAGA003",
    "Estagiário de P&D (Armas)|Pesquisa e Desenvolvimento (Torre Vingadores)|Auxiliar a equipe...|rh@vingadores.com|Presencial|R$ 2.500,00|VAGA004"
];
$vagas_arquivo = file_exists("vagas.txt") ? file("vagas.txt") : [];
$vagas_raw = empty($vagas_arquivo) ? $vagas_demo_text : $vagas_arquivo;

$todas_vagas = [];
foreach ($vagas_raw as $vaga) {
    $dados = explode("|", trim($vaga));
    if (count($dados) < 7) continue; 
    
    // 0:Titulo, 1:Area, ..., 6:ID_VAGA
    $id_vaga = trim($dados[6]);
    $todas_vagas[$id_vaga] = $dados;
}

// #############################################
// 3. Identificar quais vagas o usuário se candidatou
// #############################################
$vagas_candidatadas = [];
if (file_exists("candidaturas.txt")) {
    $candidaturas = file("candidaturas.txt");
    foreach ($candidaturas as $linha) {
        list($email, $id_vaga) = explode("|", trim($linha));
        $id_vaga = trim($id_vaga);
        
        // Se a candidatura for do usuário logado E a vaga existir na lista
        if ($email === $email_usuario && isset($todas_vagas[$id_vaga])) {
            $vagas_candidatadas[$id_vaga] = $todas_vagas[$id_vaga];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Candidaturas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content-wrapper">
            <h1>
                <a href="index.php">
                    <i class="fas fa-user-circle"></i> Meu Perfil: <?= htmlspecialchars($nome_usuario) ?>
                </a>
            </h1>
            <div class="perfil-usuario-area">
                 <a href="index.php" class="btn-inicio-cabecalho"><i class="fas fa-home"></i> Início</a>
                 <a href="logout.php" class="btn-admin"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </div>
        </div>
        <nav></nav>
    </header>

    <main>
        <h2><i class="fas fa-briefcase"></i> Minhas Candidaturas</h2>

        <?php if($feedback): ?>
            <p style="color: green; font-weight: bold; text-align: center; margin-bottom: 25px; padding: 10px; border: 1px solid green; background-color: #e6ffe6;">
                <?= $feedback ?>
            </p>
        <?php endif; ?>

        <?php if (count($vagas_candidatadas) > 0): ?>
            <ul class="lista-vagas">
                <?php foreach ($vagas_candidatadas as $id_vaga => $dados): 
                    // 0:Titulo, 1:Area, 2:Desc, 3:Contato, 4:Tipo, 5:Salario, 6:ID_VAGA
                    list($titulo, $area, $descricao, $contato, $tipo, $salario) = $dados;
                ?>
                    <li>
                        <h3><?= htmlspecialchars($titulo) ?> <span style="font-size: 0.8em; color: gray;">(ID: <?= $id_vaga ?>)</span></h3>
                        <p><strong>Empresa/Área:</strong> <?= htmlspecialchars($area) ?></p>
                        <p><strong>Salário:</strong> <span style="font-weight: bold; color: #28a745;"><?= htmlspecialchars($salario) ?></span></p>
                        <p><strong>Tipo:</strong> <?= htmlspecialchars($tipo) ?></p>
                        <p><strong>Status:</strong> <span style="color: #007bff; font-weight: bold;">Candidatura Registrada</span></p>
                        <p style="margin-top: 10px;">Entre em contato com a empresa pelo e-mail: **<?= htmlspecialchars($contato) ?>**</p>
                        
                        </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="sem-vagas" style="text-align: center;">Você ainda não se candidatou a nenhuma vaga. <a href="index.php">Veja as vagas disponíveis!</a></p>
        <?php endif; ?>
    </main>

    <footer>
        <p>© 2025 Impulso Estágio | Desenvolvido por Jimmy Peakes 😎</p>
    </footer>
</body>
</html>