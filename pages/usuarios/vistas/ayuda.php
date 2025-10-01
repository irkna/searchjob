<?php

session_start();
?>
<!DOCTYPE html>
<html lang="es"> 
<head>
  <meta charset="UTF-8">
  <title data-texto="tituloPagina">Ayuda - Search Job</title>
  <link rel="stylesheet" href="../../../styles/style.css">
  <link rel="icon" href="../../../imagenes/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  span {
 color: rgba(0, 0, 0, 0.805);
    font-style:normal !important;
    font-size: 25px;
    text-shadow:2px 2px  #f2d8c6/*color del sombreado del titulo*/
}
</style>
</head>
<body>
  <header>
    <div class="logo">
      <a href="../index-u.php"><img src="../../../imagenes/logo.png" alt="Search Job Logo"></a>
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
        <?php endif; ?></nav>

  </header>

  <ul><font size="5px"><label for="btn-menu">☰</label></font></ul>
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
    <section class="fondo">
      <h2 data-texto="tituloAyuda">Ayuda</h2>
      <p data-texto="introAyuda">¿Tenés dudas o preguntas? Acá te respondemos las más comunes.</p>
      <div style="text-align: left; max-width: 700px; margin: 20px auto; padding: 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); background: white; color: #333;">
        
        <p><strong data-texto="preg1">¿Cómo contrato a un profesional?</strong><br>
        <span data-texto="resp1">Usá el buscador, elegí una categoría y enviá una solicitud desde su perfil.</span></p>

        <p><strong data-texto="preg2">¿Cómo me contacto con un cliente?</strong><br>
        <span data-texto="resp2">A través de la bandeja de entrada donde verás los pedidos recibidos.</span></p>

        <p><strong data-texto="preg3">¿Cómo edito un pedido?</strong><br>
        <span data-texto="resp3">Ingresá a "Administrador de pedidos" y podrás modificar los detalles.</span></p>

        <p><strong data-texto="preg4">¿Puedo ofrecer mis servicios en más de una categoría?</strong><br>
        <span data-texto="resp4">Sí, podés editar tu perfil y agregar habilidades o especialidades extra.</span></p>

      </div>
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
      tituloPagina: "Ayuda - Search Job",
      tituloAyuda: "Ayuda",
      introAyuda: "¿Tenés dudas o preguntas? Acá te respondemos las más comunes.",
      preg1: "¿Cómo contrato a un profesional?",
      resp1: "Usá el buscador, elegí una categoría y enviá una solicitud desde su perfil.",
      preg2: "¿Cómo me contacto con un cliente?",
      resp2: "A través de la bandeja de entrada donde verás los pedidos recibidos.",
      preg3: "¿Cómo edito un pedido?",
      resp3: "Ingresá a \"Administrador de pedidos\" y podrás modificar los detalles.",
      preg4: "¿Puedo ofrecer mis servicios en más de una categoría?",
      resp4: "Sí, podés editar tu perfil y agregar habilidades o especialidades extra."
      , iniciaSesion: "Inicia sesión",
    unete: "Únete",
     home: "Home",
    premium: "Premium",
    categorias: "Categorías",
    bandeja: "Bandeja de entrada",
    adminPedidos: "Administrador de pedidos",
    configuracionMenu: "Configuración",
    ayuda: "Ayuda"
    },
    en: {
      tituloPagina: "Help - Search Job",
      tituloAyuda: "Help",
      introAyuda: "Do you have doubts or questions? Here we answer the most common ones.",
      preg1: "How do I hire a professional?",
      resp1: "Use the search, select a category, and send a request from their profile.",
      preg2: "How do I contact a client?",
      resp2: "Through the inbox where you will see the received requests.",
      preg3: "How do I edit an order?",
      resp3: "Go to 'Order Manager' and you can modify the details.",
      preg4: "Can I offer my services in more than one category?",
      resp4: "Yes, you can edit your profile and add extra skills or specialties."
    , iniciaSesion: "Sign in",
    unete: "Join",
       home: "Home",
    premium: "Premium",
    categorias: "Categories",
    bandeja: "Inbox",
    adminPedidos: "Order Manager",
    configuracionMenu: "Settings",
    ayuda: "Help"
    },
    pt: {
      tituloPagina: "Ajuda - Search Job",
      tituloAyuda: "Ajuda",
      introAyuda: "Você tem dúvidas ou perguntas? Aqui respondemos às mais comuns.",
      preg1: "Como contrato um profissional?",
      resp1: "Use o buscador, escolha uma categoria e envie uma solicitação do perfil dele.",
      preg2: "Como me contato com um cliente?",
      resp2: "Através da caixa de entrada onde você verá as solicitações recebidas.",
      preg3: "Como edito um pedido?",
      resp3: "Vá para 'Administrador de pedidos' e você poderá modificar os detalhes.",
      preg4: "Posso oferecer meus serviços em mais de uma categoria?",
      resp4: "Sim, você pode editar seu perfil e adicionar habilidades ou especialidades extras."
     ,iniciaSesion: "Entrar",
    unete: "Junte-se",
       home: "Início",
    premium: "Premium",
    categorias: "Categorias",
    bandeja: "Caixa de entrada",
    adminPedidos: "Administrador de pedidos",
    configuracionMenu: "Configurações",
    ayuda: "Ajuda"
    }
  };

  textos.forEach(el => {
    const clave = el.getAttribute('data-texto');
    if(traducciones[idioma][clave]) el.textContent = traducciones[idioma][clave];
  });
});
  </script>





<!-- CHATBOT DE MINGO -->
<div id="chatbot-mingo">
  <div id="chat-header">
    <img src="../../../imagenes/mingo.png" alt="Mingo" id="mingo-avatar">
    <span>Mingo - Tu asistente</span>
    <button id="cerrar-chat">✖</button>
  </div>
  <div id="chat-body"></div>
  <div id="chat-input">
    <input type="text" id="mensaje" placeholder="Escribí tu pregunta...">
    <button id="enviar">Enviar</button>
  </div>
</div>

<!-- Botón flotante -->
<button id="abrir-chat">💬</button>

<style>
  #abrir-chat {
    position: fixed;
    bottom: 60px;
    right: 20px;
    background: #eccdb9;
    border: none;
    border-radius: 50%;
    width: 60px; height: 60px;
    font-size: 25px;
    color: rgb(0, 0, 0);
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    z-index: 9999; /*  */
  }

  #chatbot-mingo {
    display: none;
    position: fixed;
    bottom: 130px;
    right: 20px;
    width: 620px;
    height: 520px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.4);
    flex-direction: column;
    overflow: hidden;
    font-family: Arial, sans-serif;
    z-index: 9999; /*  */
  }

  #chat-header {
    background:#eccdb9;
    color: rgb(0, 0, 0);
    padding: 10px;
    display: flex;
    align-items: center;
  }

  #chat-header img {
    width: 75px;
    height: 75px;
    border-radius: 50%;
    margin-right: 8px;
  }

  #cerrar-chat {
    margin-left: auto;
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
  }

  #chat-body {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    background: #f9f9f9;/*fondo*/
    display: flex;
    flex-direction: column;
  }

  .mensaje {
    margin: 5px 0;
    padding: 8px 12px;
    border-radius: 12px;
    max-width: 80%;
    word-wrap: break-word;
  }

  .user { background:#6d6c6c; align-self: flex-end; }
  .mingo { background:#494949; align-self: flex-start; }

  #chat-input {
    display: flex;
    border-top: 1px solid #ffffff;
  }

  #chat-input input {
    flex: 1;
    padding: 10px;
    border: none;
    outline: none;
  }

  #chat-input button {
    background:rgb(226, 170, 170); ;
    border: none;
    color: rgb(255, 255, 255);
    padding: 10px 15px;
    cursor: pointer;
  }
</style>

<script>
  const abrirChat = document.getElementById("abrir-chat");
  const cerrarChat = document.getElementById("cerrar-chat");
  const chatbot = document.getElementById("chatbot-mingo");
  const chatBody = document.getElementById("chat-body");
  const input = document.getElementById("mensaje");
  const enviar = document.getElementById("enviar");

const respuestas = {
       "contratar": "Para contratar a un profesional usá el buscador y enviá una solicitud desde su perfil.",
    "pago": "Podés pagar con Mercado Pago dentro de la plataforma.",
    "turno": "Los turnos seran dados según la disponibilidad del profesional,una ves contratados llegan a un acuerdo en comun para realizar el trabajo.",
    "chat": "Tenés un chat interno para hablar con el profesional antes de confirmar el servicio.",
    "reseña": "Después del servicio podés dejar una calificación sobre el profesional.",
    "contrato":"Para contratar a un profesional puedes ir a categorias, ingresar a un perfil y desde ahi presionar en boton contratar",
    "seguro":"Es seguro utilizar este sitio web, usted puede contratar a un trabajador sin compromiso, pueden llegar a un acuerdo para realizar el trabajo, y de no ser asi se puede cancelar en cualquier momento",
    "mensaje":"Para enviar un mensaje privado a un trabajador o usuario debe iniciar sesion,si ya inicio sesion ingrese a un perfil desde categorias y en la parte inferior derecha encontrara el boton para enviar un mensaje, si envio un mensaje y no fue respondido sea paciente cuando el usuario este disponible le contestara",
    "categorias": "Encontrás los profesionales organizados por categorías en el menú principal.",
"buscar": "Podés usar el buscador para encontrar un profesional por nombre, rubro o ubicación.",
"ubicacion": "Podés filtrar los trabajadores por ubicación para encontrar alguien cerca tuyo.",
"perfil": "En cada perfil de profesional podés ver su experiencia, reseñas y precio estimado.",
"disponibilidad": "Cada profesional actualiza su disponibilidad, y pueden coordinar juntos el mejor horario.",
"cancelar": "Si necesitás cancelar un servicio, podés hacerlo desde tus pedidos en curso.",
"calificacion": "Después de terminado el servicio podés calificar al profesional con estrellas y comentarios.",
"verreseñas": "En cada perfil podés ver las reseñas que dejaron otros clientes.",
"confianza": "Las calificaciones ayudan a que elijas profesionales con buena reputación.",
"olvide": "Si olvidaste tu contraseña podés restablecerla desde el enlace '¿Olvidaste tu contraseña?'.",
"editarperfil": "Podés editar tu perfil desde la sección 'Mi cuenta'.",
"foto": "Para cambiar tu foto de perfil ingresá a tu cuenta y hacé clic en 'Editar perfil'.",
"porqueusarla": "Acá podés encontrar profesionales calificados con reseñas reales de otros clientes.",
"ventajas": "La ventaja de usar esta página es que tenés todo en un solo lugar: búsqueda, contratación, chat y pago seguro.",
"quienesusan": "Lo usan personas que necesitan contratar un servicio y profesionales que ofrecen su trabajo.",
"quetal": "Esta página es una plataforma para conectar clientes con trabajadores y profesionales de distintos rubros.",
"parasirve": "Sirve para buscar, contratar y pagar de forma segura a profesionales que ofrecen sus servicios.",
"quees": "Es un sitio web donde podés encontrar trabajadores por categoría, ver sus perfiles y contratarlos.",
"como funciona": "Funciona así: buscás un trabajador, lo contratás desde su perfil y coordinan el servicio.",
"objetivo": "El objetivo es facilitar que clientes y profesionales se conecten de manera rápida, segura y confiable.",
"hola": "¡Hola! Soy Mingo 😸 , tu asistente. ¿Querés que te guíe?",
"ayuda": "Podés preguntarme sobre contratar, pagar, chatear o reseñas. Estoy para ayudarte.",
"chiste": "¿Querés un chiste? 😸  ¿Qué es mas frio que un hielo? ¡Un incha de river!",
"metodos pago": "Aceptamos pagos con Mercado Pago a través de links seguros que cada profesional tiene en su perfil.",
"cvu": "Cada profesional puede cargar su CVU o link de pago en su perfil para que abones directo.",
"comprobante": "Después de pagar, podés guardar el comprobante por seguridad, aunque el sistema ya registra el pago.",
"quetal": "Esta página es una plataforma para conectar clientes con trabajadores y profesionales de distintos rubros.",
"parasirve": "Sirve para buscar, contratar y pagar de forma segura a profesionales que ofrecen sus servicios.",
"quees": "Es un sitio web donde podés encontrar trabajadores por categoría, ver sus perfiles y contratarlos.",
"como funciona": "Funciona así: buscás un trabajador, lo contratás desde su perfil y coordinan el servicio.",
"objetivo": "El objetivo es facilitar que clientes y profesionales se conecten de manera rápida, segura y confiable.",
"objetivo": "El objetivo es facilitar que clientes y profesionales se conecten de manera rápida, segura y confiable.",
"sirve": "Acá podés encontrar profesionales calificados con reseñas reales de otros clientes.",
"que hace": "Acá podés encontrar profesionales calificados con reseñas reales de otros clientes.",
"ventajas": "La ventaja de usar esta página es que tenés todo en un solo lugar: búsqueda, contratación, chat y pago seguro.",
"quienes usan": "Lo usan personas que necesitan contratar un servicio y profesionales que ofrecen su trabajo.",
"inicio sesion": "Si ya tenés cuenta, ingresá tu correo y contraseña en Iniciar sesión.",
"registro": "Si todavía no tenés cuenta, andá a Registrarse y completá tus datos para crear una cuenta.",
"diferencia": "Registrarse es para crear tu cuenta por primera vez. Iniciar sesión es para entrar a tu cuenta ya creada.",
"problema al ingresar": "Si no podés entrar, revisá tu correo y contraseña. También podés usar '¿Olvidaste tu contraseña?'.",
"primera vez": "Si es tu primera vez en la página, tenés que registrarte antes de poder iniciar sesión.",
"pedidos": "En la sección de pedidos podés ver todos los servicios que contrataste.",
"mis pedidos": "En 'Mis pedidos' encontrás el historial de contratos: los que están en curso y los finalizados.",
"estado": "Cada pedido tiene un estado: en curso, finalizado o cancelado.",
"detalle pedido": "En cada pedido podés ver los datos del servicio, el trabajador, el costo y la forma de pago.",
"pagar pedido": "Cuando finalizás un pedido, la plataforma te redirige al link de pago del trabajador.",
"cancelar pedido": "Si necesitás cancelar un pedido, podés hacerlo desde la sección de pedidos en curso.",
"seguimiento pedido": "Podés seguir el avance de tu servicio desde la sección de pedidos, en todo momento.",
"finaliza pedido": "Cuando el trabajo está hecho, podés finalizar el pedido y dejar una reseña del profesional.",
"finaliza contrato": "Cuando el trabajo está hecho, podés finalizar el pedido y dejar una reseña del profesional.",
"historial": "Tus pedidos finalizados quedan guardados como historial para que los revises cuando quieras.",
"reclamo": "Si tuviste un problema con un pedido, podés reportarlo desde el detalle del contrato.",

    "default": "Soy Mingo 😸 . No entendí tu pregunta, podrias utilizar palabras mas especificas sobre lo que necesites saber",
  };
  // Función para agregar mensajes
  function agregarMensaje(texto, clase) {
    const msg = document.createElement("div");
    msg.classList.add("mensaje", clase);
    msg.textContent = texto;
    chatBody.appendChild(msg);
    chatBody.scrollTop = chatBody.scrollHeight;
  }

  // Procesar mensajes del usuario
  function procesarMensaje() {
    const texto = input.value.trim().toLowerCase();
    if (!texto) return;
    agregarMensaje(texto, "user");
    input.value = "";

    let respuesta = respuestas.default;
    for (let key in respuestas) {
      if (texto.includes(key)) {
        respuesta = respuestas[key];
        break;
      }
    }
    setTimeout(() => agregarMensaje(respuesta, "mingo"), 500);
  }

  enviar.addEventListener("click", procesarMensaje);
  input.addEventListener("keypress", e => {
    if (e.key === "Enter") procesarMensaje();
  });

  // 👇 Al abrir el chat, Mingo saluda automáticamente
  abrirChat.addEventListener("click", () => {
    chatbot.style.display = "flex";
    chatBody.innerHTML = ""; // limpia mensajes anteriores
    setTimeout(() => {
      agregarMensaje("¡Hola! Soy Mingo 🐧. Estoy acá para ayudarte. Preguntame lo que quieras sobre la plataforma 🚀", "mingo");
    }, 300);
  });

  cerrarChat.addEventListener("click", () => chatbot.style.display = "none");
</script>


</body>
</html>

