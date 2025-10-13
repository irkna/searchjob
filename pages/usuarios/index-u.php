<?php
include("../conexion.php");
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Search Job - Encuentra Profesionales</title>
 <link rel="stylesheet" href="../../styles/style.css">
  <link rel="icon" href="../../imagenes/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style> 
    .perfiles { width: 100%; box-sizing: border-box; margin-top: -70px; margin-bottom:40px; }
    .grid-perfiles { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; padding: 0; margin: 0 auto; max-width: 1200px; box-sizing: border-box; }
    .card-perfil { background: white; border: none; border-radius: 16px; padding: 25px 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); transition: transform 0.3s ease, box-shadow 0.3s ease; text-align: center; }
    .card-perfil:hover { transform: translateY(-6px); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12); }
    .foto-perfil { width: 180px; height: 140px; object-fit: cover; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .card-perfil h3 { font-size: 20px; color: #4e4a57; margin: 10px 0; }
    .card-perfil p { font-size: 14px; color: #666; margin: 6px 0; }
    .card-perfil i { margin-right: 5px; color: #4e4a57; }
.search-form {
  display: flex;
  justify-content: center;   /* centra horizontalmente */
  align-items: center;       /* centra verticalmente */
  gap: 10px;                 /* espacio entre input, botón y select */
  margin: 20px auto;
  flex-wrap: wrap;           /* para que se acomode en móviles */
  max-width: 600px;          /* ancho máximo */
}

.search-form input {
  flex: 1;                   /* ocupa todo el espacio posible */
  min-width: 200px;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.search-form button {
  padding: 8px 12px;
  border: none;
  border-radius: 6px;
  background: #565555ff;
  cursor: pointer;
}

.search-form select {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
}
/**//*publciaciones */
.publicaciones {
  width: 100%;
  margin: 60px auto;
  max-width: 1200px;
}

.grid-publicaciones {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 25px;
}

.card-publicacion {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-publicacion:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.pub-header {
  display: flex;
  align-items: center;
  padding: 15px;
  border-bottom: 1px solid #eee;
}

.pub-foto-perfil {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 12px;
}

.pub-nombre {
  font-size: 16px;
  font-weight: bold;
  color: #333;
}

.pub-body {
  padding: 15px;
}

.pub-foto {
  width: 100%;
  height: 220px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 12px;
}

.pub-descripcion {
  font-size: 14px;
  color: #555;
}
.grid-publicaciones a {
  display: block;
}

.grid-publicaciones a:hover .card-publicacion {
  transform: translateY(-5px);
  box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

/* ==== RESPONSIVE GRID Y BOTONES ==== */

/* Grid de publicaciones y perfiles: dos columnas en pantallas chicas */
@media (max-width: 1024px) {
  .grid-publicaciones,
  .grid-perfiles {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    padding: 0 15px;
  }
}

/* En pantallas muy chicas (celulares), mantiene dos columnas */
@media (max-width: 600px) {
  .botones-grid {
    flex-wrap: nowrap;       /* no bajan de línea */
    overflow-x: auto;        /* scroll horizontal si no caben */
    -webkit-overflow-scrolling: touch; /* scroll suave en iOS */
    gap: 8px;
  }

  .filtro-btn {
    flex: 0 0 auto;          /* que no se achiquen */
    padding: 6px 12px;
    font-size: 0.85rem;
  }

/* Barra de búsqueda en celular */
  .search-form {
    flex-direction: column;  /* los grupos se apilen */
    align-items: center;
    gap: 10px;
    max-width: 90%;
    margin: 20px auto;
  }

  /* Contenedor del input + lupa */
  .search-form .input-lupa {
    display: flex;
    width: 100%;
    gap: 5px;               /* espacio entre input y botón */
  }

  .search-form input {
    flex: 1;                 /* ocupa el espacio disponible */
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
  }

  .search-form button {
    padding: 8px 12px;
    border-radius: 6px;
    background: #565555ff;
    color: #fff;
    border: none;
    cursor: pointer;
  }

  .search-form select {
    width: 100%;             /* select ocupa toda la fila */
    margin-top: 8px;
  }


}

/* Si la pantalla es ultra pequeña, pasa a una columna */
@media (max-width: 600px) {
  .grid-publicaciones,
  .grid-perfiles {
    grid-template-columns: 1fr;
  }
  .container-menu .cont-menu nav a { font-size: 1.0rem !important; padding: 16px 7px !important; }
/* Ocultar botones en pantallas chicas (celular) */
@media (max-width: 600px) {
  .botones-grid {
    display: none;
  }
}


/* Ajuste de texto general para que se vea más grande */
body, p, h1, h2, h3, a, button, select, input {
  font-size: 1.05rem;
}
h2 { font-size: 1.8rem; }
h3 { font-size: 1.4rem; }
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

  </style>
</head>
<body>
  <header><div class="logo">
      <a href="index-u.php">
        <img src="../../imagenes/logo.png" alt="Search Job Logo">
      </a>
    </div>
    <nav>
      <?php if (isset($_SESSION['dni'])): ?>
        <a href="perfil/perfil.php" title="Mi perfil" style="color:white; margin-right: 10px;">
          <?php echo htmlspecialchars($_SESSION['nombre']); ?>
          <img 
            src="<?php echo !empty($_SESSION['foto_perfil']) 
                      ? '../../imagenes/perfiles/' . $_SESSION['foto_perfil'] 
                      : '../../imagenes/cliente-logo.jpg'; ?>" 
            alt="Perfil" 
            style="width:40px; height:40px; border-radius:50%; vertical-align:middle;" 
          />
        </a>
      <?php endif; ?>
    </nav>
  </header> 
<!--boton de barra	--------------->
  <ul><font size="5px"><label for="btn-menu">☰</label></font></ul>
  <input type="checkbox" id="btn-menu">
  <div class="container-menu">
    <div class="cont-menu">
      <nav><br>
   <nav><br> 
      <a href="index-u.php" data-texto="home">Home</a>
      <a href="vistas/categorias.php"  data-texto="categorias">Categorías</a>
      <a href="vistas/notificaciones.php" data-texto="bandeja" >Bandeja de entrada</a>
           <a href="vistas/adminPedidos.php" data-texto="adminPedidos">Pedidos</a>
<a href="vistas/chat.php">Chat</a>
      <a href="vistas/configuracion.php"data-texto="configuracion" >Configuracion</a>
      <a href="vistas/ayuda.php" data-texto="ayuda">Ayuda</a>
        </nav>

      <label for="btn-menu">✖️</label>
    </div>
  </div>
 
  <main class="search-section">
    <section class="fondo">
 <div>
  <img src="../../imagenes/mingo.png" alt="mingo" class="mingo">
</div>

    <h2 data-texto="tituloPrincipal">Encuentra un profesional <br><b><span class="Arial Black">ADECUADO</span></b></h2>
      <br>
      <!-- Barra de búsqueda y filtros -->
     <form method="GET" action="categorias.php" class="search-form">
  <div class="input-lupa">
    <input type="text" name="query" placeholder="Buscar nombre o ocupacion " 
           value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
    <button type="submit"><i class="fas fa-search"></i></button>
  </div>
  <select name="ordenar" onchange="this.form.submit()">
    <option value="">Ordenar por...</option>
    <option value="nombre" <?php if(isset($_GET['ordenar']) && $_GET['ordenar']=='nombre') echo 'selected'; ?>>Nombre</option>
    <option value="ocupacion" <?php if(isset($_GET['ordenar']) && $_GET['ordenar']=='ocupacion') echo 'selected'; ?>>Ocupación</option>
    <option value="localidad" <?php if(isset($_GET['ordenar']) && $_GET['ordenar']=='localidad') echo 'selected'; ?>>Localidad</option>
  </select>
</form>

      <!-- Categorías -->
      <div class="botones-grid" style="margin-top: 30px;">
        <a href="vistas/categorias.php?query=Gasista" class="filtro-btn" data-texto="gasista"><i class="fas fa-fire-extinguisher"></i> Gasista</a>
        <a href="vistas/categorias.php?query=Electricista" class="filtro-btn" data-texto="electricista"><i class="fas fa-bolt"></i> Electricista</a>
        <a href="vistas/categorias.php?query=Plomero" class="filtro-btn" data-texto="plomero"><i class="fas fa-wrench"></i> Plomero</a>
        <a href="vistas/categorias.php?query=Pintor" class="filtro-btn" data-texto="pintor"><i class="fas fa-paint-roller"></i> Pintor</a>
        <a href="vistas/categorias.php?query=Cerrajero" class="filtro-btn" data-texto="cerrajero"><i class="fas fa-key"></i> Cerrajero</a>
        <a href="vistas/categorias.php?query=Carpintero" class="filtro-btn" data-texto="carpintero"><i class="fas fa-hammer"></i> Carpintero</a>
        <a href="vistas/categorias.php?query=Mecánico" class="filtro-btn" data-texto="mecanico"><i class="fas fa-car"></i> Mecánico</a>
        <a href="vistas/categorias.php?query=Jardinero" class="filtro-btn" data-texto="jardinero"><i class="fas fa-leaf"></i> Jardinero</a>
      </div>
    </section>

    <!-- Sección de Perfiles -->
    <?php
      $where = "";
      $orden = "";

      // Si hay búsqueda
      if (!empty($_GET['query'])) {
        $busqueda = mysqli_real_escape_string($conex, $_GET['query']);
        $where = "WHERE usuarios.nombre LIKE '%$busqueda%' 
                  OR trabajador.ocupacion LIKE '%$busqueda%' 
                  OR usuarios.localidad LIKE '%$busqueda%'";
      }

      // Si hay orden
      if (!empty($_GET['ordenar'])) {
        $campo = mysqli_real_escape_string($conex, $_GET['ordenar']);
        $permitidos = ['nombre','ocupacion','localidad'];
        if (in_array($campo, $permitidos)) {
          $orden = "ORDER BY $campo ASC";
        }
      }

      $sql = "SELECT usuarios.dni, usuarios.nombre, usuarios.telefono, usuarios.localidad, usuarios.foto_perfil, trabajador.ocupacion
              FROM trabajador
              INNER JOIN usuarios ON trabajador.identificador = usuarios.dni
              $where
              $orden";
      $resultado = mysqli_query($conex, $sql);

      if (mysqli_num_rows($resultado) > 0) {
        echo "<section class='perfiles'>";
        echo "<div class='grid-perfiles'>";
        while ($fila = mysqli_fetch_assoc($resultado)) {
          $foto = !empty($fila['foto_perfil']) ? "../../imagenes/perfiles/" . $fila['foto_perfil'] : "../../imagenes/perfiles/cliente-logo.jpg";
          $dni = htmlspecialchars($fila['dni']); 
          echo "<a href='vistas/ver_perfil.php?dni=$dni' style='text-decoration: none; color: inherit;'>";
          echo "<div class='card-perfil'>";
          echo "<img src='$foto' alt='Foto de perfil' class='foto-perfil'>";
          echo "<h3>" . htmlspecialchars($fila['nombre']) . "</h3>";
          echo "<p><i class='fas fa-info'></i><strong data-texto='ocupacion'> Ocupación:</strong> " . htmlspecialchars($fila['ocupacion']) . "</p>";
          echo "<p><i class='fas fa-phone'></i><strong data-texto='celular'> Celular:</strong> " . htmlspecialchars($fila['telefono']) . "</p>";
          echo "<p><i class='fas fa-map-marker-alt'></i><strong data-texto='localidad'> Localidad:</strong> " . htmlspecialchars($fila['localidad']) . "</p>";
          echo "</div>";
          echo "</a>";
        }
        echo "</div>";
        echo "</section>";
      } else {
        echo "<p style='text-align: center;' data-texto='noTrabajadores'>No hay trabajadores registrados.</p>";
      }
    ?>
      <!-- Sección de Publicaciones -->
    <?php
      $sql_pub = "SELECT publicacion.id_publicacion, publicacion.foto_publicacion, publicacion.descripcion, 
                         usuarios.dni, usuarios.nombre, usuarios.foto_perfil
                  FROM publicacion
                  INNER JOIN usuarios ON publicacion.dni_usuario = usuarios.dni
                  ORDER BY RAND()   -- Mezcla aleatoriamente
                  LIMIT 6";         // Solo 6 publicaciones por vez
      $res_pub = mysqli_query($conex, $sql_pub);

      if (mysqli_num_rows($res_pub) > 0) {
        echo "<section class='publicaciones'>";
        echo "<h2 style='text-align:center; margin-bottom:30px;'>Publicaciones destacadas</h2>";
        echo "<div class='grid-publicaciones'>";

      while ($pub = mysqli_fetch_assoc($res_pub)) {
  $fotoPerfil = !empty($pub['foto_perfil']) ? "../../imagenes/perfiles/" . $pub['foto_perfil'] : "../../../imagenes/perfiles/cliente-logo.jpg";
  $fotoPubli  = !empty($pub['foto_publicacion']) ? "../../imagenes/publicaciones/" . $pub['foto_publicacion'] : "../../imagenes/no-image.png";
  $dniUser    = htmlspecialchars($pub['dni']);

  echo "<a href='vistas/ver_perfil.php?dni=$dniUser' style='text-decoration:none; color:inherit;'>";
    echo "<div class='card-publicacion'>";
      echo "<div class='pub-header'>";
        echo "<img src='$fotoPerfil' alt='Foto perfil' class='pub-foto-perfil'>";
        echo "<span class='pub-nombre'>" . htmlspecialchars($pub['nombre']) . "</span>";
      echo "</div>";
      echo "<div class='pub-body'>";
        echo "<img src='$fotoPubli' alt='Foto publicación' class='pub-foto'>";
        echo "<p class='pub-descripcion'>" . htmlspecialchars($pub['descripcion']) . "</p>";
      echo "</div>";
    echo "</div>";
  echo "</a>";
}


        echo "</div>";
        echo "</section>";
      } else {
        echo "<p style='text-align: center;'>No hay publicaciones todavía.</p>";
      }
    ?>
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
    window.addEventListener('DOMContentLoaded', () => {
      const tema = localStorage.getItem('tema') || 'claro';
      const idioma = localStorage.getItem('idioma') || 'es';

      // Tema
      const body = document.body;
      const bloques = document.querySelectorAll('.search-section, .fondo, .container, .config-box, .card-perfil, .perfiles');
      const botones = document.querySelectorAll('.btn, .boton-premium, .filtro-btn');
      const titulos = document.querySelectorAll('h1, h2, h3');
      const inputs = document.querySelectorAll('input, select, textarea');

    if (tema === 'oscuro') {
    body.style.backgroundColor = '#2e2e2e';
    body.style.color = '#f1f1f1';
    
    // Excluir card-perfil del fondo oscuro
    bloques.forEach(el => {
        if (!el.classList.contains('card-perfil')) {
            el.style.backgroundColor = '#3b3b3b';
            el.style.color = '#f1f1f1';
        }
    });

    titulos.forEach(t => t.style.color = '#ffd27f');
    botones.forEach(b => { b.style.backgroundColor = '#444'; b.style.color = '#fff'; });
    inputs.forEach(i => { i.style.backgroundColor = '#ffffffff'; i.style.color = '#000000ff'; i.style.borderColor = '#777'; });

} else {
    body.style.backgroundColor = '#fff';
    body.style.color = '#333';
    bloques.forEach(el => { 
        el.style.backgroundColor = '#fff'; 
        el.style.color = '#333'; 
    });
    titulos.forEach(t => t.style.color = '#4e4a57');
    botones.forEach(b => { b.style.backgroundColor = '#eed8c9'; b.style.color = '#333'; });
    inputs.forEach(i => { i.style.backgroundColor = '#fff'; i.style.color = '#333'; i.style.borderColor = '#ccc'; });
}

      // Idioma
      const textos = document.querySelectorAll('[data-texto]');
    const traducciones = {
        es: {
            tituloPagina: "Search Job - Encuentra Profesionales",
            home: "Home",
            premium: "Premium",
            categorias: "Categorías",
            bandeja: "Bandeja de entrada",
            pedidos: "Administrador de pedidos",
            configuracion: "Configuración",
            ayuda: "Ayuda",
            tituloPrincipal: "Encuentra un profesional ADECUADO",
            iniciaSesion: "Inicia sesión",
            unete: "Únete",
            gasista: "Gasista",
            electricista: "Electricista",
            plomero: "Plomero",
            pintor: "Pintor",
            cerrajero: "Cerrajero",
            carpintero: "Carpintero",
            mecanico: "Mecánico",
            jardinero: "Jardinero",
            ocupacion: "Ocupación:",
            celular: "Celular:",
            localidad: "Localidad:",
            noTrabajadores: "No hay trabajadores registrados.",
            busquedaPlaceholder: "¿Qué estás buscando? P. ej. Gasista"
        },
        en: {
            tituloPagina: "Search Job - Find Professionals",
            home: "Home",
            premium: "Premium",
            categorias: "Categories",
            bandeja: "Inbox",
            pedidos: "Orders Admin",
            configuracion: "Settings",
            ayuda: "Help",
            tituloPrincipal: "Find the RIGHT Professional",
            iniciaSesion: "Sign In",
            unete: "Join",
            gasista: "Gas Technician",
            electricista: "Electrician",
            plomero: "Plumber",
            pintor: "Painter",
            cerrajero: "Locksmith",
            carpintero: "Carpenter",
            mecanico: "Mechanic",
            jardinero: "Gardener",
            ocupacion: "Occupation:",
            celular: "Phone:",
            localidad: "Location:",
            noTrabajadores: "No workers registered.",
            busquedaPlaceholder: "What are you looking for? e.g. Gas Technician"
        },
        pt: {
            tituloPagina: "Search Job - Encontre Profissionais",
            home: "Início",
            premium: "Premium",
            categorias: "Categorias",
            bandeja: "Caixa de entrada",
            pedidos: "Administrador de pedidos",
            configuracion: "Configurações",
            ayuda: "Ajuda",
            tituloPrincipal: "Encontre o Profissional ADEQUADO",
            iniciaSesion: "Entrar",
            unete: "Junte-se",
            gasista: "Gás Técnico",
            electricista: "Eletricista",
            plomero: "Encanador",
            pintor: "Pintor",
            cerrajero: "Chaveiro",
            carpintero: "Carpinteiro",
            mecanico: "Mecânico",
            jardinero: "Jardineiro",
            ocupacion: "Ocupação:",
            celular: "Celular:",
            localidad: "Local:",
            noTrabajadores: "Nenhum trabalhador registrado.",
            busquedaPlaceholder: "O que você está procurando? Ex: Técnico de Gás"
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
