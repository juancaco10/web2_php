<?php
$servidor = "localhost";
$usuario = "root";
$contraseña = "Juancaco10";
$bd = "anuncios_empleo";

$conn = new mysqli($servidor, $usuario, $contraseña, $bd);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
