<?php
include '../includes/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../usuario/login.html');
    exit();
}

$oferta_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql_oferta = "SELECT * FROM ofertas WHERE id = $oferta_id";
$result_oferta = $conn->query($sql_oferta);

if ($result_oferta->num_rows > 0) {
    $oferta = $result_oferta->fetch_assoc();
} else {
    echo "Oferta no encontrada.";
    exit();
}

$sql_postulaciones = "SELECT p.*, u.nombre, u.email, u.linkedin, u.imagen 
                      FROM postulaciones p 
                      JOIN usuarios u ON p.id_usuario = u.id 
                      WHERE p.id_oferta = $oferta_id";
$result_postulaciones = $conn->query($sql_postulaciones);

$postulaciones = [];
if ($result_postulaciones->num_rows > 0) {
    while ($row = $result_postulaciones->fetch_assoc()) {
        $postulaciones[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de la Oferta de Empleo</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        .detalles-oferta {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            align-content: center;
        }
        .detalles-oferta img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .detalles-oferta p {
            margin: 10px 0;
        }
        .postulaciones {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 150px;
        }
        .postulacion {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .postulacion img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        .btn {
            background: #ff6600;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background 0.3s;
            text-align: center;
            display: block;
            margin: 20px auto;
            width: 200px;
        }
        .btn:hover {
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
        <div class="detalles-oferta">
            <h1><?php echo htmlspecialchars($oferta['titulo']); ?></h1>
            <img src="../imagenes/<?php echo htmlspecialchars($oferta['imagen']); ?>" alt="Logo de Empresa">
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($oferta['descripcion']); ?></p>
            <p><strong>Categoría:</strong> <?php echo htmlspecialchars($oferta['categoria']); ?></p>
            <p><strong>Empresa:</strong> <?php echo htmlspecialchars($oferta['empresa']); ?></p>
            <p><strong>Correo de Contacto:</strong> <?php echo htmlspecialchars($oferta['email_contacto']); ?></p>
            <a href="editar_oferta.php?id=<?php echo $oferta_id; ?>" class="btn">Editar Oferta</a>
        </div>
        <div class="postulaciones">
            <h2>Postulaciones</h2>
            <?php if (count($postulaciones) > 0): ?>
                <?php foreach ($postulaciones as $postulacion): ?>
                    <div class="postulacion">
                        <img src="../imagenes/<?php echo htmlspecialchars($postulacion['imagen']); ?>" alt="Foto de Perfil">
                        <div>
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($postulacion['nombre']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($postulacion['email']); ?></p>
                            <p><strong>LinkedIn:</strong> <a href="<?php echo htmlspecialchars($postulacion['linkedin']); ?>" target="_blank"><?php echo htmlspecialchars($postulacion['linkedin']); ?></a></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay postulaciones para esta oferta.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Sistema de Anuncios de Empleo. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
