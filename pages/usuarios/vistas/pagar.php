<?php
include("../../conexion.php");
session_start();

if (!isset($_SESSION['dni'])) {
  echo "Debes iniciar sesión.";
  exit();
}

$dni_usuario = (int) $_SESSION['dni'];
$id = (int) ($_GET['id_servicio'] ?? 0);

if ($id <= 0) { 
  echo "ID de servicio inválido."; 
  exit(); 
}

// Buscar el contrato y el link de pago del trabajador
$sql = "SELECT c.dni_trabajador, t.link_pago, t.cvu
        FROM contrato c
        JOIN trabajador t ON c.dni_trabajador = t.identificador
        WHERE c.id_servicio = ? AND c.dni_usuario = ?";

if ($stmt = $conex->prepare($sql)) {
  $stmt->bind_param("ii", $id, $dni_usuario);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($row = $res->fetch_assoc()) {
    $link = $row['link_pago'];
    $cvu  = $row['cvu'];

    if (!empty($link)) {
      // Redirigir al link de MercadoPago
      header("Location: " . $link);
      exit();
    } else {
      echo "El trabajador no tiene link de pago registrado.<br>";
      echo "Podés transferir manualmente al CVU/alias: <strong>" . htmlspecialchars($cvu) . "</strong>";
      exit();
    }
  } else {
    echo "Contrato no encontrado o no corresponde a tu cuenta.";
    exit();
  }
} else {
  echo "Error en la consulta.";
}
?>
