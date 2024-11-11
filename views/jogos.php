<?php

require '../models/db.php';  // Conexão com o banco de dados
require '../controllers/verifica_login.php';  // Verificação de login

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php'); // Redireciona para a página de login se não estiver logado
    exit;
}

// Verifica se o nome do usuário está na sessão; se não, consulta no banco de dados
if (!isset($_SESSION['nome_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $query = "SELECT nome_usuario FROM usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($nome_usuario);

    if ($stmt->fetch()) {
        $_SESSION['nome_usuario'] = $nome_usuario;  // Armazena o nome do usuário na sessão
    }
    $stmt->close();
}
// Função para obter os gêneros do jogo
function getGenerosDoJogo($jogo_id, $conn) {
    $sql = "SELECT g.nome_genero 
            FROM Genero g 
            INNER JOIN Jogo_Genero jg ON g.id_genero = jg.id_genero 
            WHERE jg.id_jogo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $jogo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $generos = [];
    while ($row = $result->fetch_assoc()) {
        $generos[] = $row['nome_genero'];
    }
    
    return $generos;
}

// Inicialização das variáveis
$query = '';
$results = [];

// Verifica se o termo de busca foi enviado via GET
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Prepara a consulta SQL incluindo a média de notas
    $sql = "SELECT j.id_jogo, j.nome_jogo, j.desenvolvedora_jogo, j.ano_lancamento, j.capa_jogo, 
            COALESCE(AVG(a.nota), 0) as media_nota 
            FROM jogo j 
            LEFT JOIN Avaliacao a ON j.id_jogo = a.id_jogo 
            WHERE j.nome_jogo LIKE ? 
            GROUP BY j.id_jogo, j.nome_jogo, j.desenvolvedora_jogo, j.ano_lancamento, j.capa_jogo 
            LIMIT 10";

    // Prepara e executa a consulta
    if ($stmt = $conn->prepare($sql)) {
        $likeQuery = '%' . $query . '%';
        $stmt->bind_param("s", $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        // Armazena os resultados da consulta
        while ($row = $result->fetch_assoc()) {
            $row['generos'] = getGenerosDoJogo($row['id_jogo'], $conn);
            $results[] = $row;
        }

        $stmt->close();
    } else {
        echo "Erro na consulta ao banco de dados.";
    }
}


// Função para buscar plataformas do jogo
function getPlataformasJogo($id_jogo, $conn) {
    $sql = "SELECT p.nome_plataforma 
            FROM Plataforma p 
            JOIN Jogo_Plataforma jp ON p.id_plataforma = jp.id_plataforma 
            WHERE jp.id_jogo = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_jogo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $plataformas = [];
    while ($row = $result->fetch_assoc()) {
        $plataformas[] = $row['nome_plataforma'];
    }
    return $plataformas;
}

// Função para buscar gêneros do jogo
function getGenerosJogo($id_jogo, $conn) {
    $sql = "SELECT g.nome_genero 
            FROM Genero g 
            JOIN Jogo_Genero jg ON g.id_genero = jg.id_genero 
            WHERE jg.id_jogo = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_jogo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $generos = [];
    while ($row = $result->fetch_assoc()) {
        $generos[] = $row['nome_genero'];
    }
    return $generos;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resultados da Busca</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <link rel="stylesheet" href="../assets/css/busca-lista.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Jersey+10&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon.png" />
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">
            <img src="../assets/img/favicon.png" alt="GameCheck Logo" />
            <h2>GAMECHECK</h2>
        </div>
        <div class="links">
            <a href="#">Jogos</a>
            <a href="#">Favoritos</a>
            <a href="#">Minhas Listas</a>
        </div>
        <div class="dropdown">
            <a href="#"><?php echo isset($_SESSION['nome_usuario']) ? htmlspecialchars($_SESSION['nome_usuario']) : "Visitante"; ?>
                <img src="../assets/img/user-profile.jpg" alt="Foto de Perfil" class="profile-pic" id="profile-pic" />
            </a>
            <div class="menu">
                <a href="#">Perfil</a>
                <a href="#">Configurações</a>
                <a href="#">Sair</a>
            </div>
        </div>
    </nav>
</header>

<main>
        <div class="search-container">
            <div class="search-info">
                <?php if (!empty($query)): ?>
                    <p>Procurando por <span class="search-term">"<?php echo htmlspecialchars($query); ?>"</span></p>
                <?php else: ?>
                    <p>Digite um termo para buscar jogos.</p>
                <?php endif; ?>
            </div>
            <div class="search-bar">
                <form action="" method="get">
                    <input type="text" name="query" value="<?php echo htmlspecialchars($query); ?>" placeholder="Buscar jogos..." aria-label="Buscar jogos" />
                    <button type="submit" aria-label="Buscar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Search Results Section -->
            <div class="search-results">
                <?php if (count($results) > 0): ?>
                    <?php foreach ($results as $jogo): ?>
                        <a href="jogo-geral.php?id=<?php echo $jogo['id_jogo']; ?>" class="game-result">
                            <div class="game-header">
                                <h2 class="game-title"><?php echo htmlspecialchars($jogo['nome_jogo']); ?></h2>
                                <p class="game-year"><?php echo htmlspecialchars($jogo['ano_lancamento']); ?></p>
                            </div>
                            <div class="game-content">
                                <div class="game-image">
                                <?php if (!empty($jogo['capa_jogo']) && filter_var($jogo['capa_jogo'], FILTER_VALIDATE_URL)): ?>
                                    <img src="../assets/img/no-image.jpg" alt="Imagem não disponível" class="card-image" />
                                    <img src="<?php echo htmlspecialchars($capa_jogo); ?>" alt="<?php echo htmlspecialchars($jogo['nome_jogo']); ?>" class="card-image" />
                                    <?php else: ?>
                                    <?php
                                        $caminhoImagem = "../capas_jogos/" . htmlspecialchars($jogo['capa_jogo']) . ".jpg";
                                    ?>
                                    <img src="<?php echo $caminhoImagem; ?>" alt="Capa do Jogo">
                                <?php endif; ?>
                                </div>
                                <div class="game-details">
                                    <p class="developer">Desenvolvido por <span><?php echo htmlspecialchars($jogo['desenvolvedora_jogo']); ?></span></p>
                                    <div class="rating">
                                        <h4>Avaliação Geral</h4>
                                        <div class="stars" aria-label="Avaliação: <?php echo number_format($jogo['media_nota'], 1); ?> de 5 estrelas">
                                            <?php
                                            $rating = floatval($jogo['media_nota']);
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= floor($rating)) {
                                                    echo '<span class="star filled"></span>';
                                                } elseif ($i - $rating < 1 && $i - $rating > 0) {
                                                    echo '<span class="star half-filled"></span>';
                                                } else {
                                                    echo '<span class="star"></span>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="genres">
                                        <h3>Gêneros</h3>
                                        <div class="genre-tags">
                                            <?php foreach ($jogo['generos'] as $genero): ?>
                                                <span class="tag"><?php echo htmlspecialchars($genero); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="platform">
                                        <h3>Plataforma</h3>
                                        <?php 
                                        $plataformas = getPlataformasJogo($jogo['id_jogo'], $conn);
                                        if (count($plataformas) === 1): ?>
                                            <span class="tag">Monoplataforma</span>
                                        <?php else: ?>
                                            <?php foreach ($plataformas as $plataforma): ?>
                                                <span class="tag"><?php echo htmlspecialchars($plataforma); ?></span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum jogo encontrado.</p>
                <?php endif; ?>
            </div>

            <!-- Filters Section -->
            <div class="filters">
                <h3>Filtros</h3>
                <div class="filter-group">
                    <button class="filter-btn" onclick="buscarJogos('', 'melhores', '')">Melhores avaliados</button>
                    <button class="filter-btn" onclick="buscarJogos('', 'piores', '')">Piores avaliados</button>
                    <button class="filter-btn" onclick="buscarJogos('', 'recentes', '')">Mais recentes</button>
                    <button class="filter-btn" onclick="buscarJogos('', 'antigos', '')">Mais antigos</button>
                </div>
                <div class="filter-group">
                    <select name="genre" id="genre-filter" aria-label="Filtrar por gênero" onchange="aplicarFiltro()">
                        <option value="">Selecione um gênero</option>
                        <option value="action">Ação</option>
                        <option value="adventure">Aventura</option>
                        <option value="rpg">RPG</option>
                        <option value="strategy">Estratégia</option>
                        <option value="simulation">Simulação</option>
                    </select>
                </div>
                <div class="filter-group">
                    <select name="platform" id="platform-filter" aria-label="Filtrar por plataforma" onchange="aplicarFiltro()">
                        <option value="">Selecione a plataforma</option>
                        <option value="multi">Multiplataforma</option>
                        <option value="single">Monoplataforma</option>
                    </select>
                </div>
            </div>
        </div>
    </main>

<footer>
    <ul>
        <li><a href="#">Sobre</a></li>
        <li><a href="#">Ajuda</a></li>
        <li><a href="#">Contato</a></li>
    </ul>
    <p>
        Todos os direitos reservados © 2024 GAMECHECK. A avaliação dos jogos é
        uma expressão da comunidade.
    </p>
</footer>

</body>
</html>
