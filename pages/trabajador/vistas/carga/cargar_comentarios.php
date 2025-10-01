<?php
include("../../../conexion.php");
$id = intval($_GET['id']);
$sql = "SELECT c.dni_usuario, c.comentario, u.nombre, c.fecha
        FROM comentario c
        JOIN usuarios u ON c.dni_usuario = u.dni
        WHERE c.id_publicacion = ?
        ORDER BY c.fecha DESC";

$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        echo '<p>
        <a href="ver_perfil.php?dni=' . urlencode($row['dni_usuario']) . '" 
           style="text-decoration:none; color:#333; font-weight:bold;">
           ' . htmlspecialchars($row['nombre']) . '
        </a>: ' 
        . "</strong> (" . $row['fecha'] . ")<br>"
        . htmlspecialchars($row['comentario']) . '
      </p>';

    }
} else {
    echo "<p>No hay comentarios.</p>";
}
?>
