<?php
include("../../../conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni_trabajador = (int) $_POST['dni_trabajador'];
    $dni_usuario = (int) $_POST['dni_usuario'];
    $puntuacion = (int) $_POST['puntuacion'];
    $comentario = trim($_POST['comentario']);

    // Validar si ya calificó
    $check_sql = "SELECT * FROM califica WHERE dni_trabajador = ? AND dni_usuario = ?";
    $check_stmt = $conex->prepare($check_sql);
    $check_stmt->bind_param("ii", $dni_trabajador, $dni_usuario);
    $check_stmt->execute();
    $result_check = $check_stmt->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Ya calificaste a este usuario.');history.back();</script>";
        exit();
    }

    // Insertar calificación
    $sql = "INSERT INTO califica (dni_trabajador, dni_usuario, puntuacion, comentario) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conex->prepare($sql);
    if (!$stmt) {
        die("Error en prepare(): " . $conex->error);
    }
    $stmt->bind_param("iiis", $dni_trabajador, $dni_usuario, $puntuacion, $comentario);

    if ($stmt->execute()) {
        // Insertar notificación
        $dni_destinatario = $dni_trabajador; 
        $nombre_autor = $_SESSION['nombre'] ?? 'Alguien';
        $mensaje = "⭐ $nombre_autor te dejó una calificación.";
        $enlace = "/searchjob/pages/perfil/perfil.php?dni=" . $dni_trabajador;

        $sql_notif = "INSERT INTO notificaciones (dni_usuario, tipo, mensaje, enlace) VALUES (?, 'calificacion', ?, ?)";
        $stmt_notif = $conex->prepare($sql_notif);
        if ($stmt_notif) {
            $stmt_notif->bind_param("iss", $dni_destinatario, $mensaje, $enlace);
            $stmt_notif->execute();
        }

        // Redirigir después de guardar todo
        header("Location: ../ver_perfil.php?dni=$dni_trabajador");
        exit();
    } else {
        echo "Error al guardar la calificación: " . $stmt->error;
    }
}

/**//*notificacion */

$dni_destinatario = $_POST['dni_trabajador']; // recibe la notificación
$nombre_autor = $_SESSION['nombre'];

$mensaje = "⭐ $nombre_autor te dejó una calificación.";
$enlace = "/searchjob/pages/perfil/perfil.php?dni=" . $_SESSION['dni'];

$sql_notif = "INSERT INTO notificaciones (dni_usuario, tipo, mensaje, enlace) VALUES (?, 'calificacion', ?, ?)";
$stmt_notif = $conex->prepare($sql_notif);
$stmt_notif->bind_param("iss", $dni_destinatario, $mensaje, $enlace);
$stmt_notif->execute();

?>
