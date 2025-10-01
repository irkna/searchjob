<?php

session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Configuración - Search Job</title>
  <link rel="stylesheet" href="../../../styles/style.css">
  <link rel="icon" href="../../../imagenes/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
      /* Tema claro */
    body.tema-claro {
      background-color: #f9f9f9 !important;
      color: #333 !important;
    }

    /* Tema oscuro */
    body.tema-oscuro {
      background-color: #2e2e2e !important;
      color: #f1f1f1 !important;
    }

    body.tema-claro .search-section {
      background-color: #f9f9f9 !important;
      color: #333 !important;
    }

    body.tema-oscuro .search-section {
      background-color: #2e2e2e !important;
      color: #f1f1f1 !important;
    }

    .config-box {
      background-color: #fff;
      color: #333;
      padding: 20px;
      margin: 20px auto;
      max-width: 600px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      text-align: left;
    }

    .config-box h3 {
      margin-bottom: 10px;
      color: #4e4a57;
    }

    .config-box label,
    .config-box select,
    .config-box button {
      display: block;
      margin: 10px 0;
    }

    #terminos {
      display: none;
      margin-top: 10px;
      background-color: #f9f9f9;
      padding: 15px;
      border-radius: 8px;
    }
      span {
 color: rgba(28, 28, 28, 0.702);
    font-style:normal !important;
    font-size: 20px;
    text-shadow:none !important/*color del sombreado del titulo*/
}
  </style>
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

  <!--boton de barra -->
  <ul>
    <font size="5px"><label for="btn-menu">☰</label></font>
  </ul>

  <!--menu -->
  <input type="checkbox" id="btn-menu">
  <div class="container-menu">
    <div class="cont-menu">
      <nav><br>
    
  <a href="../index-u.php" data-texto="home">Home</a>
  <a href="categorias.php" data-texto="categorias">Categorías</a>
  <a href="notificaciones.php" data-texto="bandeja">Bandeja de entrada</a>
        <a href="adminPedidos.php" data-texto="adminPedidos">Pedidos</a>
        <a href="chat.php">Chat</a>

  <a href="configuracion.php" data-texto="configuracionMenu">Configuración</a>
  <a href="ayuda.php" data-texto="ayuda">Ayuda</a>
</nav>

    
      <label for="btn-menu">✖️</label>
    </div>
  </div>

  <main class="search-section">
    <h2 data-texto="configuracion">Configuración</h2>

<section class="config-box">
  <h3 data-texto="apariencia">Apariencia</h3>
  <label>
    <input type="radio" name="tema" value="claro">
    <span data-texto="claro">Claro</span>
  </label>
  <label>
    <input type="radio" name="tema" value="oscuro">
    <span data-texto="oscuro">Oscuro</span>
  </label>
</section>

    <section class="config-box">
      <h3 data-texto="idioma">Idioma</h3>
      <select id="idioma-select">
        <option value="es">Español</option>
        <option value="en">Inglés</option>
        <option value="pt">Portugués</option>
      </select>
    </section>

   

    <section class="config-box">
      <h3 data-texto="tituloTerminos">Términos y condiciones</h3>

      <a href="terminosycondiciones.php" class="btn" data-texto="leerTerminos">
        Leer términos y condiciones
      </a>
 
      <p>
        <strong data-texto="condUsoTitulo">Condiciones de uso:</strong>
        <p data-texto="condUso1">
          Al utilizar esta plataforma, aceptás respetar a los demás usuarios, no publicar contenido ofensivo, y usar la plataforma de forma responsable.
        </p>
      </p>

      <p data-texto="condUso2">
        El servicio puede cambiar o interrumpirse en cualquier momento sin previo aviso. Tus datos se manejan según nuestra política de privacidad.
      </p>
    </section>

    <section class="card">
      <button onclick="aplicarConfiguracion()" class="btn" data-texto="aplicar">Aplicar</button>
    </section>
  </main>

  <footer>
    <span>Search Job</span>
    <div class="social-icons">
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="#"><i class="fab fa-whatsapp"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
    </div>
  </footer>

  <script>
    const traducciones = {
      es: {
        iniciaSesion: "Inicia sesión",
        unete: "Únete",
        configuracion: "Configuración",
        apariencia: "Apariencia",
        idioma: "Idioma",
        estadoOnlineTitulo: "Estado en línea",
        estadoOnlineChk: "Mostrar mi estado en línea en el perfil",
        aplicar: "Aplicar",
        tituloTerminos: "Términos y condiciones",
        leerTerminos: "Leer términos y condiciones",                          
        condUsoTitulo: "Condiciones de uso:",
        condUso1: "Al utilizar esta plataforma, aceptás respetar a los demás usuarios, no publicar contenido ofensivo, y usar la plataforma de forma responsable.",
        condUso2: "El servicio puede cambiar o interrumpirse en cualquier momento sin previo aviso. Tus datos se manejan según nuestra política de privacidad."
,      home: "Home",
    premium: "Premium",
    categorias: "Categorías",
    bandeja: "Bandeja de entrada",
    adminPedidos: "Pedidos",
    configuracionMenu: "Configuración",
    ayuda: "Ayuda"
    ,claro:"claro",
    oscuro:"oscuro"
      },
      en: {
        iniciaSesion: "Sign in",
        unete: "Join",
        configuracion: "Settings",
        apariencia: "Appearance",
        idioma: "Language",
        estadoOnlineTitulo: "Online Status",
        estadoOnlineChk: "Show my online status on profile",
        aplicar: "Apply",
        tituloTerminos: "Terms and Conditions",
        leerTerminos: "Read Terms and Conditions",
        condUsoTitulo: "Terms of use:",
        condUso1: "By using this platform, you agree to respect other users, not publish offensive content, and use the platform responsibly.",
        condUso2: "The service may change or be discontinued at any time without prior notice. Your data is handled according to our privacy policy."
     
    , home: "Home",
    premium: "Premium",
    categorias: "Categories",
    bandeja: "Inbox",
    adminPedidos: "Orders ",
    configuracionMenu: "Settings",
    ayuda: "Help", 
    claro:"light",  
    oscuro:"dark"
  },
      pt: {
        iniciaSesion: "Entrar",
        unete: "Junte-se",
        configuracion: "Configurações",
        apariencia: "Aparência",
        idioma: "Idioma",
        estadoOnlineTitulo: "Status Online",
        estadoOnlineChk: "Mostrar meu status online no perfil",
        aplicar: "Aplicar",
        tituloTerminos: "Termos e Condições",
        leerTerminos: "Ler Termos e Condições",
        condUsoTitulo: "Condições de uso:",
        condUso1: "Ao usar esta plataforma, você concorda em respeitar os outros usuários, não publicar conteúdo ofensivo e usar a plataforma de forma responsável.",
        condUso2: "O serviço pode mudar ou ser interrompido a qualquer momento sem aviso prévio. Seus dados são tratados conforme nossa política de privacidade."
      
      , home: "Início",
    premium: "Premium",
    categorias: "Categorias",
    bandeja: "Caixa de entrada",
    adminPedidos: "Pedidos",
    configuracionMenu: "Configurações",
    ayuda: "Ajuda"
    ,claro:"claro",
    oscuro:"escure"
  }
    };

    function aplicarConfiguracion() {
      const tema = document.querySelector('input[name="tema"]:checked')?.value;
      const idioma = document.getElementById('idioma-select').value;
      const estadoOnline = document.getElementById('estado-online').checked;

      if (tema) {
        localStorage.setItem('tema', tema);
        aplicarTema(tema);
      }

      localStorage.setItem('idioma', idioma);
      localStorage.setItem('estadoOnline', estadoOnline ? "visible" : "oculto");

      alert("Configuración aplicada correctamente.");
      aplicarTraducciones(idioma);
    }

    function aplicarTema(tema) {
      const body = document.body;
      if (tema === "oscuro") {
        body.classList.remove("tema-claro");
        body.classList.add("tema-oscuro");
      } else {
        body.classList.remove("tema-oscuro");
        body.classList.add("tema-claro");
      }
    }

    function aplicarTraducciones(idioma) {
      document.querySelectorAll("[data-texto]").forEach(el => {
        const clave = el.getAttribute("data-texto");
        if (traducciones[idioma] && traducciones[idioma][clave]) {
          el.textContent = traducciones[idioma][clave];
        }
      });
    }

    window.onload = function() {
      const tema = localStorage.getItem("tema");
      const idioma = localStorage.getItem("idioma") || "es";
      const estadoOnline = localStorage.getItem("estadoOnline");

      if (tema) {
        const radio = document.querySelector(`input[name="tema"][value="${tema}"]`);
        if (radio) radio.checked = true;
        aplicarTema(tema);
      }

      if (idioma) {
        document.getElementById('idioma-select').value = idioma;
        aplicarTraducciones(idioma);
      }

      if (estadoOnline === "visible") {
        document.getElementById('estado-online').checked = true;
      }
    };
  </script>
</body>
</html>
