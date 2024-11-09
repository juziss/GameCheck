<?php
session_start();
require 'verifica_login.php';
header('Content-Type: application/json');

// Verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Usuário não está logado'
    ]);
    exit;
}

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método de requisição inválido'
    ]);
    exit;
}

// Validar dados recebidos
$id_usuario = $_SESSION['id_usuario'];
$id_jogo = filter_input(INPUT_POST, 'id_jogo', FILTER_VALIDATE_INT);
$nota = filter_input(INPUT_POST, 'nota', FILTER_VALIDATE_INT);
$avaliacao = filter_input(INPUT_POST, 'avaliacao', FILTER_SANITIZE_STRING);

// Validar os valores recebidos
if (!$id_jogo || !$nota || !$avaliacao) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Dados inválidos'
    ]);
    exit;
}

// Validar a nota (entre 1 e 5)
if ($nota < 1 || $nota > 5) {
    echo json_encode([
        'status' => 'error',
        'message' => 'A nota deve ser entre 1 e 5'
    ]);
    exit;
}

// Validar o tamanho do comentário
if (strlen($avaliacao) > 250) {
    echo json_encode([
        'status' => 'error',
        'message' => 'O comentário não pode ter mais de 250 caracteres'
    ]);
    exit;
}

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gamecheck";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Conexão falhou: " . $conn->connect_error);
    }

    // Verificar se o jogo existe
    $stmt = $conn->prepare("SELECT id_jogo FROM Jogo WHERE id_jogo = ?");
    $stmt->bind_param("i", $id_jogo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Jogo não encontrado");
    }

    // Verificar se já existe uma avaliação deste usuário para este jogo
    $stmt = $conn->prepare("SELECT id_avaliacao FROM Avaliacao WHERE id_usuario = ? AND id_jogo = ?");
    $stmt->bind_param("ii", $id_usuario, $id_jogo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Atualizar avaliação existente
        $stmt = $conn->prepare("
            UPDATE Avaliacao 
            SET nota = ?, 
                avaliacao = ?, 
                data_avaliacao = NOW() 
            WHERE id_usuario = ? AND id_jogo = ?
        ");
        $stmt->bind_param("isii", $nota, $avaliacao, $id_usuario, $id_jogo);
        
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Avaliação atualizada com sucesso!'
            ]);
        } else {
            throw new Exception("Erro ao atualizar avaliação");
        }
    } else {
        // Inserir nova avaliação
        $stmt = $conn->prepare("
            INSERT INTO Avaliacao (id_usuario, id_jogo, nota, avaliacao, data_avaliacao) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("iiis", $id_usuario, $id_jogo, $nota, $avaliacao);
        
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Avaliação salva com sucesso!'
            ]);
        } else {
            throw new Exception("Erro ao salvar avaliação");
        }
    }

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>