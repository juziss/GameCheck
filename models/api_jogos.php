<?php
require 'db.php';

// Definir o tipo de resposta como JSON
header('Content-Type: text/html; charset=UTF-8');

// Captura os parâmetros de filtro da URL (GET)
$query = isset($_GET['query']) ? $_GET['query'] : '';
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$platform = isset($_GET['platform']) ? $_GET['platform'] : '';

// Montar a consulta SQL com base nos parâmetros fornecidos
$sql = "SELECT id_jogo, nome_jogo, desenvolvedora_jogo, ano_lancamento, capa_jogo 
        FROM Jogo 
        WHERE nome_jogo LIKE ?";
$params = ['%' . $query . '%'];

// Adicionar filtros se estiverem disponíveis
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
    echo json_encode(["error" => "Erro ao preparar a consulta SQL: " . $conn->error]);
    exit;
}

// Vincular os parâmetros
$stmt->bind_param(str_repeat('s', count($params)), ...$params);

// Executar a consulta
if (!$stmt->execute()) {
    echo json_encode(["error" => "Erro ao executar a consulta SQL: " . $stmt->error]);
    exit;
}

// Obter o resultado da consulta
$result = $stmt->get_result();

// Armazenar os dados em um array para JSON
$games = [];
while ($row = $result->fetch_assoc()) {
    $games[] = $row;
}

// Retornar o resultado em formato JSON
echo json_encode($games);

// Fechar a conexão
$stmt->close();
$conn->close();
?>
