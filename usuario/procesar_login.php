<?php
include '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta SQL para obtener el usuario por email
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        // Verificar la contraseña según el rol
        if ($usuario['rol'] == 'admin') {
            // Para administradores, verificar la contraseña sin encriptar
            if ($password == $usuario['contraseña']) {
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['rol'] = $usuario['rol'];
                header('Location: ../admin/dashboard_admin.php');
                exit();
            } else {
                echo "Contraseña incorrecta";
            }
        } else {
            // Para usuarios, verificar la contraseña encriptada
            if (password_verify($password, $usuario['contraseña'])) {
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['rol'] = $usuario['rol'];
                header('Location: dashboard_usuario.php');
                exit();
            } else {
                echo "Contraseña incorrecta";
            }
        }
    } else {
        echo "Usuario no encontrado o no autorizado";
    }
}
?>
