<?php
require '../models/db.php';  // Conexão com o banco de dados
require '../controllers/verifica_login.php';  // Verificação de login

// Inicialização das variáveis
$query = '';
$results = [];

// Verifica se o termo de busca foi enviado via GET
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Prepara a consulta SQL
    $sql = "SELECT id_jogo, nome_jogo, desenvolvedora_jogo, ano_lancamento, capa_jogo FROM jogo WHERE nome_jogo LIKE ? LIMIT 10";

    // Prepara e executa a consulta
    if ($stmt = $conn->prepare($sql)) {
        $likeQuery = '%' . $query . '%';
        $stmt->bind_param("s", $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        // Armazena os resultados da consulta
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }

        $stmt->close();
    } else {
        echo "Erro na consulta ao banco de dados.";
    }
}

if (isset($_GET['genero'])) {
    $genero = $_GET['genero'];
    
    $sql = "SELECT Jogo.id_jogo, Jogo.nome_jogo, Jogo.capa_jogo 
            FROM Jogo
            JOIN Jogo_Genero ON Jogo.id_jogo = Jogo_Genero.id_jogo
            JOIN Genero ON Jogo_Genero.id_genero = Genero.id_genero
            WHERE Genero.nome_genero = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $genero);
    $stmt->execute();
    $result = $stmt->get_result();

    // Renderizar os jogos no formato de cards
    while ($jogo = $result->fetch_assoc()) {
        echo "<div class='jogo-card'>";
        echo "<img src='capas_jogos/{$jogo['capa_jogo']}.jpg' alt='{$jogo['nome_jogo']}' />";
        echo "<h3>{$jogo['nome_jogo']}</h3>";
        echo "</div>";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />

?>
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
            <a href="#"><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : "Visitante"; ?>
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
        <div id="search-results"class="search-results">
            <?php if (count($results) > 0): ?>
                <?php foreach ($results as $jogo): ?>
                    <a href="jogo-geral.php?id=<?php echo $jogo['id_jogo']; ?>" class="game-result">
                        <div class="game-header">
                            <h2 class="game-title"><?php echo htmlspecialchars($jogo['nome_jogo']); ?></h2>
                            <p class="game-year"><?php echo htmlspecialchars($jogo['ano_lancamento']); ?></p>
                        </div>
                        <div class="game-content">
                        <?php if (!empty($jogo['capa_jogo']) && filter_var($jogo['capa_jogo'], FILTER_VALIDATE_URL)): ?>
                        <img src="../assets/img/no-image.jpg" alt="Imagem não disponível" class="card-image" />
                        <img src="<?php echo htmlspecialchars($capa_jogo); ?>" alt="<?php echo htmlspecialchars($jogo['nome_jogo']); ?>" class="card-image" />
                        <?php else: ?>
                        <?php
                            $caminhoImagem = "../capas_jogos/" . htmlspecialchars($jogo['capa_jogo']) . ".jpg";
                        ?>
                        <img src="<?php echo $caminhoImagem; ?>" alt="Capa do Jogo" width="200px">
                        <?php endif; ?>
                            <div class="game-details">
                                <p class="developer">Desenvolvido por <span><?php echo htmlspecialchars($jogo['desenvolvedora_jogo']); ?></span></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum jogo encontrado.</p>
            <?php endif; ?>
            
        </div>

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
