<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Términos y Condiciones</title>
  <link rel="stylesheet" href="../../../styles/style.css">
  <link rel="icon" href="../../../imagenes/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
/* --- RESPONSIVE  --- */
    @media (max-width: 768px) {
    

      ul label {
        display: block;
        position: fixed;
        top: 15px;
        left: 20px;
        z-index: 10001;
      }

      .fondo {
        width: 90%;
        padding: 15px;
      }

      h2 {
        font-size: 20px;
      }

      .fondo p, .fondo strong {
        font-size: 15px;
      }
    }</style>
</head>
<body>
  <header>
    <div class="logo">
      <a href="../index-u.php">
        <img src="../../../imagenes/logo.png" alt="Logo"></a>
    </div>
    <nav>
      <?php if (isset($_SESSION['dni'])): ?>
        <a href="../perfil/perfil.php" title="Mi perfil" style="color:white; margin-right: 10px;">
          <?php echo htmlspecialchars($_SESSION['nombre']); ?>
          <img 
            src="<?php echo !empty($_SESSION['foto_perfil']) 
                        ? '../../../imagenes/perfiles/' . $_SESSION['foto_perfil'] 
                        : '../../../imagenes/perfiles/cliente-logo.jpg'; ?>" 
            alt="Perfil" 
            style="width:40px; height:40px; border-radius:50%; vertical-align:middle;" 
          />
        </a>
      <?php endif; ?>
    </nav>
  </header>

  <!--boton de barra-->
  <ul>
    <font size="5px"><label for="btn-menu">☰</label></font>                    
  </ul>

  <!--menu-->
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
      <h2 data-texto="terminos">Términos y Condiciones</h2>
      <p data-texto="usoPlataforma">Al usar esta plataforma, aceptás las siguientes condiciones:</p>
      <div style="text-align: left; max-width: 700px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); color: #333;">
        <p><strong data-texto="noOfensivo">No publicar contenido ofensivo.</strong></p>
        <p><strong data-texto="respetar">Respetar a otros usuarios.</strong></p>
        <p><strong data-texto="modificacion">El sitio puede modificar sus servicios sin previo aviso.</strong></p>
        <p><strong data-texto="datosPrivacidad">Los datos se tratan según la política de privacidad</strong></p>
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

  window.addEventListener('DOMContentLoaded', () => {
    aplicarTemaGlobal();

    const idioma = localStorage.getItem('idioma') || 'es';
    const traducciones = {
      es: {
        home: "Home", premium: "Premium", categorias: "Categorías", bandeja: "Bandeja de entrada",
        adminpedidos: "Administrador de pedidos", configuracion: "Configuración", ayuda: "Ayuda",
        terminos: "Términos y Condiciones", usoPlataforma: "Al usar esta plataforma, aceptás las siguientes condiciones:",
        noOfensivo: "No publicar contenido ofensivo.", respetar: "Respetar a otros usuarios.",
        modificacion: "El sitio puede modificar sus servicios sin previo aviso.",
        datosPrivacidad: "Los datos se tratan según la política de privacidad"
      },
      en: {
        home: "Home", premium: "Premium", categorias: "Categories", bandeja: "Inbox",
        adminpedidos: "Order Manager", configuracion: "Settings", ayuda: "Help",
        terminos: "Terms and Conditions", usoPlataforma: "By using this platform, you agree to the following conditions:",
        noOfensivo: "Do not post offensive content.", respetar: "Respect other users.",
        modificacion: "The site may modify its services without prior notice.",
        datosPrivacidad: "Data is processed according to the privacy policy"
      },
      pt: {
        home: "Início", premium: "Premium", categorias: "Categorias", bandeja: "Caixa de entrada",
        adminpedidos: "Administrador de pedidos", configuracion: "Configurações", ayuda: "Ajuda",
        terminos: "Termos e Condições", usoPlataforma: "Ao usar esta plataforma, você concorda com as seguintes condições:",
        noOfensivo: "Não publicar conteúdo ofensivo.", respetar: "Respeitar outros usuários.",
        modificacion: "O site pode modificar seus serviços sem aviso prévio.",
        datosPrivacidad: "Os dados são tratados de acordo com a política de privacidade"
      }
    };

    // Traducir elementos con data-texto
    document.querySelectorAll('[data-texto]').forEach(el => {
      const clave = el.getAttribute('data-texto');
      if(traducciones[idioma][clave]) el.textContent = traducciones[idioma][clave];
    });
  });
  </script>
</body>
</html>
