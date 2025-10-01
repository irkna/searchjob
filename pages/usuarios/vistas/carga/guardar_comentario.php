<?php
include("../../../conexion.php");
session_start();
if (!isset($_SESSION['dni'])) { exit("error"); }

$id_publicacion = intval($_POST['id_publicacion']);
$comentario = trim($_POST['comentario']);
$dni = $_SESSION['dni'];

if (empty($comentario)) {
    exit("error: vacío");
}

// Insertar comentario
$sql = "INSERT INTO comentario (id_publicacion, dni_usuario, comentario) VALUES (?, ?, ?)";
$stmt = $conex->prepare($sql);
$stmt->bind_param("iis", $id_publicacion, $dni, $comentario);

if ($stmt->execute()) {
    // Notificar al dueño de la publicación
    $sql_pub = "SELECT dni_usuario FROM publicacion WHERE id_publicacion = ?";
    $stmt_pub = $conex->prepare($sql_pub);
    $stmt_pub->bind_param("i", $id_publicacion);
    $stmt_pub->execute();
    $res_pub = $stmt_pub->get_result();
    $pub = $res_pub->fetch_assoc();

    if ($pub) {
        $dni_destinatario = $pub['dni_usuario'];
        $nombre_autor = $_SESSION['nombre'];

        $mensaje = "💬 $nombre_autor comentó en tu publicación.";
        $enlace = "/searchjob/pages/perfil/perfil.php?dni=" . $dni;

        $sql_notif = "INSERT INTO notificaciones (dni_usuario, tipo, mensaje, enlace) 
                      VALUES (?, 'comentario', ?, ?)";
        $stmt_notif = $conex->prepare($sql_notif);
        $stmt_notif->bind_param("iss", $dni_destinatario, $mensaje, $enlace);
        $stmt_notif->execute();
    }

    echo "ok"; // 👈 IMPORTANTE: Solo una salida al final
} else {
    echo "error: " . $stmt->error;
}
