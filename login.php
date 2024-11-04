<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE email_usuario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        if (password_verify($senha, $usuario['senha_usuario'])) {
            $_SESSION['user'] = $email;
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Senha incorreta.'); window.location.href = 'index.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('E-mail não encontrado.'); window.location.href = 'index.php';</script>";
        exit();
    }
    
}
?>
