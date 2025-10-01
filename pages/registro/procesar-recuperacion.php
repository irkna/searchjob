<?php
include("../conexion.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST['dni'];
    $email = $_POST['email'];

   
    $dni = mysqli_real_escape_string($conex, $dni);
    $email = mysqli_real_escape_string($conex, $email);

    $sql = "SELECT contrasena FROM usuarios WHERE dni = '$dni' AND gmail = '$email'";
    $resultado = mysqli_query($conex, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
        $contrasena = $fila['contrasena'];
        echo "<script>alert('Tu contraseña es: $contrasena'); window.location.href='iniciosesion.html';</script>";
    } else {
        echo "<script>alert('No se encontró ningún usuario con ese DNI y correo.'); window.history.back();</script>";
    }
}
?>
