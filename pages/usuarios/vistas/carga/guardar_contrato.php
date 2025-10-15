<?php
include("../../../conexion.php");
session_start();
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni_trabajador = $_POST['dni_trabajador'];
    $dni_usuario = $_SESSION['dni']; 
    $costo = $_POST['costo'];
    $descripcion = $_POST['descripcion'];
    $ubicacion = $_POST['ubicacion'];
    $fecha_y_hora = date("Y-m-d H:i:s");

    $sql = "INSERT INTO contrato (dni_trabajador, dni_usuario, costo,  fecha_y_hora, descripcion, ubicacion)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("iiisss", $dni_trabajador, $dni_usuario, $costo,  $fecha_y_hora, $descripcion, $ubicacion);

    if ($stmt->execute()) {
        // Crear notificación para el trabajador
        $msg = "Has recibido una nueva contratación de usuario $dni_usuario";
        $sql_notif = "INSERT INTO notificaciones (dni_usuario, mensaje, fecha) VALUES (?, ?, NOW())";
        $stmt_notif = $conex->prepare($sql_notif);
        $stmt_notif->bind_param("is", $dni_trabajador, $msg);
        $stmt_notif->execute();

        header("Location: ../adminPedidos.php");
        exit();
    } else {
        echo "Error al contratar: " . $stmt->error;
    }
}
?>
