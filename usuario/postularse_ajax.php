<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado.']);
    exit();
}
include '../includes/conexion.php';

if (isset($_POST['id'])) {
    $id_usuario = $_SESSION['usuario_id'];
    $id_oferta = intval($_POST['id']);

    $sql = "INSERT INTO postulaciones (id_usuario, id_oferta) VALUES ($id_usuario, $id_oferta)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID de oferta no proporcionado.']);
}
?>
