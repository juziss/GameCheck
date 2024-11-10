<?php
session_start();  // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo "Erro: Usuário não está logado.";
    exit;
}

// Conexão com o banco de dados
require_once '../models/db.php';

// Obtém os dados enviados pelo formulário
$nota = isset($_POST['nota']) ? intval($_POST['nota']) : 0;
$avaliacao = isset($_POST['avaliacao']) ? trim($_POST['avaliacao']) : '';
$id_jogo = isset($_POST['id_jogo']) ? intval($_POST['id_jogo']) : 0;
$id_usuario = $_SESSION['id_usuario'];  // Obtém o id do usuário da sessão

// Valida os dados
if ($nota < 1 || $nota > 5) {
    echo "Erro: A nota deve ser entre 1 e 5.";
    exit;
}

if (empty($avaliacao)) {
    echo "Erro: A avaliação não pode estar vazia.";
    exit;
}

if ($id_jogo <= 0) {
    echo "Erro: Jogo inválido.";
    exit;
}

// Prepara a consulta SQL para inserir a avaliação no banco de dados
$sql = "INSERT INTO Avaliacao (id_usuario, id_jogo, nota, avaliacao) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $id_usuario, $id_jogo, $nota, $avaliacao);

// Executa a consulta
if ($stmt->execute()) {
    echo "Avaliação salva com sucesso!";
} else {
    echo "Erro ao salvar a avaliação.";
}
?>
