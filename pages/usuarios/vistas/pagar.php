<?php
include("../../conexion.php");
session_start();

if (!isset($_GET['id'])) {
  echo "Falta el ID del contrato.";
  exit();
}

$id = (int) $_GET['id'];
$dni_usuario = $_SESSION['dni'];

// Buscar el link de pago del trabajador correspondiente al contrato
$sql = "SELECT t.linkdepago
        FROM contrato c
        JOIN trabajador t ON c.dni_trabajador = t.identificador
        WHERE c.id_servicio = ? AND c.dni_usuario = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ii", $id, $dni_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $link = trim($row['linkdepago']);

    if (!empty($link)) {
        // Si no empieza con http o https, se lo agregamos
        if (!preg_match('/^https?:\/\//i', $link)) {
            $link = "https://" . $link;
        }

        // Redirigir al link de pago
        header("Location: $link");
        exit();
    } else {
        echo "<h2>El trabajador no tiene un link de pago configurado.</h2>";
        echo "<a href='../cliente/adminPedidos.php' style='text-decoration:none;color:#0275d8;'>Volver</a>";
    }
} else {
    echo "<h2>Contrato no encontrado.</h2>";
    echo "<a href='../cliente/adminPedidos.php' style='text-decoration:none;color:#0275d8;'>Volver</a>";
}
?>
