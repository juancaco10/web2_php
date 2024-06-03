<?php
include '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $empresa = $_POST['empresa'];
    $email_contacto = $_POST['contacto'];
    $imagen = $_FILES['imagen']['name'];

    move_uploaded_file($_FILES['imagen']['tmp_name'], "../imagenes/$imagen");

    $sql = "INSERT INTO ofertas (titulo, descripcion, categoria, empresa, email_contacto, imagen) VALUES ('$titulo', '$descripcion', '$categoria', '$empresa', '$email_contacto', '$imagen')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: crear_oferta.html?success=true");
        exit();
    } else {
        header("Location: crear_oferta.html?success=false&error=" . urlencode($conn->error));
        exit();
    }
}
?>
