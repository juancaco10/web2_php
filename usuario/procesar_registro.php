<?php
include '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $linkedin = $_POST['linkedin'];
    $rol = $_POST['rol'];

    // Manejo de la imagen
    $imagen = $_FILES['imagen']['name'];
    $target_dir = "../imagenes/";
    $target_file = $target_dir . basename($imagen);

    // Subir imagen
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO usuarios (nombre, email, contraseña, imagen, linkedin, rol) VALUES ('$nombre', '$email', '$password', '$imagen', '$linkedin', '$rol')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                window.onload = function() {
                    if (window.opener && !window.opener.closed) {
                        window.opener.mostrarMensaje('Registro exitoso. Redirigiendo a inicio de sesión...');
                        window.close();
                    } else {
                        document.write('<h1>Registro exitoso. Redirigiendo a inicio de sesión...</h1>');
                        setTimeout(function() {
                            window.location.href = 'login.html';
                        }, 3000);
                    }
                }
            </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>
