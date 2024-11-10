<?php
require_once 'db.php';

$resultadosBusca = [];
$query = '';

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);

    // Prepara a consulta SQL para buscar jogos com o nome similar ao que foi digitado
    $stmt = $conn->prepare("SELECT id_jogo, nome_jogo, desenvolvedora_jogo, ano_lancamento, capa_jogo FROM jogo WHERE nome_jogo LIKE ? LIMIT 10");
    $likeQuery = "%" . $query . "%";
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()) {
        $resultadosBusca[] = $row;
    }

    $stmt->close();

    // Se encontrou jogos, exibe os resultados
    if (!empty($resultadosBusca)) {
        foreach ($resultadosBusca as $jogo) {
            echo "<div class='game-result'>";
            echo "<h3>" . htmlspecialchars($jogo['nome_jogo']) . "</h3>";
            echo "<p><strong>Desenvolvedora:</strong> " . htmlspecialchars($jogo['desenvolvedora_jogo']) . "</p>";
            echo "<p><strong>Ano de Lançamento:</strong> " . htmlspecialchars($jogo['ano_lancamento']) . "</p>";

            // Verifica se a capa é uma URL ou BLOB
            if (!empty($jogo['capa_jogo']) && filter_var($jogo['capa_jogo'], FILTER_VALIDATE_URL)) {
                echo "<img src='" . htmlspecialchars($jogo['capa_jogo']) . "' alt='" . htmlspecialchars($jogo['nome_jogo']) . "' class='game-cover' />";
            } else {
                echo "<img src='./assets/img/no-image.jpg' alt='Imagem não disponível' class='game-cover' />";
            }

            echo "<a href='jogo-geral.php?id=" . $jogo['id_jogo'] . "'>Ver detalhes</a>";
            echo "</div>";
        }
    } else {
        echo "<p>Nenhum jogo encontrado para sua busca.</p>";
    }
}

?>
