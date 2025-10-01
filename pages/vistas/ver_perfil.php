<?php
include("../conexion.php");
session_start();

if (!isset($_GET['dni'])) {
    echo "Perfil no encontrado.";
    exit();
}

$dni = $_GET['dni'];

// Cargar datos del usuario
$sql = "SELECT u.nombre, u.telefono, u.localidad, t.ocupacion, u.foto_perfil 
        FROM usuarios u
        INNER JOIN trabajador t ON u.dni = t.identificador
        WHERE u.dni = ?";
$stmt = $conex->prepare($sql);
if (!$stmt) {
    die("Error en prepare(): " . $conex->error);
}
$stmt->bind_param("s", $dni);
$stmt->execute();
$resultado = $stmt->get_result();
$datos = $resultado->fetch_assoc();

if (!$datos) {
    echo "Perfil no encontrado.";
    exit();
}

// Cargar publicaciones
$sql_pub = "SELECT id_publicacion, foto_publicacion, descripcion FROM publicacion WHERE dni_usuario = ?";
$stmt_pub = $conex->prepare($sql_pub);
if (!$stmt_pub) {
    die("Error en prepare() publicaciones: " . $conex->error);
}
$stmt_pub->bind_param("s", $dni);
$stmt_pub->execute();
$pub_result = $stmt_pub->get_result();
$publicaciones = $pub_result->fetch_all(MYSQLI_ASSOC);

// Cargar calificaciones
$sql_cal = "SELECT u.nombre, c.puntuacion, c.comentario 
            FROM califica c
            INNER JOIN usuarios u ON c.dni_usuario = u.dni
            WHERE c.dni_trabajador = ?";
$stmt_cal = $conex->prepare($sql_cal);
if (!$stmt_cal) {
    die("Error en prepare() calificaciones: " . $conex->error);
}
$stmt_cal->bind_param("s", $dni);
$stmt_cal->execute();
$res_cal = $stmt_cal->get_result();
$calificaciones = $res_cal->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
    <title>Perfil de <?php echo htmlspecialchars($datos['nombre']); ?></title>
    <link rel="stylesheet" href="../../styles/perfil.css">
    <link rel="stylesheet" href="../../styles/style.css">
    <link rel="icon" href="../../imagenes/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>

.calificaciones {
  max-width: 400px !important;
  margin: 60px auto 20px;
  padding: 20px;
}
.calificaciones2 {
  max-width: 800px !important;
  margin: 60px auto 20px;
  padding: 20px;
    overflow: hidden;
  position: relative;
  margin: 10px;
}

.calificaciones-lista {
  max-height: 200px;
  overflow-y: auto;
  padding-right: 10px;
  margin-bottom: 1px;
  border-radius: 10px;
  background: #f9f9f9;
  padding: 15px;
  box-shadow: 0 0 10px rgba(0,0,0,0.05);
}
footer {
    margin-top: -50px !important;  /* reduce la distancia entre el contenido y el footer */
    text-align: center;
}
.calificacion-card {
  background: white;
  padding: 15px;
  margin-bottom: 10px;
  border-left: 5px solid #5e5b64;
  border-radius: 8px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.calificacion-card .estrella {
  color: #0e0a04ff;
  font-size: 1.5em;
}

.formulario-calificacion {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.perfil{
    margin-left:12% !important;
}

.formulario-calificacion select,
.formulario-calificacion textarea {
  width: 100%;
  padding: 10px;
  margin: 8px 0 15px;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.formulario-calificacion button {
  background: #5e5b64;
  color: white;
  border: none;
  padding: 12px 18px;
  border-radius: 6px;
  cursor: pointer;
  transition: 0.3s;
}

.formulario-calificacion button:hover {
  background: #3d3b40;
}

/**//* */
.imagen-contenedor {
    width: 250px;      /* ancho fijo de cada imagen */
    height: 200px;     /* alto fijo de cada imagen */
    overflow: hidden;  /* recorta lo que sobresalga */
    margin: 0 auto;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.imagen-contenedor img {
    width: 100%;
    height: 100%;
    object-fit: cover;  /* mantiene proporción y recorta exceso */
    display: block;
}


/**//* */
        .imagenes {
            background: transparent !important;
            box-shadow: none !important;
        }
        .foto-container {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #5e5b64;
            margin: 0 auto 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .foto-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }
        .calificaciones {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        .calificaciones select,
        .calificaciones textarea {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .calificaciones h3 {
            margin-top: 30px;
        }
/**/ /*comentar publicaciones*/
/* Modal */
    .modal {
      position: fixed;
      z-index: 1000;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.7);
      display: none; justify-content: center; align-items: center;
    }
    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 12px;
      max-width: 600px;
      width: 90%;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
    }
   .cerrar {
  position: absolute;
  right: 2px;
  top: -1px;
  font-size: 40px;    
  color: black;     
  cursor: pointer;
}

    #comentarios textarea {
      width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;
    }
    #comentarios button {
      margin-top: 8px; padding: 6px 12px; background: #333; color: white; border: none; border-radius: 6px; cursor: pointer;
    }
    .perfil {
    max-height: 550px !important; /* altura máxima del perfil */
    overflow-y: auto;  /* scroll interno si se supera el alto */
    padding: 20px;
    border-radius: 12px;
    background-color: #fff; /* opcional, según tema */
}

/**//*foto perfil */
#modalFotoPerfil {
  position: fixed;
  z-index: 1000;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.9); /* fondo oscuro */
  display: none;
  justify-content: center;
  align-items: center;
}

#modalFotoPerfil img {
  max-width: 90%;
  max-height: 90%;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0,0,0,0.5);
}

#modalFotoPerfil .cerrar {
  position: absolute;
  top: 20px;
  right: 30px;
  font-size: 50px;
  color: white;
  cursor: pointer;
}
/* Foto de perfil a pantalla casi completa en el modal */
#modalFotoPerfil img#fotoPerfilGrande {
  width: 96vw !important;   /* ocupa casi todo el ancho de la ventana */
  height: 96vh !important;  /* ocupa casi todo el alto de la ventana */
  object-fit: contain;      /* mantiene proporción, sin recortar */
  border-radius: 10px;
  box-shadow: 0 0 25px rgba(0,0,0,0.7);
  display: block;
}


    </style>
</head>
<body>
       <header>
       <div class="logo">
     <a href="../../index.html">
                <img src="../../imagenes/logo.png" alt="Search Job Logo">
            </a> 
    </div>
       <nav>
  <a href="../registro/iniciosesion.html" class="btn" data-texto="iniciaSesion">Inicia sesión</a>
  <a href="../usuarios/formulario/registro/unete.php" class="btn" data-texto="unete">Únete</a>
</nav>
    </header>

    <!--botón de barra-->
    <ul><font size="5px"><label for="btn-menu">☰</label></font></ul>

    <!--menú-->
    <input type="checkbox" id="btn-menu">
    <div class="container-menu">
        <div class="cont-menu">
            <nav><br>
     <a href="../../index.html" data-texto="home">Home</a>
  <a href="premium.html" data-texto="premium">Premium</a>
  <a href="categorias.php" data-texto="categorias">Categorías</a>
  <a href="notificaciones.html" data-texto="bandeja">Bandeja de entrada</a>
  <a href="adminpedidos.html" data-texto="adminPedidos">Administrador de pedidos</a>
  <a href="configuracion.html" data-texto="configuracion">Configuración</a>
  <a href="ayuda.html" data-texto="ayuda">Ayuda</a>
            </nav>
            <label for="btn-menu">✖️</label>
        </div>
    </div>

    <main>
       
        <div class="perfil">
            <br>
           <div class="foto-container">
    <img src="../../imagenes/perfiles/<?php echo htmlspecialchars($datos['foto_perfil']); ?>" 
         alt="Foto de perfil" 
         onclick="abrirFotoPerfil(this.src)" 
         style="cursor: zoom-in;">
</div>
<div id="modalFotoPerfil" class="modal" style="display:none; justify-content:center; align-items:center;">
  <span class="cerrar" onclick="cerrarFotoPerfil()">&times;</span>
  <img id="fotoPerfilGrande" src="" alt="Foto de perfil grande">
</div>


            <h2><?php echo htmlspecialchars($datos['nombre']); ?></h2>
            <br>
           <div class="info-item">
    <i class="fas fa-phone"></i>
    <strong data-texto="celular">Celular:</strong>&nbsp;<?php echo htmlspecialchars($datos['telefono']); ?>
</div>

<div class="info-item">
    <i class="fas fa-map-marker-alt"></i>
    <strong data-texto="localidad">Localidad:</strong>&nbsp;<?php echo htmlspecialchars($datos['localidad']); ?>
</div>

<div class="info-item">
    <i class="fas fa-info"></i>
    <strong data-texto="ocupacion">Ocupación:</strong>&nbsp;<?php echo htmlspecialchars($datos['ocupacion']); ?>
</div>
        
          </div>

        <div class="imagenes">
            <div class="imagenes-preview">
                <?php foreach ($publicaciones as $pub): ?>
  <div class="tarjeta" onclick="abrirModal('<?php echo addslashes($pub['foto_publicacion']); ?>', '<?php echo addslashes($pub['descripcion']); ?>', '<?php echo $pub['id_publicacion']; ?>')">
    <div class="imagen-contenedor">
        <img src="../../imagenes/publicaciones/<?php echo htmlspecialchars($pub['foto_publicacion']); ?>" alt="Publicación">
    </div>
    <div class="descripcion-publicacion">
        <p><?php echo htmlspecialchars($pub['descripcion']); ?></p>
    </div>
</div>

<?php endforeach; ?>

            </div>
            
            <div id="modalPublicacion" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="cerrar" onclick="cerrarModal()">&times;</span>
    <img id="modalImagen" src="" alt="Imagen grande" style="max-width:100%; border-radius:10px;">
    <p id="modalDescripcion"></p>
    
 <div id="comentarios">
      <h3>Comentarios</h3>
      <div id="listaComentarios"></div>
      <form id="formComentario">
        <input type="hidden" name="id_publicacion" id="idPublicacionInput">
        <textarea name="comentario" placeholder="Escribe un comentario..." required></textarea>
        <button type="submit">Comentar</button>
        
      </form>
    </div>
  </div>
</div>

      <!-- Sección de Calificaciones -->
<section class="calificaciones2">
    <h2>Calificaciones</h2>

    <div class="calificaciones-lista">
        <?php if (count($calificaciones) > 0): ?>
            <?php foreach ($calificaciones as $cal): ?>
                <div class="calificacion-card">
                    <strong><?php echo htmlspecialchars($cal['nombre']); ?></strong><br>
                    <span class="estrella">⭐ <?php echo htmlspecialchars($cal['puntuacion']); ?>/5</span>
                    <p><?php echo nl2br(htmlspecialchars($cal['comentario'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">Este trabajador aún no tiene calificaciones.</p>
        <?php endif; ?>
    </div>

   
</section>




        </div>

       <!-- Sección de Calificaciones -->
<section class="calificaciones">
  

    <?php if (isset($_SESSION['dni']) && $_SESSION['dni'] != $dni): ?>
        <div class="formulario-calificacion">
            <h3 style="margin-bottom: 15px;">Dejá tu calificación</h3>
            <form method="POST" action="carga/guardar_calificacion.php">
                <input type="hidden" name="dni_trabajador" value="<?php echo $dni; ?>">
                <input type="hidden" name="dni_usuario" value="<?php echo $_SESSION['dni']; ?>">

                <label for="puntuacion">Puntuación (1 a 5):</label>
                <select name="puntuacion" required>
                    <option value="">Seleccioná...</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>

                <label for="comentario">Comentario:</label>
                <textarea name="comentario" rows="3" required></textarea>

                <button type="submit">Enviar calificación</button>
            </form>
        </div>
    <?php endif; ?>
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






function abrirModal(foto, descripcion, idPublicacion) {
  document.getElementById("modalImagen").src = "../../imagenes/publicaciones/" + foto;
  document.getElementById("modalDescripcion").textContent = descripcion;
  document.getElementById("idPublicacionInput").value = idPublicacion;
  document.getElementById("modalPublicacion").style.display = "flex";
  
  fetch("carga/cargar_comentarios.php?id=" + idPublicacion)
    .then(res => res.text())
    .then(html => {
      document.getElementById("listaComentarios").innerHTML = html;
    });
}

function cerrarModal() {
  document.getElementById("modalPublicacion").style.display = "none";
}
// Cerrar modal de publicaciones si clickea fuera del contenido
document.getElementById("modalPublicacion").addEventListener("click", function(e) {
  const modalContent = document.querySelector("#modalPublicacion .modal-content");
  if (!modalContent.contains(e.target)) {
    cerrarModal();
  }
});

// Enviar comentario
document.getElementById("formComentario").addEventListener("submit", function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch("carga/guardar_comentario.php", { method: "POST", body: formData })
    .then(res => res.text())
    .then(resp => { 
      if (resp === "ok") {
        abrirModal(document.getElementById("modalImagen").src.split("/").pop(), document.getElementById("modalDescripcion").textContent, formData.get("id_publicacion"));
        this.reset();
      } else {
        alert("Debe iniciar sesion para poder comentar");
      }
    });
});
</script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    const tema = localStorage.getItem('tema') || 'claro';
    const idioma = localStorage.getItem('idioma') || 'es';

    // Elementos a modificar
    const body = document.body;
    const fondos = document.querySelectorAll('main, .perfil, .imagenes, .calificaciones, .calificaciones2, .formulario-calificacion, .modal-content');
const botones = document.querySelectorAll('button, .btn');
    const titulos = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
    const inputs = document.querySelectorAll('input, textarea, select');

    // Aplicar tema
    if(tema === 'oscuro') {
        body.style.backgroundColor = '#2e2e2e';
        body.style.color = '#f1f1f1';
        fondos.forEach(f => { 
            f.style.color = '#828282ff'; 
        });
        titulos.forEach(t => t.style.color = '#ffd27f');
        
    botones.forEach(b => { b.style.backgroundColor = '#444'; b.style.color = '#fff'; });
        inputs.forEach(i => { 
            i.style.backgroundColor = '#fff'; 
            i.style.color = '#000'; 
            i.style.borderColor = '#777';
        });
        const modal = document.querySelector('.modal-content');
        if(modal) modal.style.backgroundColor = '#3b3b3b';
    } else {
        body.style.backgroundColor = '#fff';
        body.style.color = '#333';
        fondos.forEach(f => { 
            f.style.backgroundColor = '#fff'; 
            f.style.color = '#333'; 
        });
        titulos.forEach(t => t.style.color = '#4e4a57');
        botones.forEach(b => { b.style.backgroundColor = '#5e5b64'; b.style.color = '#fff'; });
        inputs.forEach(i => { 
            i.style.backgroundColor = '#fff'; 
            i.style.color = '#000'; 
            i.style.borderColor = '#ccc';
        });
    }

    // Traducciones
    const textos = document.querySelectorAll('[data-texto]');


    const traducciones = {
        es: {
            contratar: "Contratar",
     iniciaSesion: "Inicia sesión",
        unete:"Únete",
        home: "Home", premium: "Premium", categorias: "Categorías", bandeja: "Bandeja de entrada",
        adminPedidos: "Administrador de pedidos", configuracion: "Configuración", ayuda: "Ayuda",
            calificacionesTitulo: "Calificaciones",
            dejarCalificacion: "Dejá tu calificación",
            puntuacion: "Puntuación (1 a 5):",
            comentario: "Comentario:",
            enviar: "Enviar calificación",
               comentarios: "Comentarios",
        comentarPlaceholder: "Escribe un comentario...",
        comentarBoton: "Comentar",
             celular: "Celular:", localidad: "Localidad:", ocupacion: "Ocupación:",
            sinCalificaciones: "Este trabajador aún no tiene calificaciones."
        },
        en: {
            contratar: "Hire",
               comentarios: "Comments",
        comentarPlaceholder: "Write a comment...",
        comentarBoton: "Comment",
            celular: "Celular:", localidad: "Local:", ocupacion: "Ocupação:",
            celular: "Phone:", localidad: "Location:", ocupacion: "Occupation:",
  iniciaSesion: "Sign in",
        unete: "Join",
        home: "Home", premium: "Premium", categorias: "Categories", bandeja: "Inbox",
        adminPedidos: "Order Manager", configuracion: "Settings", ayuda: "Help", 
            calificacionesTitulo: "Ratings",
            dejarCalificacion: "Leave your rating",
            puntuacion: "Rating (1 to 5):",
            comentario: "Comment:",
            enviar: "Submit rating",
            sinCalificaciones: "This worker has no ratings yet."
        },
        pt: {
            contratar: "Contratar",
            home: "Início", premium: "Premium", categorias: "Categorias", bandeja: "Caixa de entrada", configuracion: "Configurações", ayuda: "Ajuda", 
            calificacionesTitulo: "Avaliações",
            dejarCalificacion: "Deixe sua avaliação",
            puntuacion: "Pontuação (1 a 5):",
            comentario: "Comentário:",
            enviar: "Enviar avaliação",
            sinCalificaciones: "Este trabalhador ainda não possui avaliações."
        }
    };

    textos.forEach(el => {
        const clave = el.getAttribute('data-texto');
        if(traducciones[idioma][clave]) el.textContent = traducciones[idioma][clave];
    });
    
    //menu
const menuTextos = document.querySelectorAll('nav [data-texto]');
menuTextos.forEach(el => {
    const clave = el.getAttribute('data-texto');
    if(traducciones[idioma][clave]) el.textContent = traducciones[idioma][clave];
});
//
 const t = traducciones[idioma];
// Título "Comentarios"
const tituloComentarios = document.querySelector('#comentarios h3');
if (tituloComentarios) tituloComentarios.textContent = t.comentarios;

// Placeholder comentario
const textareaComentario = document.querySelector('#comentarios textarea');
if (textareaComentario) textareaComentario.setAttribute("placeholder", t.comentarPlaceholder);

// Botón comentar
const botonComentar = document.querySelector('#comentarios button');
if (botonComentar) botonComentar.textContent = t.comentarBoton;

    // Traducir botones y etiquetas
    const btnContratar = document.querySelector('button[type="submit"]');
    if(btnContratar) btnContratar.textContent = traducciones[idioma].contratar;

    const h2Calificaciones = document.querySelector('.calificaciones2 h2');
    if(h2Calificaciones) h2Calificaciones.textContent = traducciones[idioma].calificacionesTitulo;

    
    const formulario = document.querySelector('.formulario-calificacion');
    if(formulario){
        const h3 = formulario.querySelector('h3');
        if(h3) h3.textContent = traducciones[idioma].dejarCalificacion;
        const labels = formulario.querySelectorAll('label');
        if(labels[0]) labels[0].textContent = traducciones[idioma].puntuacion;
        if(labels[1]) labels[1].textContent = traducciones[idioma].comentario;
        const btnEnviar = formulario.querySelector('button[type="submit"]');
        if(btnEnviar) btnEnviar.textContent = traducciones[idioma].enviar;
    }

    const sinCalif = document.querySelector('.calificaciones-lista p');
    if(sinCalif && sinCalif.textContent.includes("Este trabajador")) {
        sinCalif.textContent = traducciones[idioma].sinCalificaciones;
    }
});


//foto de perfil
function abrirFotoPerfil(src) {
  document.getElementById("fotoPerfilGrande").src = src;
  document.getElementById("modalFotoPerfil").style.display = "flex";
}

function cerrarFotoPerfil() {
  document.getElementById("modalFotoPerfil").style.display = "none";
}

// opcional: cerrar si clickea fuera de la imagen
document.getElementById("modalFotoPerfil").addEventListener("click", function() {
  cerrarFotoPerfil();
});
 


</script>

</body>
</html>
