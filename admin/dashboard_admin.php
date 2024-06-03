<?php
include '../includes/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../usuario/login.html');
    exit();
}

// Obtener la información del administrador
$admin_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM usuarios WHERE id='$admin_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    $nombre = $admin['nombre'];
} else {
    echo "Administrador no encontrado.";
    exit();
}

// Obtener las ofertas con el conteo de postulaciones
$sql_ofertas = "SELECT o.*, (SELECT COUNT(*) FROM postulaciones p WHERE p.id_oferta = o.id) AS total_postulaciones 
                FROM ofertas o";
$result_ofertas = $conn->query($sql_ofertas);

$ofertas = [];
if ($result_ofertas->num_rows > 0) {
    while ($row = $result_ofertas->fetch_assoc()) {
        $ofertas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        .contenido-ofertas {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-around;
            margin-top: 40px;
            margin-left: 15px;
            margin-right: 15px;
            margin-bottom: 70px;
        }
        .oferta-card {
            width: calc(20% - 20px);
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            box-sizing: border-box;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .oferta-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 0, 0, 1);
        }
        .oferta-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .oferta-card p {
            margin: 10px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Panel de Administrador</div>
            <ul>
                <li><a href="dashboard_admin.php">Inicio</a></li>
                <li><a href="crear_oferta.html">Crear Oferta</a></li>
                <li><a href="ver_ofertas.php">Ver Ofertas</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="contenido-principal">
            <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?></h1>
            <p>Desde aquí puedes gestionar las ofertas de empleo.</p>
            <div class="acciones">
                <a href="crear_oferta.html" class="btn">Crear Nueva Oferta</a>
                <a href="ver_ofertas.php" class="btn">Gestionar Ofertas Existentes</a>
            </div>
        </section>
        <section class="contenido-ofertas">
            <?php if (count($ofertas) > 0): ?>
                <?php foreach ($ofertas as $oferta): ?>
                    <div class="oferta-card" onclick="window.location.href='postulaciones.php?id=<?php echo $oferta['id']; ?>'">
                        <img src="../imagenes/<?php echo htmlspecialchars($oferta['imagen']); ?>" alt="Logo de Empresa">
                        <p><strong><?php echo htmlspecialchars($oferta['titulo']); ?></strong></p>
                        <p><?php echo htmlspecialchars($oferta['empresa']); ?></p>
                        <p><strong>Postulaciones:</strong> <?php echo $oferta['total_postulaciones']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay ofertas de empleo registradas.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Sistema de Anuncios de Empleo - Programación WEB II - Juan Carlos de León</p>
    </footer>
</body>
</html>
