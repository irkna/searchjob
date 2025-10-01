<?php
 include("../../../conexion.php");

 if(isset($_POST['send'])){
    if(  
        strlen($_POST['cuil'])>=1 &&
        strlen($_POST['matricula'])>=1 &&
        strlen($_POST['ocupacion'])>=1 &&
        strlen($_POST['identificador'])>=1 
   
    ){
      $cuil=trim($_POST['cuil']);
      $matricula=trim($_POST['matricula']);
      $ocupacion=trim($_POST['ocupacion']);  
      $identificador=trim($_POST['identificador']);
     
  
      $consulta="INSERT INTO trabajador(cuil,matricula,ocupacion,identificador)
              VALUES('$cuil','$matricula','$ocupacion','$identificador')";
    
      $resultado = mysqli_query($conex,$consulta);
        /* if($resultado){
              mensaje de error 
         }*/

          if ($resultado)  {
        echo "<script>window.location.href = '/searchjob/pages/registro/iniciosesion.html';</script>";
        exit();
}

        else {
        echo "Error al guardar los datos: " . mysqli_error($conex);
           
        }
} 

     
    } 

?>