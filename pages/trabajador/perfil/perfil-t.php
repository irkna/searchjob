<?php
include("../../conexion.php");
session_start();

if (!isset($_SESSION['dni'])) {
    header("Location: login-trabajador.html");
    exit();
}

$dni = $_SESSION['dni'];
$sql = "SELECT u.nombre, u.telefono, u.localidad, t.ocupacion, u.foto_perfil , t.linkdepago
        FROM usuarios u 
        INNER JOIN trabajador t ON u.dni = t.identificador 
        WHERE u.dni = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $dni);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

$fotoPerfil = (!empty($usuario['foto_perfil']) && file_exists("../../../imagenes/perfiles/" . $usuario['foto_perfil']))
    ? "../../../imagenes/perfiles/" . $usuario['foto_perfil']
    : "../../../imagenes/perfiles/trabajador-logo.jpg";
$_SESSION['foto_perfil'] = $usuario['foto_perfil']; 



//////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
// Subida de imagen de publicación
if (isset($_POST['publicar'])) {

    // Contar publicaciones actuales del usuario
    $sqlCount = "SELECT COUNT(*) as total FROM publicacion WHERE dni_usuario = ?";
    $stmtCount = $conex->prepare($sqlCount);
    $stmtCount->bind_param("i", $dni);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
    $rowCount = $resultCount->fetch_assoc();

    if ($rowCount['total'] >= 3) {
        // Si tiene 3 o más publicaciones, mostrar mensaje y no subir
        echo "<script>alert('⚠️ Has alcanzado el límite de 3 publicaciones. Para subir más, necesitás comprar Premium o borrar algunas que ya tienes.');</script>";
    } else {
        // Permitir subir publicación
        $descripcion = $_POST['descripcion'];

        if (isset($_FILES['foto_publicacion']) && $_FILES['foto_publicacion']['error'] == 0) {
            $nombreArchivo = 'pub_' . time() . '_' . basename($_FILES['foto_publicacion']['name']);
            $rutaDestino = '../../../imagenes/publicaciones/' . $nombreArchivo;

            if (move_uploaded_file($_FILES['foto_publicacion']['tmp_name'], $rutaDestino)) {
                $sqlPub = "INSERT INTO publicacion (foto_publicacion, descripcion, dni_usuario) VALUES (?, ?, ?)";
                $stmtPub = $conex->prepare($sqlPub);
                $stmtPub->bind_param("ssi", $nombreArchivo, $descripcion, $dni);
                $stmtPub->execute();
            }
        }
    }

    // Redirigir para evitar reenvío al recargar
    echo "<script>window.location.href = window.location.href;</script>";
    exit();
}


// Eliminación de publicación
if (isset($_POST['eliminar_publicacion'])) {
    $archivo = $_POST['eliminar_publicacion'];
    
    // Eliminar de la base de datos
    $sqlDelete = "DELETE FROM publicacion WHERE foto_publicacion = ? AND dni_usuario = ?";
    $stmtDelete = $conex->prepare($sqlDelete);
    $stmtDelete->bind_param("si", $archivo, $dni);
    $stmtDelete->execute();

    // Eliminar la imagen del servidor
    $rutaArchivo = '../../../imagenes/publicaciones/' . $archivo;
    if (file_exists($rutaArchivo)) {
        unlink($rutaArchivo);
    }

    // Refrescar página
    echo "<script>window.location.href = window.location.href;</script>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil del Trabajador</title>
    <link rel="icon" href="../../../imagenes/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../styles/perfil.css">

  <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    .tarjeta {
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  overflow: hidden;
  position: relative;
  margin: 10px;
  width: 250px;
}

.imagen-contenedor {
  position: relative;
  width: 100%;
}
input[type="file"] {
    color: transparent;   
}

.imagen-contenedor img {
  width: 100%;
  height: auto;
  display: block;
  margin: 0;
  padding: 0;
}

.btn-icono-eliminar {
  position: absolute;
  top: 8px;
  right: 8px;
  background: rgba(255, 255, 255, 0.85);
  border: none;
  font-size: 18px;
  color: #dc3545;
  cursor: pointer;
  border-radius: 50%;
  width: 39px;
  height: 39px;
  line-height: 28px;
  text-align: center;
  transition: background 0.3s, color 0.3s;
  z-index: 1;
}

.btn-icono-eliminar:hover {
  background: #fff;
  color: #a71d2a;
}

.form-eliminar {
  margin: 0;
  padding: 0;
  position: absolute;
  top: 0;
  right: 0;
}

.descripcion-publicacion {
  padding: 10px;
  margin: 0;
}

.descripcion-publicacion p {
  margin: 0;
  font-size: 14px;
  color: #333;
}

/**//* */

.calificaciones-box h2 {
  margin-top: 0;
  font-size: 20px;
  margin-bottom: 15px;
  color: #333;
}

.calificaciones-box {
  max-width: 800px;
  margin: 30px auto;
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);

  display: flex;
  flex-direction: column;
}

.calificaciones-scroll {
  max-height: 250px;     /* altura del scroll */
  overflow-y: auto;      /* scroll vertical */
  padding-right: 10px;
  display: flex;
  flex-direction: column;
  gap: 10px;             /* separación entre tarjetas */
}

.calificacion-card {
  background: #f9f9f9;
  border-left: 4px solid #5e5b64;
  padding: 12px 15px;
  border-radius: 8px;
  width: 100%;           /* ocupa todo el ancho */
  box-sizing: border-box;
  word-wrap: break-word;
}


.calificacion-card .estrella {
  color: #f39c12;
  font-size: 16px;
  margin-right: 2px;
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
      right: 20px;
      top: 10px;
      font-size: 28px;
      cursor: pointer;
    }
    #comentarios textarea {
      width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;
    }
    #comentarios button {
      margin-top: 8px; padding: 6px 12px; background: #333; color: white; border: none; border-radius: 6px; cursor: pointer;
    }
.boton-archivo {
  display: inline-block;
  padding: 8px 20px;
  background: #444444bd;
  color: #fff;
  border-radius: 6px;
  cursor: pointer;
  margin-top:-15%;
}
.boton-archivo:hover {
  background: #666;
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
    <nav></nav>
  </header>

  <ul>
    <font size="5px"><label for="btn-menu">☰</label></font>                    
  </ul>

  <input type="checkbox" id="btn-menu">
  <div class="container-menu">
    <div class="cont-menu">
      <nav><br> 
      <a href="../index-t.php" data-text="home">Home</a>
      <a href="../vistas/premium-t.html" data-text="premium">Premium</a>
      <a href="../vistas/categorias-t.php"  data-text="categorias">Categorías</a>
      <a href="../vistas/notificaciones-t.php" data-text="bandeja" >Bandeja de entrada</a>
           <a href="../vistas/adminPedidos-t.php" data-text="pedidos">Administrador de pedidos</a>
<a href="../vistas/chat.php">Chat</a>
      <a href="../vistas/configuracion-t.php"data-text="configuracion" >Configuracion</a>
      <a href="../vistas/ayuda-t.php" data-text="ayuda">Ayuda</a>
        </nav>

      <label for="btn-menu">✖️</label>
    </div>
  </div>
   <main>
   <div class="perfil">
        <div id="mensaje-error" style="color: red; font-weight: bold; margin: 10px;"></div>

    <form method="POST" id="form-editar" enctype="multipart/form-data" style="display:none;"></form>

    <label for="inputFotoPerfil">
      <img id="fotoPerfil" src="<?= $fotoPerfil ?>" alt="Foto de perfil">
    </label>
    <input type="file" id="inputFotoPerfil" accept="image/*" style="display:none;" onchange="cambiarFotoPerfil(event)">



    <div id="nombre"><h2><?= htmlspecialchars($usuario['nombre']) ?></h2></div>

    <div class="info-item" id="celular">
        <i class="fas fa-phone"></i> Celular:  <?= htmlspecialchars($usuario['telefono']) ?>
      </div>

      <div class="info-item" id="localidad">
        <i class="fas fa-map-marker-alt"></i> Localidad: <?= htmlspecialchars($usuario['localidad']) ?>
      </div>
      <div class="info-item" id="ocupacion">
        <i class="fas fa-info"></i> Ocupacion: <?= htmlspecialchars($usuario['ocupacion']) ?>
      </div>
<div class="info-item" id="linkdepago">
  <i class="fas fa-link"></i> 
  Link de pago:
  <?php if (!empty($usuario['linkdepago'])): ?>
    <a href="<?= (strpos($usuario['linkdepago'], 'http') === 0 ? $usuario['linkdepago'] : 'https://' . $usuario['linkdepago']) ?>" 
       target="_blank" 
       rel="noopener noreferrer"
       style="color: var(--accent-color); text-decoration: underline;">
      <?= htmlspecialchars($usuario['linkdepago']) ?>
    </a>
  <?php else: ?>
    <span> Agregue un link de pago </span>
  <?php endif; ?>
</div>


      
    <button onclick="editarPerfil()">Editar perfil</button>
    <button><a href="cerrarsesion.php" style="color:white">Cerrar sesión</a></button>
  </div>
 
      <section class="publicaciones">
        
  <div class="galeria">
<?php
$sqlPublicaciones = "SELECT id_publicacion, foto_publicacion, descripcion FROM publicacion WHERE dni_usuario = ?";

$stmtPub = $conex->prepare($sqlPublicaciones);
$stmtPub->bind_param("i", $dni);
$stmtPub->execute();
$resultadoPub = $stmtPub->get_result();

while ($pub = $resultadoPub->fetch_assoc()) {
    $ruta = '../../../imagenes/publicaciones/' . $pub['foto_publicacion'];
echo '<div class="tarjeta" onclick="abrirModal(' . htmlspecialchars(json_encode($pub['foto_publicacion'])) . ', ' . htmlspecialchars(json_encode($pub['descripcion'])) . ', ' . htmlspecialchars(json_encode($pub['id_publicacion'])) . ')">';

  echo '<div class="imagen-contenedor">';
    echo '<img src="' . htmlspecialchars($ruta) . '" alt="Publicación">';
    echo '<form method="POST" class="form-eliminar" onsubmit="return confirm(\'¿Estás seguro de que querés eliminar esta publicación?\');">';
      echo '<input type="hidden" name="eliminar_publicacion" value="' . htmlspecialchars($pub['foto_publicacion']) . '">';
      echo '<button type="submit" class="btn-icono-eliminar" title="Eliminar publicación">🗑️</button>';
    echo '</form>';
  echo '</div>';
  echo '<div class="descripcion-publicacion">';
    echo '<p>' . htmlspecialchars($pub['descripcion']) . '</p>';
  echo '</div>';
echo '</div>';

}

?>
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






<section class="calificaciones">
  <div class="calificaciones-box">
    <h2>Calificaciones recibidas</h2>
    <div class="calificaciones-scroll">
      <?php
$sqlCalificaciones = "SELECT c.puntuacion, c.comentario, u.nombre, u.dni 
                      FROM califica c
                      JOIN usuarios u ON c.dni_usuario = u.dni
                      WHERE c.dni_trabajador = ?";

$stmtCal = $conex->prepare($sqlCalificaciones);
$stmtCal->bind_param("i", $dni);
$stmtCal->execute();
$resultadoCal = $stmtCal->get_result();

if ($resultadoCal->num_rows > 0) {
    while ($cal = $resultadoCal->fetch_assoc()) {
        echo '<div class="calificacion-card">';
        echo '<strong><a href="../vistas/ver_perfil.php?dni=' . $cal['dni'] . '">' . htmlspecialchars($cal['nombre']) . '</a></strong><br>';

        echo '<div class="estrellas">';
        for ($i = 1; $i <= 5; $i++) {
            echo '<span class="estrella">' . ($i <= $cal['puntuacion'] ? '★' : '☆') . '</span>';
        }
        echo '</div>';
        echo '<p>' . htmlspecialchars($cal['comentario']) . '</p>';
        echo '</div>';
    }
} else {
    echo "<p>Este usuario aún no recibió calificaciones.</p>";
}

      ?>
    </div>
  </div>
</section>


</section>

    


        <section class="publicaciones">
          <div class="perfil">
            <h2><p data-text="tituloSubir">Subir publicaciones</p></h2>

                 <form method="POST" enctype="multipart/form-data">
  <label id="labelArchivo" for="archivo" class="boton-archivo">Elegir archivo</label>
<input type="file" id="archivo" name="foto_publicacion" accept="image/*" style="display:none;" required>
<span id="nombreArchivo" style="margin-left:10px; font-size:14px; color:green; font-weight:bold;"></span> <!-- acá se muestra el nombre -->
<input type="text" name="descripcion" placeholder="Descripción..." required>
<button type="submit" name="publicar">Subir publicación</button>
</form>

</div>
          



        </section>






    <form method="POST" id="form-editar" style="display:none;"></form>
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
// Mostrar nombre del archivo cuando se selecciona
document.getElementById("archivo").addEventListener("change", function() {
  const archivo = this.files[0];
  const nombreArchivo = document.getElementById("nombreArchivo");
  const labelArchivo = document.getElementById("labelArchivo");

  if (archivo) {
    nombreArchivo.textContent = "✅ " + archivo.name; // muestra nombre del archivo
    labelArchivo.textContent = "Archivo seleccionado"; // cambia el texto del botón
    labelArchivo.style.background = "#28a745"; // cambia color a verde
  } else {
    nombreArchivo.textContent = "";
    labelArchivo.textContent = "Elegir archivo";
    labelArchivo.style.background = "#444444bd";
  }
});




    let editando = false;

    function cambiarFotoPerfil(event) {
      const imagen = event.target.files[0];
      if (imagen) {
        const reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById("fotoPerfil").src = e.target.result;
        };
        reader.readAsDataURL(imagen);
      }
    }

    function convertirATextoEditable(id, tag, label = "") {
      const div = document.getElementById(id);
      const valor = div.innerText.replace(label, "").trim()
     
  .replace("Link de pago:", "")
  .replace("", "") // ícono de fontawesome convertido en texto raro
  .trim();

      div.innerHTML = `<input type="text" name="${id}" value="${valor}" required>`;
    }

   function editarPerfil() {
  const mensajeError = document.getElementById("mensaje-error");

  if (!editando) {
    convertirATextoEditable('nombre', 'h2');
    convertirATextoEditable('celular', 'div', 'Celular: ');
    convertirATextoEditable('localidad', 'div', 'Localidad: ');
    convertirATextoEditable('ocupacion', 'div', 'Ocupacion: ');
        convertirATextoEditable('linkdepago', 'div', 'Link de pago: ');


    
    document.querySelector('.perfil button').textContent = "Guardar cambios";
    editando = true;
  } else {
    // Obtener valores
    const nombre = document.querySelector('#nombre input').value.trim();
    const telefono = document.querySelector('#celular input').value.trim();
    const localidad = document.querySelector('#localidad input').value.trim();
    const ocupacion = document.querySelector('#ocupacion input').value.trim();
        const linkdepago = document.querySelector('#linkdepago input').value.trim();



    // Validar campos
    if (!nombre || !telefono || !localidad  ) {
      mensajeError.textContent = "⚠️ Por favor, completá todos los campos antes de guardar.";
      return;
    }

    // Limpiar mensaje
    mensajeError.textContent = "";

    // Rellenar y enviar el formulario oculto
    const form = document.getElementById('form-editar');
    form.innerHTML = `
      <input type="hidden" name="nombre" value="${nombre}">
      <input type="hidden" name="telefono" value="${telefono}">
      <input type="hidden" name="localidad" value="${localidad}">
      <input type="hidden" name="ocupacion" value="${ocupacion}">
            <input type="hidden" name="linkdepago" value="${linkdepago}">


       
    `;

    const inputFoto = document.getElementById('inputFotoPerfil');
    if (inputFoto && inputFoto.files.length > 0) {
      inputFoto.name = "nueva_foto";
      form.appendChild(inputFoto); // mover al form
    }

    form.submit();
  }
}
/**//*para cargar comentarios */

function abrirModal(foto, descripcion, idPublicacion) {
  document.getElementById("modalImagen").src = "../../../imagenes/publicaciones/" + foto;
  document.getElementById("modalDescripcion").textContent = descripcion;
  document.getElementById("idPublicacionInput").value = idPublicacion;
  document.getElementById("modalPublicacion").style.display = "flex";
  
  fetch("cargar_comentarios.php?id=" + idPublicacion)
    .then(res => res.text())
    .then(html => {
      document.getElementById("listaComentarios").innerHTML = html;
    });
}

function cerrarModal() {
  document.getElementById("modalPublicacion").style.display = "none";
}// Cerrar modal de publicaciones si clickea fuera del contenido
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
  fetch("guardar_comentario.php", { method: "POST", body: formData })
    .then(res => res.text())
    .then(resp => {
      if (resp === "ok") {
        abrirModal(document.getElementById("modalImagen").src.split("/").pop(), document.getElementById("modalDescripcion").textContent, formData.get("id_publicacion"));
        this.reset();
      } else {
        alert("Error al comentar");
      }
    });
});

/*tema oscuro/*/

function aplicarTemaGlobal() {
  const tema = localStorage.getItem('tema') || 'claro';
  const body = document.body;

  const fondos = document.querySelectorAll(
    '.perfil-header, .perfil-info, .publicaciones, .publicacion, .formulario-publicacion, .datos-usuario'
  );
  const botones = document.querySelectorAll('.btn, .boton-editar, .boton-guardar, .boton-cancelar, .boton-volver');
  const titulos = document.querySelectorAll('h1, h2, h3');
  const textosSecundarios = document.querySelectorAll('.texto-secundario, .info-extra, .mensaje-sin-calificaciones');

  if (tema === 'oscuro') {
    body.style.backgroundColor = '#000'; // Fondo negro
    body.style.color = '#ada2a2ff';

    fondos.forEach(elem => {
      elem.style.backgroundColor = '#1a1a1a';
      elem.style.color = '#715656ff';
    });

    botones.forEach(btn => {
      btn.style.backgroundColor = '#444';
      btn.style.color = '#fff';
    });

    titulos.forEach(t => t.style.color = '#ffd27f');

    textosSecundarios.forEach(txt => {
      txt.style.color = '#555'; // gris oscuro en ambos temas
    });

  } else {
    body.style.backgroundColor = '#fff';
    body.style.color = '#333';

    fondos.forEach(elem => {
      elem.style.color = '#333';
    });

    botones.forEach(btn => {
      btn.style.backgroundColor = '#eed8c9';
      btn.style.color = '#333';
    });

    titulos.forEach(t => t.style.color = '#4e4a57');

    textosSecundarios.forEach(txt => {
      txt.style.color = '#555'; // mismo gris oscuro
    });
  }
}

window.onload = aplicarTemaGlobal;

function aplicarIdioma() {
    const idioma = localStorage.getItem('idioma') || 'es';

  const textos = {
    es: {
        editarPerfil: "Editar perfil",
        cerrarSesion: "Cerrar sesión",
        subirPublicacion: "Subir publicación",
        tituloSubir: "Subir publicaciones",
        calificaciones: "Calificaciones recibidas",
        enLinea: "En línea",
        comentarios: "Comentarios",
        comentarPlaceholder: "Escribe un comentario...",
        comentarBoton: "Comentar",
        celular: "Celular",
        localidad: "Localidad",
        descripcion: "Descripción...",
        elegirArchivo: "Elegir archivo",
        sinCalificaciones: "Este usuario aún no recibió calificaciones.",
        home: "Home",
        premium: "Premium",
        categorias: "Categorías",
        bandeja: "Bandeja de entrada",
        configuracion: "Configuración",
        ayuda: "Ayuda"
    },
    en: {
        editarPerfil: "Edit profile",
        cerrarSesion: "Logout",
        subirPublicacion: "Upload post",
        tituloSubir: "Upload posts",
        calificaciones: "Received Ratings",
        enLinea: "Online",
        comentarios: "Comments",
        comentarPlaceholder: "Write a comment...",
        comentarBoton: "Comment",
        celular: "Phone",
        localidad: "Location",
        descripcion: "Description...",
        elegirArchivo: "Choose file",
        sinCalificaciones: "This user hasn't received ratings yet.",
        home: "Home",
        premium: "Premium",
        categorias: "Categories",
        bandeja: "Inbox",
        configuracion: "Settings",
        ayuda: "Help"
    }
};


    const t = textos[idioma];
// Traducción del menú
document.querySelectorAll('nav a[data-text]').forEach(a => {
    const clave = a.getAttribute('data-text');
    if (t[clave]) a.textContent = t[clave];
});

    // Botón editar perfil
    const btnEditar = document.querySelector('.perfil > button:first-of-type');
    if (btnEditar) btnEditar.textContent = t.editarPerfil;

    // Botón cerrar sesión
    const btnCerrar = document.querySelector('.perfil > button a');
    if (btnCerrar) btnCerrar.textContent = t.cerrarSesion;
//boton elegir archivo
const labelArchivo = document.getElementById("labelArchivo");
if (labelArchivo) labelArchivo.textContent = t.elegirArchivo;


    // Botón subir publicación
    const btnSubir = document.querySelector('form button[name="publicar"]');
    if (btnSubir) btnSubir.textContent = t.subirPublicacion;

// Título "Comentarios"
const tituloComentarios = document.querySelector('#comentarios h3');
if (tituloComentarios) tituloComentarios.textContent = t.comentarios;

// Placeholder comentario
const textareaComentario = document.querySelector('#comentarios textarea');
if (textareaComentario) textareaComentario.setAttribute("placeholder", t.comentarPlaceholder);

// Botón comentar
const botonComentar = document.querySelector('#comentarios button');
if (botonComentar) botonComentar.textContent = t.comentarBoton;

    // Título amarillo "Subir publicaciones"
    const tituloSubir = document.querySelector('.perfil p');
    if (tituloSubir) tituloSubir.textContent = t.tituloSubir;

    // Título calificaciones
    const h2Calificaciones = document.querySelector('.calificaciones-box h2');
    if (h2Calificaciones) h2Calificaciones.textContent = t.calificaciones;

    // Texto "sin calificaciones"
    const sinCalif = document.querySelector('.calificaciones-scroll p');
    if (sinCalif && sinCalif.textContent.includes("Este usuario")) {
        sinCalif.textContent = t.sinCalificaciones;
    }

    // Info en línea, celular y localidad
    const enLineaElem = document.querySelector('#estado-linea');
    if (enLineaElem) enLineaElem.innerHTML = `<i class="fas fa-circle" style="color: green;"></i> ${t.enLinea}`;

    const celularElem = document.querySelector('#celular');
    if (celularElem) celularElem.innerHTML = `<i class="fas fa-phone"></i> ${t.celular}: ${celularElem.textContent.split(':')[1].trim()}`;

    const localidadElem = document.querySelector('#localidad');
    if (localidadElem) localidadElem.innerHTML = `<i class="fas fa-map-marker-alt"></i> ${t.localidad}: ${localidadElem.textContent.split(':')[1].trim()}`;

 
    // Placeholder descripción publicación
    const formDescripcion = document.querySelector('form input[name="descripcion"]');
    if (formDescripcion) formDescripcion.setAttribute("placeholder", t.descripcion);

   
    // Botón elegir archivo (input file)
    const inputFile = document.querySelector('form input[type="file"]');
    if (inputFile) inputFile.setAttribute("title", t.elegirArchivo);
}

// Ejecutar al cargar la página
window.addEventListener("load", aplicarIdioma);



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

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre"], $_POST["telefono"], $_POST["localidad"], $_POST["ocupacion"],$_POST["linkdepago"])) {
  $nombre = $_POST["nombre"];
  $telefono = $_POST["telefono"];
  $localidad = $_POST["localidad"];
  $ocupacion = $_POST["ocupacion"];
  $linkdepago=$_POST["linkdepago"];
  

  // Valida que los campos no estén vacíos
  if (!empty($nombre) && !empty($telefono) && !empty($localidad)) {
    $sql1 = "UPDATE usuarios SET nombre = ?, telefono = ?, localidad = ?  WHERE dni = ?";
    $stmt1 = $conex->prepare($sql1);
    $stmt1->bind_param("sssi", $nombre, $telefono, $localidad, $dni);
    $stmt1->execute();

  // Actualizar ocupación en trabajador
    $sql2 = "UPDATE trabajador SET ocupacion = ? , linkdepago = ? WHERE identificador = ?";
    $stmt2 = $conex->prepare($sql2);
    $stmt2->bind_param("ssi", $ocupacion,$linkdepago, $dni);
    $stmt2->execute();

  }

 


  // Imagen 
  if (isset($_FILES['nueva_foto']) && $_FILES['nueva_foto']['error'] == 0) {
    $nombreFoto = 'foto_' . $dni . '.' . pathinfo($_FILES['nueva_foto']['name'], PATHINFO_EXTENSION);
    $rutaDestino = '../../../imagenes/perfiles/' . $nombreFoto;
    if (move_uploaded_file($_FILES['nueva_foto']['tmp_name'], $rutaDestino)) {
      $sqlFoto = "UPDATE usuarios SET foto_perfil = ? WHERE dni = ?";
      $stmtFoto = $conex->prepare($sqlFoto);
      $stmtFoto->bind_param("si", $nombreFoto, $dni);
      $stmtFoto->execute();
    }
  }

  echo "<script>window.location.href = window.location.href;</script>";
}

?>
</body>
</html>
