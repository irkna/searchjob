<?php
include("../../conexion.php");
$id = intval($_GET['id']);
$sql = "SELECT c.comentario, u.nombre, c.fecha
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
        echo "<div><strong>" . htmlspecialchars($row['nombre']) . "</strong> (" . $row['fecha'] . ")<br>" . htmlspecialchars($row['comentario']) . "</div><hr>";
    }
} else {
    echo "<p>No hay comentarios.</p>";
}
?>
