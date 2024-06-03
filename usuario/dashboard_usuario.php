<?php
include '../includes/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit();
}

// Obtener la información del usuario
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM usuarios WHERE id='$usuario_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $nombre = $usuario['nombre'];
    $imagen = $usuario['imagen'];
} else {
    // Manejar el caso en que el usuario no exista
    echo "Usuario no encontrado.";
    exit();
}

// Obtener las ofertas de empleo habilitadas
$search = isset($_GET['search']) ? $_GET['search'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

$sql_ofertas = "SELECT * FROM ofertas WHERE activa = 1 AND (titulo LIKE '%$search%' OR empresa LIKE '%$search%')";
if ($categoria != '') {
    $sql_ofertas .= " AND categoria='$categoria'";
}

$result_ofertas = $conn->query($sql_ofertas);

$ofertas = [];
if ($result_ofertas->num_rows > 0) {
    while ($row = $result_ofertas->fetch_assoc()) {
        // Verificar si el usuario ya se ha postulado a esta oferta
        $oferta_id = $row['id'];
        $sql_postulacion = "SELECT * FROM postulaciones WHERE id_usuario='$usuario_id' AND id_oferta='$oferta_id'";
        $result_postulacion = $conn->query($sql_postulacion);

        if ($result_postulacion->num_rows > 0) {
            $row['postulado'] = true;
        } else {
            $row['postulado'] = false;
        }

        $ofertas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard del Usuario</title>
    <link rel="stylesheet" href="../css/dashboard_usuario.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="../js/dashboard_usuario.js" defer></script>   
</head>
<body>
    <header>
        <nav>
            <div class="logo">Anuncios de Empleo</div>
            <ul class="perfil">
                <li><a href="dashboard_usuario.php">Inicio</a></li>
                <li><a href="postulaciones.php">Mis Postulaciones</a></li>
                <li><a href="logout.php" id="logout-link">Cerrar Sesión</a></li>
                <li><a href="perfil.php"><img src="../imagenes/<?php echo htmlspecialchars($imagen); ?>" alt="Foto de Perfil"></a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="dashboard">
            <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?></h1>
            <div class="busqueda">
                <form method="get" action="dashboard_usuario.php">
                    <input type="text" name="search" placeholder="Buscar por categorías o empresas" value="<?php echo htmlspecialchars($search); ?>">
                    <select name="categoria">
                        <option value="">Todas las Categorías</option>
                        <option value="Tiempo Completo" <?php if ($categoria == 'Tiempo Completo') echo 'selected'; ?>>Tiempo Completo</option>
                        <option value="Medio Tiempo" <?php if ($categoria == 'Medio Tiempo') echo 'selected'; ?>>Medio Tiempo</option>
                        <option value="Freelance" <?php if ($categoria == 'Freelance') echo 'selected'; ?>>Freelance</option>
                    </select>
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <h1>Ofertas disponibles:</h1>
            <div class="ofertas">
                <?php if (count($ofertas) > 0): ?>
                    <?php foreach ($ofertas as $oferta): ?>
                        <div class="oferta" onclick="window.location.href='detalle_oferta.php?id=<?php echo $oferta['id']; ?>'">
                            <img src="../imagenes/<?php echo htmlspecialchars($oferta['imagen']); ?>" alt="Imagen de la Empresa">
                            <div>
                                <h2><?php echo htmlspecialchars($oferta['titulo']); ?></h2>
                                <p><?php echo htmlspecialchars($oferta['descripcion']); ?></p>
                                <p><strong>Categoría:</strong> <?php echo htmlspecialchars($oferta['categoria']); ?></p>
                                <p><strong>Empresa:</strong> <?php echo htmlspecialchars($oferta['empresa']); ?></p>
                                <p><strong>Contacto:</strong> <?php echo htmlspecialchars($oferta['email_contacto']); ?></p>
                                <?php if ($oferta['postulado']): ?>
                                    <button class="button-disabled" disabled>Postulado</button>
                                <?php else: ?>
                                    <button>Postularme</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay ofertas de empleo registradas.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Sistema de Anuncios de Empleo - Programación WEB II - Juan Carlos de León</p>
    </footer>
</body>
</html>