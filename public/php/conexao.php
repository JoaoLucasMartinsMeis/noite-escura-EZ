<?php
$host = "localhost";
$usuario = "root";
$senha = "root";
$banco = "noite_escura";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
