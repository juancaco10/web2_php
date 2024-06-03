<?php
include '../includes/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: login.html');
    exit();
}

// Obtener todas las ofertas de empleo con filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';

$sql = "SELECT * FROM ofertas WHERE (titulo LIKE '%$search%' OR empresa LIKE '%$search%')";

if ($categoria != '') {
    $sql .= " AND categoria='$categoria'";
}

if ($estado !== '') {
    $sql .= " AND activa=$estado";
}

$result = $conn->query($sql);

$ofertas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ofertas[] = $row;
    }
}

// Manejar la habilitación/deshabilitación de ofertas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle_id'])) {
    $oferta_id = $_POST['toggle_id'];
    $estado_actual = $_POST['estado_actual'];
    $nuevo_estado = $estado_actual ? 0 : 1;
    
    $sql_toggle = "UPDATE ofertas SET activa=$nuevo_estado WHERE id=$oferta_id";
    if ($conn->query($sql_toggle) === TRUE) {
        header('Location: ver_ofertas.php');
        exit();
    } else {
        echo "Error al actualizar el estado de la oferta.";
    }
}

// Manejar la eliminación de ofertas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $oferta_id = $_POST['delete_id'];
    
    $sql_delete = "DELETE FROM ofertas WHERE id=$oferta_id";
    if ($conn->query($sql_delete) === TRUE) {
        header('Location: ver_ofertas.php');
        exit();
    } else {
        echo "Error al eliminar la oferta.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Ofertas de Empleo</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        .contenedor-principal {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .filtros {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .filtros input[type="text"],
        .filtros select {
            padding: 10px;
            width: 100%;
            max-width: 600px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .filtros button {
            padding: 10px 20px;
            background: #ff6600;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .filtros button:hover {
            background: #e65c00;
        }
        .ofertas-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .oferta {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: calc(50% - 20px);
            box-sizing: border-box;
        }
        .oferta h2 {
            margin-top: 0;
        }
        .oferta p {
            margin: 10px 0;
        }
        .btn {
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background 0.3s;
            margin-right: 10px;
            display: inline-block;
        }
        .btn-activo {
            background: #ff6600;
        }
        .btn-activo:hover {
            background: #e65c00;
        }
        .btn-inactivo {
            background: #ccc;
            color: #666;
        }
        .btn-inactivo:hover {
            background: #bbb;
        }
        .btn-delete {
            background: #ff0000;
        }
        .btn-delete:hover {
            background: #cc0000;
        }
        .botones {
            display: flex;
            justify-content: space-between;
        }
    </style>
    <script>
        function confirmarEliminacion(oferta_id) {
            if (confirm('¿Está seguro que desea eliminar esta oferta?')) {
                document.getElementById('delete-form-' + oferta_id).submit();
            }
        }
    </script>
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
        <div class="contenedor-principal">
            <section class="filtros">
                <form method="get" action="ver_ofertas.php">
                    <input type="text" name="search" placeholder="Buscar por empresa o título" value="<?php echo htmlspecialchars($search); ?>">
                    <select name="categoria">
                        <option value="">Todas las Categorías</option>
                        <option value="Tiempo Completo" <?php if ($categoria == 'Tiempo Completo') echo 'selected'; ?>>Tiempo Completo</option>
                        <option value="Medio Tiempo" <?php if ($categoria == 'Medio Tiempo') echo 'selected'; ?>>Medio Tiempo</option>
                        <option value="Freelance" <?php if ($categoria == 'Freelance') echo 'selected'; ?>>Freelance</option>
                    </select>
                    <select name="estado">
                        <option value="">Todos los Estados</option>
                        <option value="1" <?php if ($estado === '1') echo 'selected'; ?>>Habilitado</option>
                        <option value="0" <?php if ($estado === '0') echo 'selected'; ?>>Deshabilitado</option>
                    </select>
                    <button type="submit" class="btn">Filtrar</button>
                </form>
            </section>
            <h1>Ofertas de Empleo</h1>
            <section class="ofertas-container">
                <?php if (count($ofertas) > 0): ?>
                    <?php foreach ($ofertas as $oferta): ?>
                        <div class="oferta">
                            <h2><?php echo htmlspecialchars($oferta['titulo']); ?></h2>
                            <p><?php echo htmlspecialchars($oferta['descripcion']); ?></p>
                            <p><strong>Categoría:</strong> <?php echo htmlspecialchars($oferta['categoria']); ?></p>
                            <p><strong>Empresa:</strong> <?php echo htmlspecialchars($oferta['empresa']); ?></p>
                            <p><strong>Contacto:</strong> <?php echo htmlspecialchars($oferta['email_contacto']); ?></p>
                            <div class="botones">
                                <form method="post" action="ver_ofertas.php" style="display:inline;">
                                    <input type="hidden" name="toggle_id" value="<?php echo $oferta['id']; ?>">
                                    <input type="hidden" name="estado_actual" value="<?php echo $oferta['activa']; ?>">
                                    <button type="submit" class="btn <?php echo $oferta['activa'] ? 'btn-activo' : 'btn-inactivo'; ?>"><?php echo $oferta['activa'] ? 'Deshabilitar' : 'Habilitar'; ?></button>
                                </form>
                                <a href="gestionar_ofertas.php?id=<?php echo $oferta['id']; ?>" class="btn btn-activo">Editar</a>
                                <form id="delete-form-<?php echo $oferta['id']; ?>" method="post" action="ver_ofertas.php" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $oferta['id']; ?>">
                                    <button type="button" class="btn btn-delete" onclick="confirmarEliminacion(<?php echo $oferta['id']; ?>)">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay ofertas de empleo registradas.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Sistema de Anuncios de Empleo. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
