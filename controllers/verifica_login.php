<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redireciona para a página de login
    exit();
    }
                        

  ?>