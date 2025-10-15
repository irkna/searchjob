<?php
include("../conexion.php");
session_start();

if (!isset($_SESSION['dni'])) {
    echo "Debes iniciar sesión para pagar.";
    exit();
}

if (!isset($_GET['id_servicio'])) { 
    echo "Falta el ID del servicio.";
    exit();
}

$id_servicio = $_GET['id_servicio'];
$dni_usuario = $_SESSION['dni'];

// Obtener el link de pago del trabajador según el contrato
$sql = "SELECT t.linkdepago
        FROM contrato c
        JOIN trabajador t ON c.dni_trabajador = t.identificador
        WHERE c.id_servicio = ? AND c.dni_usuario = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ii", $id_servicio, $dni_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $fila = $result->fetch_assoc();
    $link = $fila['linkdepago'];

    // Asegurar que tenga https://
    if (!preg_match('/^https?:\/\//i', $link)) {
        $link = "https://" . $link;
    }
    //marca el contrtao como pagado
$update = "UPDATE contrato SET estado = 'pagado' WHERE id_servicio = ? AND dni_usuario = ?";
$stmt_update = $conex->prepare($update);
$stmt_update->bind_param("ii", $id_servicio, $dni_usuario);
$stmt_update->execute();


    // Mostrar mensaje y abrir automáticamente el link en una nueva pestaña
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Redirigiendo al pago...</title>
        <link rel='icon' href='../../imagenes/logo.png' type='image/x-icon'>
        <style>
            body {
                background: #f8f9fa;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100vh;
                font-family: Arial, sans-serif;
                color: #333;
            }
            .mensaje {
                background: #e6ffed;
                border: 1px solid #b7f5c5;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .mensaje h2 {
                color: #1e7a3b;
                margin-bottom: 10px;
            }
            a {
                display:inline-block;
                margin-top:20px;
                background:#0275d8;
                color:white;
                padding:8px 14px;
                border-radius:8px;
                text-decoration:none;
            }
        </style>
        <script>
            // Abrir automáticamente el link de pago en nueva pestaña
            window.onload = function() {
                window.open('$link', '_blank');
              
            }
        </script>
    </head>
    <body>
        <div class='mensaje'>
            <h2>Abra el link de pago...</h2>
            <p>Recuerde guardar el Numero De Operacion ya que le sera pedido para poder finalizar el pago.</p>
            <a href='$link' target='_blank'>Abrir manualmente</a>
            <br>
            <a href='adminPedidos.php'>Volver a mis pedidos</a>
        </div>
    </body>
    </html>";
    exit();

} else {
    echo "No se encontró el link de pago para este contrato.";
    exit();
}
?>
