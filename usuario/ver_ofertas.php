<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
include '../includes/conexion.php';

$result = $conn->query("SELECT * FROM ofertas WHERE activa=1");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ofertas de Empleo</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h1>Ofertas de Empleo</h1>
    <table>
        <tr>
            <th>Título</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Empresa</th>
            <th>Postular</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['titulo']; ?></td>
            <td><?php echo $row['descripcion']; ?></td>
            <td><?php echo $row['categoria']; ?></td>
            <td><?php echo $row['empresa']; ?></td>
            <td>
                <a href="postular_oferta.php?id=<?php echo $row['id']; ?>">Postular</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
