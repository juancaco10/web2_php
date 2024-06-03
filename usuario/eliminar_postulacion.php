<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit();
}

include '../includes/conexion.php';

$id_usuario = $_SESSION['usuario_id'];
$id_oferta = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_oferta > 0) {
    $sql_delete = "DELETE FROM postulaciones WHERE id_usuario = $id_usuario AND id_oferta = $id_oferta";
    if ($conn->query($sql_delete) === TRUE) {
        echo "Postulación eliminada correctamente.";
    } else {
        echo "Error al eliminar la postulación: " . $conn->error;
    }
} else {
    echo "ID de oferta inválido.";
}

header('Location: detalle_oferta.php?id=' . $id_oferta);
exit();
?>
