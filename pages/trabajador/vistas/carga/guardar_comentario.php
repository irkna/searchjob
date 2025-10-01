<?php
include("../../../conexion.php");
session_start();
if (!isset($_SESSION['dni'])) { exit("error"); }

$id_publicacion = intval($_POST['id_publicacion']);
$comentario = trim($_POST['comentario']);
$dni = $_SESSION['dni'];

if (!empty($comentario)) {
    $sql = "INSERT INTO comentario (id_publicacion, dni_usuario, comentario) VALUES (?, ?, ?)";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("iis", $id_publicacion, $dni, $comentario);
    if ($stmt->execute()) {
        echo "ok";
    } else {
        echo "error";
    }
}

/**//*notificar */
// Buscar el dueño de la publicación
$sql_pub = "SELECT dni_usuario FROM publicacion WHERE id_publicacion = ?";
$stmt_pub = $conex->prepare($sql_pub);
$stmt_pub->bind_param("i", $_POST['id_publicacion']);
$stmt_pub->execute();
$res_pub = $stmt_pub->get_result();
$pub = $res_pub->fetch_assoc();

if ($pub) {
    $dni_destinatario = $pub['dni_usuario'];
    $nombre_autor = $_SESSION['nombre'];

    $mensaje = "💬 $nombre_autor comentó en tu publicación.";
    $enlace = "/searchjob/pages/perfil/perfil.php?dni=" . $_SESSION['dni'];

    $sql_notif = "INSERT INTO notificaciones (dni_usuario, tipo, mensaje, enlace) VALUES (?, 'comentario', ?, ?)";
    $stmt_notif = $conex->prepare($sql_notif);
    $stmt_notif->bind_param("iss", $dni_destinatario, $mensaje, $enlace);
    $stmt_notif->execute();
}

?>
