<?php
include '../includes/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $linkedin = $_POST['linkedin'];
    $imagen = $_FILES['imagen']['name'];

    $sql = "UPDATE usuarios SET nombre='$nombre', email='$email', linkedin='$linkedin'";

    if ($password) {
        $sql .= ", contraseña='$password'";
    }

    if ($imagen) {
        $target_dir = "../imagenes/";
        $target_file = $target_dir . basename($imagen);
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
            $sql .= ", imagen='$imagen'";
        } else {
            echo "Error al subir la imagen.";
            exit();
        }
    }

    $sql .= " WHERE id='$usuario_id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['nombre'] = $nombre;
        header('Location: perfil.php?success=1');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
