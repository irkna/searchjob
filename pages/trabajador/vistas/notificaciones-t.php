<?php
include("../conexion.php");
session_start();

if (!isset($_SESSION['dni'])) {
    header("Location: ../trabajadores/login-trabajador.html");
    exit();
}

$dni = $_SESSION['dni'];

$sql = "SELECT * FROM notificaciones WHERE dni_usuario = ? ORDER BY fecha DESC";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $dni);
$stmt->execute();
$res = $stmt->get_result();
$notificaciones = $res->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title data-texto="tituloPagina">Bandeja de entrada - Search Job</title>
  <link rel="stylesheet" href="../../../styles/style.css">
  <link rel="icon" href="../../../imagenes/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
  
    /* ======== RESPONSIVE ======== */
    @media (max-width: 768px) {
   
      nav a {
        display: inline-block;
        margin: 5px 8px;
        font-size: 15px;
      }

      main.search-section {
        padding: 15px;
      }

      .fondo {
        padding: 15px;
        border-radius: 8px;
      }

      .notificacion-box div {
        font-size: 15px;
        padding: 12px;
      }

  
    }

    @media (max-width: 480px) {
      header {
        padding: 8px;
      }

      .fondo h2 {
        font-size: 20px;
      }

      .fondo p {
        font-size: 14px;
      }

      .notificacion-box div {
        font-size: 14px;
        padding: 10px;
      }

      .notificacion-box small {
        font-size: 12px;
      }
    }
</style>
</head>
<body>
  <header>
    <div class="logo">
      <a href="../index-t.php">
        <img src="../../../imagenes/logo.png" alt="Search Job Logo">
      </a>
    </div>
    <nav>
      <?php if (isset($_SESSION['dni'])): ?>
      <a href="../perfil/perfil-t.php" title="Mi perfil" style="color:white; margin-right: 10px;">
        <?php echo htmlspecialchars($_SESSION['nombre']); ?>
        <img 
          src="<?php echo !empty($_SESSION['foto_perfil']) 
                    ? '../../../imagenes/perfiles/' . $_SESSION['foto_perfil'] 
                    : '../../../imagenes/cliente-logo.jpg'; ?>" 
          alt="Perfil" 
          style="width:40px; height:40px; border-radius:50%; vertical-align:middle;" 
        />
      </a>
      <?php endif; ?>
    </nav>
  </header>

  <!-- Botón de barra -->
  <ul><font size="5px"><label for="btn-menu">☰</label></font></ul>
  
  <!-- Menú -->
  <input type="checkbox" id="btn-menu">
  <div class="container-menu">
    <div class="cont-menu">
      <nav><br>
        <a href="../index-t.php" data-texto="home">Home</a>
        <a href="premium-t.html" data-texto="premium">Premium</a>
        <a href="categorias-t.php" data-texto="categorias">Categorías</a>
        <a href="notificaciones-t.php" data-texto="bandeja">Bandeja de entrada</a>
             <a href="adminPedidos-t.php" data-texto="adminPedidos">Administrador de pedidos</a>
        <a href="chat.php">Chat</a>

        <a href="configuracion-t.php" data-texto="configuracion">Configuración</a>
        <a href="ayuda-t.php" data-texto="ayuda">Ayuda</a>
      </nav>
      <label for="btn-menu">✖️</label>
    </div>
  </div>

  <main class="search-section">
    <section class="fondo">
      <h2 data-texto="titulo">Bandeja de entrada</h2>
      <p data-texto="descripcion">Aquí encontrarás mensajes, notificaciones y respuestas de profesionales o clientes.</p>
      <div class="notificacion-box">
        <?php if (count($notificaciones) > 0): ?>
          <?php foreach ($notificaciones as $n): ?>
            <div style="margin-bottom:10px; padding:10px; border-radius:8px; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,0.1);">
              <span><?php echo htmlspecialchars($n['mensaje']); ?></span><br>
              <small><?php echo date("d/m/Y H:i", strtotime($n['fecha'])); ?></small><br>
             
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p data-texto="sinNotificaciones">No tienes notificaciones.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

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
    body.style.color = '#f1f1f1';
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
      adminPedidos:"Administrador de perdidos",
      configuracion: "Configuración",
      ayuda: "Ayuda",
      titulo: "Bandeja de entrada",
      descripcion: "Aquí encontrarás mensajes, notificaciones y respuestas de profesionales o clientes.",
      sinNotificaciones: "No tienes notificaciones."
    },
    en: {
      tituloPagina: "Inbox - Search Job",
      home: "Home",
      premium: "Premium",
      categorias: "Categories",
      bandeja: "Inbox",
      adminPedidos:"Orders admin",
      configuracion: "Settings",
      ayuda: "Help",
      titulo: "Inbox",
      descripcion: "Here you will find messages, notifications and replies from professionals or clients.",
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
      descripcion: "Aqui você encontrará mensagens, notificações e respostas de profissionais ou clientes.",
      sinNotificaciones: "Você não tem notificações."
    }
  };

  textos.forEach(el => {
    const clave = el.getAttribute('data-texto');
    if (traducciones[idioma][clave]) {
      el.textContent = traducciones[idioma][clave];
    }
  });
};
</script>
</body>
</html>
