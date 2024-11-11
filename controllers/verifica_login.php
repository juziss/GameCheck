<?php
session_start();  // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");  // Redireciona para o login se não estiver logado
    exit();
}
?>
