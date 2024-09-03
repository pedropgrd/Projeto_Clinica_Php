<?php
$servidor = "localhost:3307";
$banco = "db_clinica";
$usuario = "root";
$senha = "";

$mysqli = new mysqli($servidor, $usuario, $senha, $banco);

if ($mysqli->connect_error) {
    die("Falha ao conectar: " . $mysqli->connect_error);
}
?>
