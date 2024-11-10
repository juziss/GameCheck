<!-- <?php
require '../models/db.php';

// Definir a resposta como HTML
header('Content-Type: text/html; charset=UTF-8');

// Captura os parâmetros de filtro da URL (GET)
$query = isset($_GET['query']) ? $_GET['query'] : '';
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$platform = isset($_GET['platform']) ? $_GET['platform'] : '';

// Construir a consulta SQL com base nos parâmetros fornecidos
$sql = "SELECT id_jogo, nome_jogo, desenvolvedora_jogo, ano_lancamento, capa_jogo FROM Jogo WHERE nome_jogo LIKE ?";
$params = ['%' . $query . '%'];

// Adicionar filtros se houverem
if (!empty($genre)) {
    $sql .= " AND genero_jogo LIKE ?";
    $params[] = '%' . $genre . '%';
}

if (!empty($platform)) {
    $sql .= " AND plataforma_jogo LIKE ?";
    $params[] = '%' . $platform . '%';
}

// Preparar a consulta SQL
$stmt = $conn->prepare($sql);

// Verificar se a preparação foi bem-sucedida
if ($stmt === false) {
    echo "<p>Erro ao preparar a consulta SQL.</p>";
    exit;
}

// Vincular os parâmetros
$stmt->bind_param(str_repeat('s', count($params)), ...$params);

// Executar a consulta
if (!$stmt->execute()) {
    echo "<p>Erro ao executar a consulta SQL.</p>";
    exit;
}

// Obter os resultados da consulta
$result = $stmt->get_result();

// Verificar se retornou jogos
// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         // Gerar a listagem de jogos
//         echo '<a href="jogo-geral.php?id=' . $row['id_jogo'] . '" class="game-result">';
//         echo '<div class="game-header">';
//         echo '<h2 class="game-title">' . $row['nome_jogo'] . '</h2>';
//         echo '<p class="game-year">' . $row['ano_lancamento'] . '</p>';
//         echo '</div>';
//         echo '<div class="game-content">';
//         echo '<div class="game-image">';
//         echo '<img src="../assets/img/' . $row['capa_jogo'] . '" alt="' . $row['nome_jogo'] . '" />';
//         echo '</div>';
//         echo '<div class="game-details">';
//         echo '<p class="developer">Desenvolvido por <span>' . $row['desenvolvedora_jogo'] . '</span></p>';
//         echo '</div>';
//         echo '</div>';
//         echo '</a>';
//     }
// } else {
//     echo "<p>Nenhum jogo encontrado.</p>";
// }

// Fechar a conexão
// $stmt->close();
// $conn->close();
// ?> -->
