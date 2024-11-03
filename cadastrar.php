<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $confirmacao = isset($_POST['confirmacao']) ? trim($_POST['confirmacao']) : '';

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE email_usuario = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Este email já está cadastrado.'); window.location.href = 'index.php';</script>";
        exit(); 
    } else {
        
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuario (nome_usuario, email_usuario, senha_usuario) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $nome, $email, $senhaHash);

        if ($stmt->execute()) {
            echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href = 'index.php';</script>";
        } else {
            echo "Erro: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
