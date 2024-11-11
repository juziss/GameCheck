<?php
session_start();  // Inicia a sessão

require_once '../models/db.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o usuário existe no banco de dados
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE email_usuario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        // Verifica a senha
        if (password_verify($senha, $usuario['senha_usuario'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];  // Define a variável de sessão
            header("Location: home.php");  // Redireciona para a página inicial após login
            exit();
        } else {
            echo "<script>alert('Senha incorreta.'); window.location.href = '../index.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('E-mail não encontrado.'); window.location.href = '../index.php';</script>";
        exit();
    }
}
?>
