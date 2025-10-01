<?php
include("../../conexion.php");

if (!isset($_GET['id'])) {
    echo "Publicación no encontrada.";
    exit();
}

$id_publicacion = $_GET['id'];

// Consulta para traer comentario, nombre, dni y fecha
$sql = "SELECT com.comentario, u.nombre, u.dni, com.fecha
        FROM comentario com
        JOIN usuarios u ON com.dni_usuario = u.dni
        WHERE com.id_publicacion = ?
        ORDER BY com.fecha DESC";

$stmt = $conex->prepare($sql);

// Chequear si la preparación fue correcta
if (!$stmt) {
    die("Error en la consulta SQL: " . $conex->error);
}

$stmt->bind_param("i", $id_publicacion);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($comentario = $result->fetch_assoc()) {
        $fechaFormateada = date("d/m/Y H:i", strtotime($comentario['fecha'])); // formato: día/mes/año hora:min
        echo '<div class="comentario-card">';
        echo '<strong><a href="../vistas/ver_perfil.php?dni=' . $comentario['dni'] . '">' . htmlspecialchars($comentario['nombre']) . '</a></strong> ';
        echo '<span style="color:#888; font-size:12px;">(' . $fechaFormateada . ')</span><br>';
        echo '<p>' . htmlspecialchars($comentario['comentario']) . '</p>';
        echo '</div>';
    }
} else {
    echo "<p>Sin comentarios aún.</p>";
}
?>
