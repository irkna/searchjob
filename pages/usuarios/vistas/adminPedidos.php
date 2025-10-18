<?php
include("../../conexion.php");
session_start();

// Verificar sesión
if (!isset($_SESSION['dni'])) {
    echo "Debes iniciar sesión.";
    exit();
}

$dni = (int) $_SESSION['dni'];

// Tomar handler de conexión compatible
$db = null;
if (isset($conex) && $conex instanceof mysqli) $db = $conex;
if (!$db && isset($conexion) && $conexion instanceof mysqli) $db = $conexion;
if (!$db) {
    die("Error de conexión a la base de datos.");
}

// Helpers simples
function to_datetime_mysql($val) {
    // Convierte 'YYYY-MM-DDTHH:MM' a 'YYYY-MM-DD HH:MM:SS'
    $val = trim($val ?? '');
    if ($val === '') return null;
    $val = str_replace('T', ' ', $val);
    // Si no tiene segundos, los agrega
    if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $val)) {
        $val .= ':00';
    }
    return $val;
}

$flash = ['ok' => [], 'err' => []];

// Acciones POST (actualizar / cancelar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {

    $accion = $_POST['accion'];

if ($accion === 'guardar_operacion') {
    $id = (int)($_POST['id_servicio'] ?? 0);
    $numero_operacion = trim($_POST['numero_operacion'] ?? '');

    if ($id > 0 && $numero_operacion !== '') {
        $sql = "UPDATE contrato SET numero_operacion = ?, estado = 'finalizado' 
                WHERE id_servicio = ? AND dni_usuario = ?";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("sii", $numero_operacion, $id, $dni);
            if ($stmt->execute()) {
                $flash['ok'][] = "Contrato #$id finalizado correctamente con número de operación.";
            } else {
                $flash['err'][] = "Error al guardar el número de operación.";
            }
        }
    } else {
        $flash['err'][] = "Debes ingresar un número de operación válido.";
    }
}


    if ($accion === 'actualizar') {
        $id = (int) ($_POST['id_servicio'] ?? 0);
        $ubicacion = trim($_POST['ubicacion'] ?? '');
        $fecha_hora = to_datetime_mysql($_POST['fecha_y_hora'] ?? '');

        if ($id <= 0 || $ubicacion === '' || $fecha_hora === null) {
            $flash['err'][] = "Datos incompletos para actualizar.";
        } else {
            // Verificar que el contrato pertenezca al usuario logueado y obtener trabajador
            $sqlCheck = "SELECT dni_trabajador FROM contrato WHERE id_servicio=? AND dni_usuario=?";
            if ($stmtC = $db->prepare($sqlCheck)) {
                $stmtC->bind_param("ii", $id, $dni);
                $stmtC->execute();
                $res = $stmtC->get_result();
                if ($rowC = $res->fetch_assoc()) {
                    $dni_trabajador = (int)$rowC['dni_trabajador'];

                    // Actualizar solo ubicacion y fecha_y_hora
                    $sqlUpd = "UPDATE contrato SET ubicacion=?, fecha_y_hora=? WHERE id_servicio=? AND dni_usuario=?";
                    if ($stmtU = $db->prepare($sqlUpd)) {
                        $stmtU->bind_param("ssii", $ubicacion, $fecha_hora, $id, $dni);
                        if ($stmtU->execute()) {
                            $flash['ok'][] = "Contrato #$id actualizado.";

                            // Notificación al trabajador (tipo permitido: comentario)
                            $mensaje = "El cliente modificó el contrato #$id.";
                            $enlace = "notificaciones.php";
                            $tipo = "comentario";
                            $sqlN = "INSERT INTO notificaciones (dni_usuario, tipo, mensaje, enlace) VALUES (?, ?, ?, ?)";
                            if ($stmtN = $db->prepare($sqlN)) {
                                $stmtN->bind_param("isss", $dni_trabajador, $tipo, $mensaje, $enlace);
                                $stmtN->execute();
                            }
                        } else {
                            $flash['err'][] = "No se pudo actualizar el contrato #$id.";
                        }
                        $stmtU->close();
                    } else {
                        $flash['err'][] = "Error preparando actualización.";
                    }
                } else {
                    $flash['err'][] = "Contrato no encontrado o no te pertenece.";
                }
                $stmtC->close();
            } else {
                $flash['err'][] = "Error verificando contrato.";
            }
        }
    }

    if ($accion === 'cancelar') {
        $id = (int) ($_POST['id_servicio'] ?? 0);
        if ($id <= 0) {
            $flash['err'][] = "ID de contrato inválido.";
        } else {
            // Obtener trabajador y confirmar pertenencia
            $sqlGet = "SELECT dni_trabajador FROM contrato WHERE id_servicio=? AND dni_usuario=?";
            if ($stmtG = $db->prepare($sqlGet)) {
                $stmtG->bind_param("ii", $id, $dni);
                $stmtG->execute();
                $res = $stmtG->get_result();
                if ($rowG = $res->fetch_assoc()) {
                    $dni_trabajador = (int)$rowG['dni_trabajador'];

                    // Borrar
                    $sqlDel = "DELETE FROM contrato WHERE id_servicio=? AND dni_usuario=?";
                    if ($stmtD = $db->prepare($sqlDel)) {
                        $stmtD->bind_param("ii", $id, $dni);
                        if ($stmtD->execute()) {
                            $flash['ok'][] = "Contrato #$id cancelado.";

                            // Notificación al trabajador (tipo permitido: comentario)
                            $mensaje = "El cliente canceló el contrato #$id.";
                            $enlace = "notificaciones.php";
                            $tipo = "comentario";
                            $sqlN = "INSERT INTO notificaciones (dni_usuario, tipo, mensaje, enlace) VALUES (?, ?, ?, ?)";
                            if ($stmtN = $db->prepare($sqlN)) {
                                $stmtN->bind_param("isss", $dni_trabajador, $tipo, $mensaje, $enlace);
                                $stmtN->execute();
                            }
                        } else {
                            $flash['err'][] = "No se pudo cancelar el contrato #$id.";
                        }
                        $stmtD->close();
                    } else {
                        $flash['err'][] = "Error preparando eliminación.";
                    }
                } else {
                    $flash['err'][] = "Contrato no encontrado o no te pertenece.";
                }
                $stmtG->close();
            } else {
                $flash['err'][] = "Error verificando contrato.";
            }
        }
    }
    if ($_POST['accion'] === 'entregado') {
    $id_servicio = (int)$_POST['id_servicio'];
    $sql = "UPDATE contrato SET estado='entregado' WHERE id_servicio=$id_servicio";
    mysqli_query($conex, $sql);

} elseif ($_POST['accion'] === 'finalizar') {
    $id_servicio = (int)$_POST['id_servicio'];
    $sql = "UPDATE contrato SET estado='finalizado' WHERE id_servicio=$id_servicio";
    mysqli_query($conex, $sql);
}

  }

// Traer contratos del usuario (cliente)
// Pedidos activos (no finalizados)
$sqlActivos = "SELECT c.id_servicio, c.dni_trabajador, c.costo, 
                      c.fecha_y_hora, c.descripcion, c.ubicacion, c.estado,
                      u.nombre AS nombre_trabajador, u.foto_perfil AS foto_trabajador
               FROM contrato c
               JOIN usuarios u ON u.dni = c.dni_trabajador
               WHERE c.dni_usuario=? AND c.estado != 'finalizado'
               ORDER BY c.fecha_y_hora DESC";
$stmt = mysqli_prepare($db, $sqlActivos);
mysqli_stmt_bind_param($stmt, "i", $dni);
mysqli_stmt_execute($stmt);
$resultActivos = mysqli_stmt_get_result($stmt);

// Pedidos finalizados
$sqlFinalizados = "SELECT c.id_servicio, c.dni_trabajador, c.costo, 
                          c.fecha_y_hora, c.descripcion, c.ubicacion, c.estado,
                          c.numero_operacion,
                          u.nombre AS nombre_trabajador, u.foto_perfil AS foto_trabajador
                   FROM contrato c
                   JOIN usuarios u ON u.dni = c.dni_trabajador
                   WHERE c.dni_usuario=? AND c.estado = 'finalizado'
                   ORDER BY c.fecha_y_hora DESC";

$stmt2 = mysqli_prepare($db, $sqlFinalizados);
mysqli_stmt_bind_param($stmt2, "i", $dni);
mysqli_stmt_execute($stmt2);
$resultFinalizados = mysqli_stmt_get_result($stmt2);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administrador de pedidos - Search Job</title>
  <link rel="stylesheet" href="../../../styles/style.css">
  <link rel="icon" href="../../../imagenes/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
  @media screen and (max-width: 768px) {
  h2, h3 {
    font-size: 15px;
    text-align: center;
  }

  p {
    font-size: 15px !important;
  }
span{
  font-size:15px !important;
}
  header nav a {
    font-size: 14px;
    margin: 5px 0;
  }
  .container-menu .cont-menu nav a { font-size: 1.0rem !important; padding: 16px 12px !important; }

  /* Centrar imágenes y texto en tarjetas */
  .pedido-card div[style*="display:flex"] {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .pedido-card img {
    width: 65px;
    height: 65px;
    margin-bottom: 10px;
  }

  /* Formularios en columna */
  .pedido-card form label span {
    font-size: 13px;
  }

  .pedido-card input,
  .pedido-card button {
    font-size: 14px;
  }

  /* Botones apilados */
  .pedido-card form div[style*="gap:10px"] {
    display: flex;
    flex-direction: column;
  }

  /* Footer más pequeño */
  footer {
    font-size: 16px;
  }
  /* Forzar que todo ocupe el 100% y evitar scroll horizontal */
html, body {
  width: 100%;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  overflow-x: hidden; /* Evita scroll horizontal si algo se sale */
}

/* Header full width */
header {
  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
  position: relative; /* para que perfil absoluto se posicione bien */
}

/* Logo y nav dentro del header */
header .logo, header nav {
  display: inline-block;
  vertical-align: middle;
}

/* Ajuste para el perfil absoluto en móvil */
@media (max-width: 600px) {
  header .perfil-header {
    top: 10px;
    right: 10px;
    position: absolute;
  }
}

} 

</style>
</head>
<body>

  <header>
    <div class="logo">
      <a href="../index-u.php">
        <img src="../../../imagenes/logo.png" alt="Search Job Logo">
      </a>
    </div>
    <nav>
      <?php if (isset($_SESSION['dni'])): ?>
        <a href="../perfil/perfil.php" title="Mi perfil" style="color:white; margin-right: 10px;">
          <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Mi Perfil'); ?>
          <img 
            src="<?php echo !empty($_SESSION['foto_perfil']) 
                ? '../../../imagenes/perfiles/' . htmlspecialchars($_SESSION['foto_perfil'])
                : '../../../imagenes/perfiles/cliente-logo.jpg'; ?>" 
            alt="Perfil" 
            style="width:40px; height:40px; border-radius:50%; vertical-align:middle;" 
          />
        </a>
      <?php endif; ?>
    </nav>
  </header>
<?php if (isset($_GET['finalizado'])): ?>
  <div style="background:#e6ffed; border:1px solid #b7f5c5; padding:10px; border-radius:8px; margin:10px auto; width:fit-content; color:#1e7a3b;">
    <i class="fa-solid fa-circle-check"></i> ¡El contrato fue pagado y finalizado con éxito!
  </div>
<?php endif; ?>

  <!--boton de barra--------------->
  <ul>
    <font size="5px"><label for="btn-menu">☰</label></font>
  </ul>

  <!--menu--------------->
  <input type="checkbox" id="btn-menu">
  <div class="container-menu">
    <div class="cont-menu">
      <nav><br>
        <a href="../index-u.php" data-texto="home">Home</a>
        <a href="categorias.php" data-texto="categorias">Categorías</a>
        <a href="notificaciones.php" data-texto="bandeja">Bandeja de entrada</a>
      
        <a href="adminPedidos.php" data-texto="adminPedidos">Pedidos</a>
                <a href="chat.php">Chat</a>

        <a href="configuracion.php" data-texto="configuracion">Configuración</a>
        <a href="ayuda.php" data-texto="ayuda">Ayuda</a>
      </nav>
      <label for="btn-menu">✖️</label>
    </div>
  </div>

  <main class="search-section">
    <section class="fondo">
      <h2 data-texto="tituloAdmin">Pedidos</h2>
      <p data-texto="introAdmin">Gestioná tus pedidos: modificá lugar, horario y costo para cada solicitud.</p>

      <!-- Mensajes de operación -->
      <?php if (!empty($flash['ok']) || !empty($flash['err'])): ?>
        <div style="max-width: 800px; margin: 10px auto;">
          <?php foreach ($flash['ok'] as $m): ?>
            <div style="background:#e6ffed;border:1px solid #b7f5c5;color:#1e7a3b;padding:10px;border-radius:8px;margin-bottom:6px;">
              <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($m); ?>
            </div>
          <?php endforeach; ?>
          <?php foreach ($flash['err'] as $m): ?>
            <div style="background:#ffecec;border:1px solid #f5b7b7;color:#7a1e1e;padding:10px;border-radius:8px;margin-bottom:6px;">
              <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($m); ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

<?php if (mysqli_num_rows($resultActivos) === 0): ?>
  <div class="pedido-card" style="background:white; max-width:600px; margin:20px auto; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1); color:#333;">
    <p>No tienes pedidos activos.</p>
  </div>
<?php else: ?>
  <?php while ($c = mysqli_fetch_assoc($resultActivos)) : ?>
    <div class="pedido-card" style="background:white; max-width:700px; margin:20px auto; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1); color:#333;">
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
        <a href="ver_perfil.php?dni=<?php echo (int)$c['dni_trabajador']; ?>" style="display:flex; align-items:center; gap:12px; text-decoration:none; color:inherit;">
          <img src="<?php echo $c['foto_trabajador'] ? ('../../../imagenes/perfiles/' . htmlspecialchars($c['foto_trabajador'])) : '../../../imagenes/perfiles/cliente-logo.jpg'; ?>" 
               alt="Trabajador" 
               style="width:48px;height:48px;border-radius:50%;object-fit:cover; cursor:pointer;">
          <div>
            <p style="margin:0;"><strong>Pedido #<?php echo (int)$c['id_servicio']; ?></strong></p>
            <p style="margin:0;"><b>Trabajador:</b> <?php echo htmlspecialchars($c['nombre_trabajador']); ?></p>
          </div>
        </a>
           <div style="margin:10px; max-width:2500px; gap:10px; flex-wrap:wrap;">
      <a href="pagar.php?id_servicio=<?php echo (int)$c['id_servicio']; ?>" 
   style="background:#0275d8;color:#fff;padding:8px 14px;border:none;border-radius:8px;
          cursor:pointer;text-decoration:none;display:inline-block;">
   Pagar
</a>


          </button></div>
          <?php
// Si el contrato tiene un estado de "pagado" pero no tiene número de operación
if ($c['estado'] === 'pagado' && empty($c['numero_operacion'])): ?>
  <form method="POST" style="margin-top:10px;">
    <input type="hidden" name="id_servicio" value="<?php echo (int)$c['id_servicio']; ?>">
    <label>
      <span><b>Ingrese el número de operación:</b></span><br>
      <input type="text" name="numero_operacion" placeholder="Ej: #1234567890" required>
    </label>
    <button type="submit" name="accion" value="guardar_operacion"
      style="background:#28a745;color:#fff;padding:6px 12px;border:none;border-radius:6px;cursor:pointer;margin-top:6px;">
      Guardar número de operación
    </button>
  </form>
<?php elseif (!empty($c['numero_operacion'])): ?>
  <p><b>Número de operación:</b> <?php echo htmlspecialchars($c['numero_operacion']); ?></p>
<?php endif; ?>

      </div>

      <p><strong>Estado:</strong> <?php echo ucfirst($c['estado']); ?></p>
      <p><b>Servicio:</b> <?php echo htmlspecialchars($c['descripcion']); ?></p>

      <form method="POST" style="display:grid; gap:10px;">
        <input type="hidden" name="id_servicio" value="<?php echo (int)$c['id_servicio']; ?>">

        <label>
          <span>Lugar:</span>
          <input type="text" name="ubicacion" value="<?php echo htmlspecialchars($c['ubicacion']); ?>" style="width:100%;" required>
        </label>

        <label>
          <span>Horario:</span>
          <input type="datetime-local" name="fecha_y_hora" 
                 value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($c['fecha_y_hora']))); ?>" 
                 style="width:100%;" required>
        </label>

        <p style="margin:6px 0;"><b>Costo:</b> <?php echo (int)$c['costo']; ?> ARS</p>

        <div style="margin:10px; max-width:2500px; gap:10px; flex-wrap:wrap;">
          <button class="btn" type="submit" name="accion" value="actualizar" data-texto="guardar"
                  style="background:#eed8c9;color:#333;padding:8px 14px;border:none;border-radius:8px;cursor:pointer;">
            Guardar cambios
          </button>
          <button class="btn" type="submit" name="accion" value="cancelar"
                  onclick="return confirm('¿Seguro que querés cancelar este contrato?');"
                  style="background:#d9534f;color:#fff;padding:8px 14px;border:none;border-radius:8px;cursor:pointer;">
            Cancelar contrato
          </button>

          <?php if ($c['estado'] === 'entregado'): ?>
            <button class="btn" type="submit" name="accion" value="finalizar"
                    onclick="return confirm('¿Querés confirmar la finalización de este contrato?');"
                    style="background:#0275d8;color:#fff;padding:8px 14px;border:none;border-radius:8px;cursor:pointer;">
              Confirmar finalización
            </button>
          <?php endif; ?>
        </div>
      </form><br>
    </div><br><br>
  <?php endwhile; ?>
<?php endif; ?>

<hr>

<h2>Trabajos finalizados</h2>
<?php if (mysqli_num_rows($resultFinalizados) === 0): ?>
  <div class="pedido-card" style="background:white; max-width:600px; margin:20px auto; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1); color:#333;">
    <p>No tienes trabajos finalizados.</p>
  </div>
<?php else: ?>
  <?php while ($c = mysqli_fetch_assoc($resultFinalizados)) : ?>
    <div class="pedido-card" style="background:white; max-width:700px; margin:20px auto; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1); color:#333;">
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
        <a href="ver_perfil.php?dni=<?php echo (int)$c['dni_trabajador']; ?>" style="display:flex; align-items:center; gap:12px; text-decoration:none; color:inherit;">
          <img src="<?php echo $c['foto_trabajador'] ? ('../../../imagenes/perfiles/' . htmlspecialchars($c['foto_trabajador'])) : '../../../imagenes/perfiles/cliente-logo.jpg'; ?>" 
               alt="Trabajador" 
               style="width:48px;height:48px;border-radius:50%;object-fit:cover; cursor:pointer;">
          <div>
            <p style="margin:0;"><strong>Pedido #<?php echo (int)$c['id_servicio']; ?></strong></p>
            <p style="margin:0;"><b>Trabajador:</b> <?php echo htmlspecialchars($c['nombre_trabajador']); ?></p>
          </div>
        </a>
      </div>
<?php if (!empty($c['numero_operacion'])): ?>
  <p><b>Número de operación:</b> <?php echo htmlspecialchars($c['numero_operacion']); ?></p>
<?php else: ?>
  <p><b>Número de operación:</b> No registrado</p>
<?php endif; ?>

      <p><strong>Estado:</strong> <?php echo ucfirst($c['estado']); ?></p>
      <p><b>Servicio:</b> <?php echo htmlspecialchars($c['descripcion']); ?></p>
      <p><b>Lugar:</b> <?php echo htmlspecialchars($c['ubicacion']); ?></p>
      <p><b>Fecha y hora:</b> <?php echo htmlspecialchars($c['fecha_y_hora']); ?></p>
      <p><b>Costo:</b> <?php echo (int)$c['costo']; ?> ARS</p>
    </div>
  <?php endwhile; ?>
<?php endif; ?>

    </section>
  </main>

  <footer>
    <span>Search Job</span>
    <div class="social-icons">
      <a href="https://www.instagram.com/searchjobofficial?utm_source=ig_web_button_share_sheet&igsh=MXd5dmtoczJzM2FwNA==" target="_blank"><i class="fab fa-instagram"></i></a>
      <a href="https://whatsapp.com/channel/0029Vb6GsJ0HFxP99lJCxN2w" target="_blank"><i class="fab fa-whatsapp"></i></a>
      <a href=" https://x.com/SearchJob_offic?t=5V35SM4pON8GfgydSvikyg&s=08" target="_blank"><i class="fab fa-twitter"></i></a>
    </div>
  </footer>

  <script>
  window.addEventListener('DOMContentLoaded', () => {
    const tema = localStorage.getItem('tema') || 'claro';
    const idioma = localStorage.getItem('idioma') || 'es';

    // --- Tema ---
    const body = document.body;
    const bloques = document.querySelectorAll('.search-section, .fondo, .container, .config-box, .card');
    const botones = document.querySelectorAll('.btn, .boton-premium, .filtro-btn');
    const titulos = document.querySelectorAll('h1, h2, h3');
    const inputs = document.querySelectorAll('input, select, textarea');

    if (tema === 'oscuro') {
      body.style.backgroundColor = '#2e2e2e';
      body.style.color = '#f1f1f1';
      bloques.forEach(el => { el.style.backgroundColor = '#3b3b3b'; el.style.color = '#f1f1f1'; });
      titulos.forEach(t => t.style.color = '#ffd27f');
      botones.forEach(b => { b.style.backgroundColor = '#444'; b.style.color = '#fff'; });
      inputs.forEach(i => { i.style.backgroundColor = '#555'; i.style.color = '#f1f1f1'; i.style.borderColor = '#777'; });
    } else {
      body.style.backgroundColor = '#fff';
      body.style.color = '#333';
      bloques.forEach(el => { el.style.backgroundColor = '#fff'; el.style.color = '#333'; });
      titulos.forEach(t => t.style.color = '#4e4a57');
      botones.forEach(b => { b.style.backgroundColor = '#eed8c9'; b.style.color = '#333'; });
      inputs.forEach(i => { i.style.backgroundColor = '#fff'; i.style.color = '#333'; i.style.borderColor = '#ccc'; });
    }

    // --- Idioma ---
    const textos = document.querySelectorAll('[data-texto]');
    const traducciones = {
      es: {
        tituloPagina: "Administrador de pedidos - Search Job",
        iniciaSesion: "Inicia sesión",
        unete: "Únete",
        home: "Home",
        premium: "Premium",
        categorias: "Categorías",
        bandeja: "Bandeja de entrada",
        adminPedidos: "Pedidos",
        configuracion: "Configuración",
        ayuda: "Ayuda",
        tituloAdmin: "Pedidos",
        introAdmin: "Gestioná tus pedidos: modificá lugar, horario y costo para cada solicitud.",
        pedido: "Pedido #1234",
        cliente: "Cliente: Pedro Gómez",
        servicio: "Servicio: Plomería",
        lugar: "Lugar:",
        horario: "Horario:",
        costo: "Costo:",
        guardar: "Guardar cambios"
      },
      en: {
        tituloPagina: "Order Manager - Search Job",
        iniciaSesion: "Sign in",
        unete: "Join",
        home: "Home",
        premium: "Premium",
        categorias: "Categories",
        bandeja: "Inbox",
        adminPedidos: "Orders",
        configuracion: "Settings",
        ayuda: "Help",
        tituloAdmin: "Orders",
        introAdmin: "Manage your orders: modify location, schedule, and cost for each request.",
        pedido: "Order #1234",
        cliente: "Client: Pedro Gómez",
        servicio: "Service: Plumbing",
        lugar: "Location:",
        horario: "Schedule:",
        costo: "Cost:",
        guardar: "Save changes"
      },
      pt: {
        tituloPagina: "Administrador de pedidos - Search Job",
        iniciaSesion: "Entrar",
        unete: "Junte-se",
        home: "Home",
        premium: "Premium",
        categorias: "Categorias",
        bandeja: "Caixa de entrada",
        adminPedidos: "Pedidos",
        configuracion: "Configurações",
        ayuda: "Ajuda",
        tituloAdmin: "Administrador de pedidos",
        introAdmin: "Gerencie seus pedidos: modifique local, horário e custo para cada solicitação.",
        pedido: "Pedido #1234",
        cliente: "Cliente: Pedro Gómez",
        servicio: "Serviço: Encanamento",
        lugar: "Local:",
        horario: "Horário:",
        costo: "Custo:",
        guardar: "Salvar alterações"
      }
    };

    textos.forEach(el => {
      const clave = el.getAttribute('data-texto');
      if (traducciones[idioma][clave]) el.textContent = traducciones[idioma][clave];
    });
  });
</script>

</body>
</html>
