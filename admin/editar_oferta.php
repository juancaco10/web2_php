<?php
include '../includes/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../usuario/login.html');
    exit();
}

$oferta_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_oferta'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $empresa = $_POST['empresa'];
    $email_contacto = $_POST['email_contacto'];
    $imagen = $_FILES['imagen']['name'];

    $sql_update = "UPDATE ofertas SET 
        titulo = '$titulo', 
        descripcion = '$descripcion', 
        categoria = '$categoria', 
        empresa = '$empresa', 
        email_contacto = '$email_contacto'";

    if ($imagen) {
        $target_dir = "../imagenes/";
        $target_file = $target_dir . basename($imagen);
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
            $sql_update .= ", imagen = '$imagen'";
        } else {
            echo "Error al subir la imagen.";
            exit();
        }
    }

    $sql_update .= " WHERE id = $oferta_id";

    if ($conn->query($sql_update) === TRUE) {
        header("Location: postulaciones.php?id=$oferta_id");
        exit();
    }
     else {
        echo "Error al actualizar la oferta: " . $conn->error;
    }
}

$sql = "SELECT * FROM ofertas WHERE id = $oferta_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $oferta = $result->fetch_assoc();
} else {
    echo "Oferta no encontrada.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Oferta de Empleo</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        .formulario {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .formulario input[type="text"],
        .formulario textarea,
        .formulario select,
        .formulario input[type="file"],
        .formulario button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .formulario button {
            background: #ff6600;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .formulario button:hover {
            background: #e65c00;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Panel de Administrador</div>
            <ul>
                <li><a href="dashboard_admin.php">Inicio</a></li>
                <li><a href="crear_oferta.php">Crear Oferta</a></li>
                <li><a href="ver_ofertas.php">Ver Ofertas</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="formulario">
            <h1>Editar Oferta de Empleo</h1>
            <?php if (isset($_GET['success'])): ?>
                <p>Oferta actualizada correctamente.</p>
            <?php endif; ?>
            <form action="editar_oferta.php?id=<?php echo $oferta_id; ?>" method="post" enctype="multipart/form-data">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($oferta['titulo']); ?>" required>
                
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($oferta['descripcion']); ?></textarea>
                
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria" required>
                    <option value="Tiempo Completo" <?php if ($oferta['categoria'] == 'Tiempo Completo') echo 'selected'; ?>>Tiempo Completo</option>
                    <option value="Medio Tiempo" <?php if ($oferta['categoria'] == 'Medio Tiempo') echo 'selected'; ?>>Medio Tiempo</option>
                    <option value="Freelance" <?php if ($oferta['categoria'] == 'Freelance') echo 'selected'; ?>>Freelance</option>
                </select>
                
                <label for="empresa">Empresa:</label>
                <input type="text" id="empresa" name="empresa" value="<?php echo htmlspecialchars($oferta['empresa']); ?>" required>
                
                <label for="email_contacto">Correo de Contacto:</label>
                <input type="text" id="email_contacto" name="email_contacto" value="<?php echo htmlspecialchars($oferta['email_contacto']); ?>" required>
                
                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen">
                
                <button type="submit" name="actualizar_oferta">Actualizar Oferta</button>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Sistema de Anuncios de Empleo. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
