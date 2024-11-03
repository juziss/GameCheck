<?php

require_once 'db.php';

$resultadosBusca = [];
$query = '';

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);

    $stmt = $conn->prepare("SELECT * FROM jogo WHERE nome_jogo LIKE ?");
    $likeQuery = "%" . $query . "%";
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()) {
        $resultadosBusca[] = $row;
    }

    if (!empty($resultadosBusca)) {
        foreach ($resultadosBusca as $jogo) {
            echo "<p>" . htmlspecialchars($jogo['nome_jogo']) . "</p>"; 
        }
    } else {
        echo "<p>Nenhum jogo encontrado.</p>";
    }

    $stmt->close();
}
?>
