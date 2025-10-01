<?php
include("../../conexion.php");
session_start();

if (!isset($_SESSION['dni'])) {
    echo "Debes iniciar sesión.";
    exit();
}

$dni_trabajador = $_SESSION['dni'];

// Lista de chats con usuarios + mensajes no leídos
$sql = "SELECT u.dni, u.nombre, u.foto_perfil,
               SUM(CASE WHEN m.receptor_dni = ? AND m.emisor_dni = u.dni AND m.leido = 0 THEN 1 ELSE 0 END) AS no_leidos
        FROM usuarios u
        INNER JOIN mensajes m 
            ON (u.dni = m.emisor_dni OR u.dni = m.receptor_dni)
        WHERE u.dni != ?
          AND (m.emisor_dni = ? OR m.receptor_dni = ?)
        GROUP BY u.dni, u.nombre, u.foto_perfil
        ORDER BY MAX(m.fecha) DESC";  // chats más recientes arriba
$stmt = $conex->prepare($sql);
$stmt->bind_param("ssss", $dni_trabajador, $dni_trabajador, $dni_trabajador, $dni_trabajador);
$stmt->execute();
$result = $stmt->get_result();
$clientes = $result->fetch_all(MYSQLI_ASSOC);

// Chat abierto
$chat_abierto = null;
$mensajes = [];

if (isset($_GET['chat_dni'])) {
    $chat_dni = $_GET['chat_dni'];

    // Marcar mensajes como leídos
    $sql = "UPDATE mensajes 
            SET leido = 1 
            WHERE emisor_dni = ? AND receptor_dni = ? AND leido = 0";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("ss", $chat_dni, $dni_trabajador);
    $stmt->execute();

    // Traer info del chat abierto
    $sql = "SELECT dni, nombre, foto_perfil FROM usuarios WHERE dni = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("s", $chat_dni);
    $stmt->execute();
    $result = $stmt->get_result();
    $chat_abierto = $result->fetch_assoc();

    // Traer mensajes
    $sql = "SELECT * FROM mensajes 
            WHERE (emisor_dni = ? AND receptor_dni = ?) 
               OR (emisor_dni = ? AND receptor_dni = ?)
            ORDER BY fecha ASC";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("ssss", $dni_trabajador, $chat_dni, $chat_dni, $dni_trabajador);
    $stmt->execute();
    $result = $stmt->get_result();
    $mensajes = $result->fetch_all(MYSQLI_ASSOC);
}

// Enviar mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensaje']) && isset($_POST['chat_dni'])) {
    $mensaje = trim($_POST['mensaje']);
    $chat_dni = $_POST['chat_dni'];

    $sql = "INSERT INTO mensajes (emisor_dni, receptor_dni, mensaje, fecha, leido) 
            VALUES (?, ?, ?, NOW(), 0)";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("sss", $dni_trabajador, $chat_dni, $mensaje);
    $stmt->execute();

    header("Location: ?chat_dni=" . urlencode($chat_dni));
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link rel="stylesheet" href="../../../styles/style.css">
    <link rel="icon" href="../../../imagenes/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .chat-container { display: flex; height: 90vh; margin: 20px; border: 1px solid #ccc; background: #fff; border-radius: 10px; overflow: hidden; }
        .clientes-list { width: 25%; border-right: 1px solid #ccc; overflow-y: auto; background: #fafafa; }
        .clientes-list .cliente { padding: 10px; display: flex; align-items: center; border-bottom: 1px solid #eee; cursor: pointer; }
        .clientes-list .cliente:hover { background: #eaeaea; }
        .clientes-list img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .chat-box { flex: 1; display: flex; flex-direction: column; }
        .chat-header { padding: 10px; background: #9f9c9fff; color: #fff; display: flex; align-items: center; }
        .chat-header img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; cursor: pointer; }
  .chat-messages {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    background: #e5ddd5;
    display: flex;
    flex-direction: column;
    gap: 4px; /* espacio pequeño entre mensajes */
}
.mensaje {
    display: inline-block;
    max-width: 60%;               /* límite de ancho de la burbuja */
    padding: 6px 10px;
    border-radius: 10px;
    background: #fff;
    word-wrap: break-word;        /* fuerza salto en palabras largas */
    overflow-wrap: break-word;    /* compatibilidad */
    white-space: normal;          /* permite saltos de línea */
    line-height: 1.3;
}


/* Mensajes propios */
.propio {
    background: #dcf8c6;
    align-self: flex-end;
    border-radius: 12px 12px 0 12px;
}

/* Mensajes ajenos */
.ajeno {
    background: #fff;
    border: 1px solid #ddd;
    align-self: flex-start;
    border-radius: 12px 12px 12px 0;
}

.mensaje small {
    display: block;
    font-size: 11px;
    color: gray;
    margin-top: 2px;
    text-align: right;
}

 .chat-input { display: flex; border-top: 1px solid #ccc; }
        .chat-input textarea { flex: 1; padding: 10px; border: none; resize: none; }
        .chat-input button { width: 50px; border: none; background: #9f9c9fff;color: #fff; cursor: pointer; }
        .chat-input button:hover { background: #9f9c9fff; }
    </style>
        <style>
  .mingo {
  width: 250px;   /* Ajusta el tamaño */
  height: auto;   /* Mantiene proporción */
  display: block;
  margin: 0 auto; /* Centrar */
}
.badge {
    background: #25d366;
    color: white;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
    margin-left: auto;
}


</style>
</head>
<body>
<header>
    <div class="logo">
        <a href="../index-t.php">
            <img src="../../../imagenes/logo.png" alt="Logo">
        </a>
    </div>
    <nav>
        <?php if (isset($_SESSION['dni'])): ?>
            <a href="../perfil/perfil-t.php" style="color:white; margin-right: 10px;">
                <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                <img 
                    src="<?php echo !empty($_SESSION['foto_perfil']) 
                                ? '../../../imagenes/perfiles/' . $_SESSION['foto_perfil'] 
                                : '../../../imagenes/perfiles/cliente-logo.jpg'; ?>" 
                    alt="Perfil" 
                    style="width:40px; height:40px; border-radius:50%; vertical-align:middle; cursor:pointer;" 
                />
            </a>
        <?php endif; ?>
    </nav>
</header>

<!-- Menú -->
<ul><font size="5px"><label for="btn-menu">☰</label></font></ul>
<input type="checkbox" id="btn-menu">
<div class="container-menu">
    <div class="cont-menu">
        <nav><br>
            <a href="../index-t.php" data-texto="home">Home</a>
            <a href="premium-t.html" data-texto="premium">Premium</a>
            <a href="categorias-t.php" data-texto="categorias">Categorías</a>
            <a href="notificaciones-t.php" data-texto="bandeja">Bandeja de entrada</a>
            <a href="adminPedidos-t.php" data-texto="adminPedidos">Pedidos</a>
           <a href="chat.php">Chat</a>
            <a href="configuracion-t.php" data-texto="configuracion">Configuración</a>
            <a href="ayuda-t.php" data-texto="ayuda">Ayuda</a>
        </nav>
        <label for="btn-menu">✖️</label>
    </div>
</div>

<div class="chat-container">
<div class="clientes-list">
    <?php foreach ($clientes as $cliente): ?>
       <div class="cliente" onclick="window.location='?chat_dni=<?php echo $cliente['dni']; ?>'">
    <a href="ver_perfil.php?dni=<?php echo $cliente['dni']; ?>" onclick="event.stopPropagation();">
        <img src="<?php echo "../../../imagenes/perfiles/" . (!empty($cliente['foto_perfil']) ? $cliente['foto_perfil'] : "cliente-logo.jpg"); ?>" 
             alt="Foto">
    </a>
    <span><?php echo $cliente['nombre']; ?></span>
 <?php if (!empty($cliente['no_leidos']) && $cliente['no_leidos'] > 0): ?>
    <span class="badge"><?php echo $cliente['no_leidos']; ?></span>
<?php endif; ?>

</div>

    <?php endforeach; ?>
</div>

 

    <div class="chat-box">
        <?php if ($chat_abierto): ?>
            <div class="chat-header">
                <a href="ver_perfil.php?dni=<?php echo $chat_abierto['dni']; ?>">
                    <img src="<?php echo "../../../imagenes/perfiles/" . (!empty($chat_abierto['foto_perfil']) ? $chat_abierto['foto_perfil'] : "cliente-logo.jpg"); ?>" alt="Foto">
                </a>
                <span><?php echo $chat_abierto['nombre']; ?></span>
            </div>
            <div class="chat-messages">
                <?php foreach ($mensajes as $msg): ?>
                    <div class="mensaje <?php echo $msg['emisor_dni'] == $dni_trabajador ? 'propio' : 'ajeno'; ?>">
                        <div><?php echo htmlspecialchars($msg['mensaje']); ?></div>
                        <small><?php echo date("d/m/Y H:i", strtotime($msg['fecha'])); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
            <form method="POST" class="chat-input">
                <textarea name="mensaje" rows="2" placeholder="Escribe tu mensaje..." required></textarea>
                <input type="hidden" name="chat_dni" value="<?php echo $chat_abierto['dni']; ?>">
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
        <?php else: ?>
            <div class="chat-header">
                <span>Selecciona un chat para comenzar</span>
            </div>
          <img src="../../../imagenes/mingo.png" 
     alt="Mingo" 
     style="display:block; margin:0 auto; width:550px; height:auto;">
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer>
    <span>Search Job</span>
    <div class="social-icons">
        <a href="https://www.instagram.com/searchjobofficial" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://whatsapp.com/channel/0029Vb6GsJ0HFxP99lJCxN2w" target="_blank"><i class="fab fa-whatsapp"></i></a>
        <a href="https://x.com/SearchJob_offic" target="_blank"><i class="fab fa-twitter"></i></a>
    </div>
</footer>



<script>
function aplicarTemaGlobal() {
  const tema = localStorage.getItem('tema');
  const body = document.body;

  if (!tema) return;

  if (tema === 'oscuro') {
    body.style.backgroundColor = '#2e2e2e';
    body.style.color = '#000000ff';
    document.querySelectorAll('.search-section, .fondo, .container, .config-box').forEach(elem => {
      elem.style.backgroundColor = '#3b3b3b';
      elem.style.color = '#f1f1f1';
    });
    document.querySelectorAll('h1, h2, h3').forEach(t => t.style.color = '#ffd27f');
    document.querySelectorAll('.btn, .boton-premium, .filtro-btn').forEach(btn => {
      btn.style.backgroundColor = '#444';
      btn.style.color = '#fff';
    });
  } else {
    body.style.backgroundColor = '#fff';
    body.style.color = '#333';
    document.querySelectorAll('.search-section, .fondo, .container, .config-box').forEach(elem => {
      elem.style.backgroundColor = '#fff';
      elem.style.color = '#333';
    });
    document.querySelectorAll('h1, h2, h3').forEach(t => t.style.color = '#4e4a57');
    document.querySelectorAll('.btn, .boton-premium, .filtro-btn').forEach(btn => {
      btn.style.backgroundColor = '#eed8c9';
      btn.style.color = '#333';
    });
  }
}

window.onload = () => {
  aplicarTemaGlobal();

  // Idioma
  const idioma = localStorage.getItem('idioma') || 'es';
  const textos = document.querySelectorAll('[data-texto]');
  const traducciones = {
    es: {
      tituloPagina: "Bandeja de entrada - Search Job",
      home: "Home",
      premium: "Premium",
      categorias: "Categorías",
      bandeja: "Bandeja de entrada",
      configuracion: "Configuración",
      ayuda: "Ayuda",
      titulo: "Bandeja de entrada",
      adminPedidos:"Administrador de pedidos",
      descripcion: "Aquí encontrarás mensajes, notificaciones y respuestas de profesionales o clientes.",
      ver: "Ver",
      sinNotificaciones: "No tienes notificaciones."
    },
    en: {
      tituloPagina: "Inbox - Search Job",
      home: "Home",
      premium: "Premium",
      categorias: "Categories",
      bandeja: "Inbox",
      configuracion: "Settings",
      ayuda: "Help",
      titulo: "Inbox",
      adminPedidos:"Order admin",
      descripcion: "Here you will find messages, notifications and replies from professionals or clients.",
      ver: "View",
      sinNotificaciones: "You have no notifications."
    },
    pt: {
      tituloPagina: "Caixa de entrada - Search Job",
      home: "Início",
      premium: "Premium",
      categorias: "Categorias",
      bandeja: "Caixa de entrada",
      configuracion: "Configurações",
      ayuda: "Ajuda",
      titulo: "Caixa de entrada",
      adminPedidos:"Administrador de pedidos",
      descripcion: "Aqui você encontrará mensagens, notificações e respostas de profissionais ou clientes.",
      ver: "Ver",
      sinNotificaciones: "Você não tem notificações."
    }
  };

  textos.forEach(el => {
    const clave = el.getAttribute('data-texto');
    if (traducciones[idioma][clave]) {
      el.textContent = traducciones[idioma][clave];
    }
  });
//chat
  const chatMessages = document.querySelector('.chat-messages');
    if (chatMessages) {
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

};
</script>
</body>
</html>
