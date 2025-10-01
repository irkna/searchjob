<?php
include("../../conexion.php");
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
?>
