<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'gamecheck';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Definir o conjunto de caracteres como UTF-8
$conn->set_charset("utf8mb4");

?>
