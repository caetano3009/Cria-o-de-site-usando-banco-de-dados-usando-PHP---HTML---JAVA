<?php
session_start();

// #############################################
// 1. DADOS DE DEMONSTRAÇÃO (4 vagas com Salário e ID)
//    Formato: Título|Área|Descrição|Contato|Tipo|Salário|ID_VAGA
// #############################################
$vagas_demo_text = [
    "Engenheiro de Software ADS|Tecnologia (Google)|Desenvolvimento de soluções de publicidade digital e machine learning. Requer Python e C++.|rh@google.com|Remoto|R$ 15.000,00|VAGA001",
    "Analista de Qualidade e Processos|Controle de Produção (Samsung)|Monitorar e analisar processos de fabricação de dispositivos móveis. Necessário experiência em Six Sigma.|suporte@samsung.br|Presencial|R$ 7.500,00|VAGA002",
    "Stormtrooper |Segurança e Ordem (Rep. Star Wars)|Garantir a ordem na República Galáctica, sob as ordens do Imperador. Mira ruim é opcional.|imperador@starwars.com|Híbrido|2.000 Créditos|VAGA003",
    "Estagiário de P&D (Armas)|Pesquisa e Desenvolvimento (Torre Vingadores)|Auxiliar a equipe de Tony Stark no desenvolvimento de novas tecnologias de armamento e IA. Conhecimento em robótica é diferencial.|rh@vingadores.com|Presencial|R$ 2.500,00|VAGA004"
];

// Lê o arquivo de vagas existentes (se houver) e combina com as de demonstração
$vagas_arquivo = file_exists("vagas.txt") ? file("vagas.txt") : [];
// Se o arquivo estiver vazio, usamos apenas as vagas de demonstração
$vagas_raw = empty($vagas_arquivo) ? $vagas_demo_text : $vagas_arquivo;


// Filtros e processamento de vagas
$busca = isset($_GET["busca"]) ? strtolower(trim($_GET["busca"])) : "";
$areaFiltro = isset($_GET["area"]) ? strtolower(trim($_GET["area"])) : "";
$tipoFiltro = isset($_GET["tipo"]) ? strtolower(trim($_GET["tipo"])) : "";

// #############################################
// 2. Lógica de Candidatura (Carrega Candidaturas do Usuário)
// #############################################
$candidaturas = [];
if (isset($_SESSION["usuario_logado"])) {
    $email_usuario = $_SESSION["usuario_logado"]["email"]; 
    $candidaturas_file = file_exists("candidaturas.txt") ? file("candidaturas.txt") : [];
    foreach ($candidaturas_file as $linha) {
        // Formato da linha: email_usuario|ID_VAGA
        $partes = explode("|", trim($linha));
        if (count($partes) === 2) {
            list($email, $id_vaga) = $partes;
            if (trim($email) === $email_usuario) {
                $candidaturas[] = trim($id_vaga);
            }
        }
    }
}


$vagas_filtradas = [];
$areas_disponiveis = [];

foreach ($vagas_raw as $vaga) {
    $dados = explode("|", trim($vaga));
    // Precisa ter pelo menos 7 campos: 0:Titulo, 1:Area, 2:Desc, 3:Contato, 4:Tipo, 5:Salario, 6:ID_VAGA
    if (count($dados) < 7) continue; 

    list($titulo, $area, $descricao, $contato, $tipo, $salario, $id_vaga) = $dados;
    $id_vaga = trim($id_vaga);
    
    $texto_vaga = strtolower("$titulo $area $descricao $contato $tipo $salario $id_vaga");

    $ok_busca = ($busca === "" || strpos($texto_vaga, $busca) !== false);
    $ok_area = ($areaFiltro === "" || strtolower($area) === strtolower($areaFiltro));
    $ok_tipo = ($tipoFiltro === "" || strtolower($tipo) === strtolower($tipoFiltro));

    // Filtra áreas para o menu
    if ($area !== "" && !in_array($area, $areas_disponiveis)) {
        $areas_disponiveis[] = $area;
    }
    
    if ($ok_busca && $ok_area && $ok_tipo) {
        $vagas_filtradas[] = $dados;
    }
}
sort($areas_disponiveis);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Impulso Estágio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content-wrapper">
             <h1>
                <a href="index.php">
                    <i class="fas fa-bullhorn"></i> Impulso Estágio
                </a>
            </h1>

            <div class="perfil-usuario-area">
                <?php if(isset($_SESSION["admin"])): // Admin Logado ?>
                    <a href="cadastrar.php" class="btn-cadastro-usuario"><i class="fas fa-plus-circle"></i> Cadastrar Vaga</a>
                    <a href="logout.php" class="btn-admin"><i class="fas fa-lock-open"></i> Sair (Admin)</a>
                <?php elseif(isset($_SESSION["usuario_logado"])): // Usuário Comum Logado ?>
                    <a href="perfil.php" class="btn-login-usuario"><i class="fas fa-user-circle"></i> Meu Perfil</a>
                    <a href="logout.php" class="btn-admin"><i class="fas fa-sign-out-alt"></i> Sair</a>
                <?php else: // Ninguém Logado ?>
                    <a href="cadastro_usuario.php" class="btn-cadastro-usuario"><i class="fas fa-user-plus"></i> Cadastro Usuário</a>
                    <a href="login_usuario.php" class="btn-login-usuario"><i class="fas fa-sign-in-alt"></i> Login Usuário</a>
                    <a href="login.php" class="btn-admin"><i class="fas fa-lock"></i> Área Admin</a>
                <?php endif; ?>
            </div>
        </div>

        <nav>
            <a href="index.php"><i class="fas fa-home"></i> Início</a>
            <?php if(isset($_SESSION["admin"])): ?>
                <a href="cadastrar.php"><i class="fas fa-clipboard-list"></i> Cadastrar Vaga</a> 
            <?php endif; ?>
            <?php foreach ($areas_disponiveis as $a): // Links de filtro (Áreas) ?>
                <a href="index.php?area=<?= urlencode(strtolower($a)) ?>" class="filtro-area">
                    <?= htmlspecialchars($a) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </header>

    <main>
        <h2>Filtrar Vagas</h2>
        <form method="GET" action="index.php" class="form-filtros">
            <input type="text" name="busca" placeholder="Buscar por palavra..." 
                   value="<?= htmlspecialchars($busca) ?>">

            <select name="area">
                <option value="">Todas as áreas</option>
                <?php foreach ($areas_disponiveis as $a): ?>
                    <option value="<?= urlencode(strtolower($a)) ?>" <?= strtolower($areaFiltro) === strtolower($a) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="tipo">
                <option value="">Todos os tipos</option>
                <option value="presencial" <?= $tipoFiltro === "presencial" ? 'selected' : '' ?>>Presencial</option>
                <option value="remoto" <?= $tipoFiltro === "remoto" ? 'selected' : '' ?>>Remoto</option>
                <option value="híbrido" <?= $tipoFiltro === "híbrido" ? 'selected' : '' ?>>Híbrido</option>
            </select>

            <button type="submit"><i class="fas fa-search"></i> Filtrar</button>
            <?php if($busca || $areaFiltro || $tipoFiltro): ?>
                <a href="index.php" class="btn-limpar">Limpar</a>
            <?php endif; ?>
        </form>

        <h2>Vagas disponíveis</h2>

        <?php if (count($vagas_filtradas) > 0): ?>
            <ul class="lista-vagas">
                <?php foreach ($vagas_filtradas as $dados): 
                    // 0:Titulo, 1:Area, 2:Desc, 3:Contato, 4:Tipo, 5:Salario, 6:ID_VAGA
                    list($titulo, $area, $descricao, $contato, $tipo, $salario, $id_vaga) = $dados;
                    $id_vaga = trim($id_vaga);
                    // Verifica se o ID_VAGA está na lista de candidaturas do usuário logado
                    $ja_candidatado = in_array($id_vaga, $candidaturas);
                ?>
                    <li>
                        <h3><?= htmlspecialchars($titulo) ?></h3>
                        <p><strong>Empresa/Área:</strong> <?= htmlspecialchars($area) ?></p>
                        <p><strong>Salário:</strong> <span style="font-weight: bold; color: #28a745;"><?= htmlspecialchars($salario) ?></span></p>
                        <p><strong>Tipo:</strong> <?= htmlspecialchars($tipo) ?></p>
                        <p><strong>Descrição:</strong> <?= htmlspecialchars($descricao) ?></p>
                        <p><strong>Contato:</strong> <?= htmlspecialchars($contato) ?></p>
                        
                        <?php if(isset($_SESSION["admin"])): ?>
                            <form action="excluir_vaga.php" method="POST" style="margin-top:10px;">
                                <input type="hidden" name="id_vaga" value="<?= $id_vaga ?>">
                                <button type="submit" class="btn-excluir"><i class="fas fa-trash-alt"></i> Excluir</button>
                            </form>
                        <?php elseif(isset($_SESSION["usuario_logado"])): ?>
                            <a href="candidatar.php?id=<?= urlencode($id_vaga) ?>" 
                               class="btn-candidatar <?= $ja_candidatado ? 'cadastrado' : '' ?>">
                                <?= $ja_candidatado ? '<i class="fas fa-check-circle"></i> Candidatado' : '<i class="fas fa-paper-plane"></i> Candidatar-se' ?>
                            </a>
                        <?php else: ?>
                            <a href="login_usuario.php" class="btn-candidatar"><i class="fas fa-sign-in-alt"></i> Faça Login para Candidatar-se</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="sem-vagas">Nenhuma vaga encontrada com esses filtros 😕</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>© 2025 Impulso Estágio | Desenvolvido por Jimmy Peakes 😎</p>
    </footer>
</body>
</html>