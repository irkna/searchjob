<?php
session_start();
include("../../../conexion.php");

$gmail = $_POST['gmail'];
$contrasena = $_POST['contrasena']; 

$sql = "SELECT * FROM usuarios WHERE gmail='$gmail' AND contrasena='$contrasena'";
$resultado = mysqli_query($conex, $sql);

if (mysqli_num_rows($resultado) == 1) {
    $usuario = mysqli_fetch_assoc($resultado);
    $_SESSION['dni'] = $usuario['dni']; 
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['telefono'] = $usuario['telefono'];
    $_SESSION['localidad'] = $usuario['localidad'];
    $_SESSION['gmail'] = $usuario['gmail'];
    $_SESSION['fecha'] = $usuario['fecha'];
    header("Location: ../../perfil/perfil.php");
} else {
    echo "<script>alert('Gmail o contraseña incorrectos');window.location.href='login-cliente.php';</script>";
}
?>