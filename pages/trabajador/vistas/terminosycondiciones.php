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

</head>
<body>
  <header>
    <div class="logo">
      <a href="../index-t.php">
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
<!--boton de barra	--------------->
 <ul>
    <font size="5px"><label for="btn-menu">☰</label></font>                    
   
 </ul>
 
  <!--menu	--------------->
<input type="checkbox" id="btn-menu">
<div class="container-menu">
  
	<div class="cont-menu">
          <nav><br>
     
  <a href="../index-t.php" data-texto="home">Home</a>
  <a href="premium.html" data-texto="premium">Premium</a>
  <a href="categorias-t.php" data-texto="categorias">Categorías</a>
  <a href="notificaciones-t.php" data-texto="bandeja">Bandeja de entrada</a>
    <a href="" data-texto="adminPedidos">Administrador de pedidos</a>
  <a href="configuracion-t.php" data-texto="configuracionMenu">Configuración</a>
  <a href="ayuda-t.php" data-texto="ayuda">Ayuda</a>
</nav>
		<label for="btn-menu">✖️</label>
	</div>
</div>


   <main class="search-section">
     <section class="fondo">
    <h2>Términos y Condiciones</h2>
    <p>Al usar esta plataforma, aceptás las siguientes condiciones:</p>
    <div style="text-align: left; max-width: 700px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); color: #333;">
      <p><strong>No publicar contenido ofensivo.</strong><br></p>
      <p><strong>Respetar a otros usuarios.</strong><br></p>
      <p><strong>El sitio puede modificar sus servicios sin previo aviso.</strong><br></p>
    <p><strong>Los datos se tratan según la política de privacidad</strong><br> </p>
    </div>
    </section>
  </main>
  <footer>
    <span>Search Job</span>
    <div class="mainfooter">&copy; 2025 Search Job.</div>
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

window.onload = aplicarTemaGlobal;
</script>
</body>
</html>
