<?php
session_start();
include("../../../conexion.php");


$gmail = $_POST['gmail'];
$contrasena = $_POST['contrasena'];
/**//* */

$sql = "SELECT * FROM usuarios WHERE gmail = ? AND contrasena = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ss", $gmail, $contrasena);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    $dni = $usuario['dni'];

    /**//* */
    $sql_trabajador = "SELECT * FROM trabajador WHERE identificador = ?";
    $stmt_trabajador = $conex->prepare($sql_trabajador);
    $stmt_trabajador->bind_param("i", $dni);
    $stmt_trabajador->execute();
    $resultado_trabajador = $stmt_trabajador->get_result();

    if ($resultado_trabajador->num_rows === 1) {
        
        $_SESSION['dni'] = $dni;
        $_SESSION['nombre'] = $usuario['nombre'];

        header("Location:../../perfil/perfil-t.php");
        exit();
    } else {
        echo "<script>alert('Usted no está registrado como trabajador'); window.location.href = 'login-trabajador.html';</script>";
        exit();
    }
} else {
    echo "<script>alert('Gmail o contraseña incorrectos'); window.location.href = 'login-trabajador.html';</script>";
    exit();
}
?>
