<?php
include("../../../conexion.php");

if (isset($_POST['send'])) {
    if (
        strlen($_POST['dni']) >= 1 &&
        strlen($_POST['nombre']) >= 1 &&
        strlen($_POST['telefono']) >= 1 &&
        strlen($_POST['localidad']) >= 1 &&
        strlen($_POST['gmail']) >= 1 &&
        strlen($_POST['contrasena']) >= 1
    ) {
        $dni = trim($_POST['dni']);
        $nombre = trim($_POST['nombre']);
        $telefono = trim($_POST['telefono']);
        $localidad = trim($_POST['localidad']);
        $gmail = trim($_POST['gmail']);
        $contrasena = trim($_POST['contrasena']);
       $fecha = date("Y-m-d"); 

      /* Verifica si el dni ya existe*/
$verificar = mysqli_query($conex, "SELECT * FROM usuarios WHERE dni = '$dni'");
if (mysqli_num_rows($verificar) > 0) {
     ?>
 <div class="error">  <center>Este DNI ya está registrado. Por favor usa otro.</center> </div>
    <?php
} else {
   $consulta = "INSERT INTO usuarios(dni, nombre, telefono, localidad, gmail, contrasena, fecha)
                     VALUES('$dni', '$nombre', '$telefono', '$localidad', '$gmail', '$contrasena', '$fecha')";
  $resultado = mysqli_query($conex, $consulta);

    if ($resultado) {
    if (isset($_POST['trabajador'])) {
        echo "<script>window.location.href = '/searchjob/pages/trabajador/formulario/registro/formulariotrabajador.php';</script>";
    } else {
        echo "<script>window.location.href = '/searchjob/pages/registro/iniciosesion.html';</script>";
    }
    exit();
}

         else {
              ?>
    <div class="error">Error al guardar los datos</div>
    <?php
           /*echo " Error al guardar los datos ". mysqli_error($conex);*/

        }
} 

     
    } else {
        echo "Por favor completa todos los campos.";
    }
}
?>
