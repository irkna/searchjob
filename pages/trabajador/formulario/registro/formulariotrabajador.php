<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search job </title>
   <link rel="icon" href="../../../../imagenes/logo.png" type="image/x-icon">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
     @media (max-width: 768px) {
  header {
    flex-direction: column;
    align-items: flex-start;
  }
header .logo{
     justify-content: flex-start;
    width: 100%;
    gap: 10px;
    margin-top: 10px;
    text-align: left;
}
  header  {
    
   justify-content: flex-start;
    width: 100%;
    gap: 10px;
    margin-top: 10px;
    text-align: right;
    
  }

  .search-form {
    flex-direction: column;
    padding: 10px;
  }

  .search-form input[type="text"] {
    width: 100%;
    margin-bottom: 10px;
  }

  .search-form button {
    width: 50%;
  }

  .botones-grid {
    flex-direction: column;
   
  }

  footer {
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }

  .social-icons {
    margin-top: 10px;
  }
}
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #eed8c975;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: linear-gradient(200deg, #4e4a57, #5e5b64);/*color de barra*/
      color: white;
      padding:   6px 10px;
    }

    .logo {
      display: flex;
      align-items: center;
      margin-left: 1%;
    }

    .logo a {
      display: flex;
      align-items: center;
      text-decoration: none;
    }

    .logo img {
      width: 100px;
      object-fit: cover;
      margin-right: 5px;
    }

    .overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 999;
    }

    .popup {
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      max-width: 500px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .popup h2 {
      margin-top: 0;
    }

    .popup .btn {
      margin-top: 20px;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
      border: none;
      background-color:#dec2b2;
    }

    .popup .btn:hover {
      background-color: #727077;
    }

     h3{
      color:  #000000ad;
     }
   .layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh; /* ocupa toda la altura visible */
}

main {
  flex: 1;
}

    #mainContent {
      flex:1;
      padding: 20px;
      max-width: 400px;
      margin: auto;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      margin-top: 20px;
      margin-block-end: 30px;
    }
form label {
      display: block;
    
     
    } 
    
  form .estilo{
    position: relative;
    border-bottom: 2px solid #adadad;
    margin: 30px 0;
}

.estilo input{
    width: 100%;
    padding: 0 5px;
    height: 25px;
    font-size: 16px;
    border: none;
    background: none;
    outline: none;
}

.estilo label{
    position: absolute;
    top: 30%;
    left: 5px;
    color: #000000c4;/*color de los datos que pide*/
    transform: translateY(-50%);
    font-size: 16px;
    pointer-events: none;
    transition: .5s;
}


.estilo input:focus ~ label,
.estilo input:valid ~ label{
    top: -5px;
    color: #7f7d7d;
    transition: top 0.3s ease, color 0.3s ease;
}

.estilo input:focus ~ span::before,
.estilo input:focus ~ span::before{
    width: 100%;
}


    form button {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #dec2b2;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    form button:hover {
      background-color: #727077;
    }
          /*footer*/
        footer {
      background-color: #222;
      color: white;
      padding: 20px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .mainfooter {
      font-size: 16px;
    }

    .social-icons a {
      color: white;
      margin: 0 10px;
      font-size: 20px;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .social-icons a:hover {
      color: #1da1f2; /* azul twitter por defecto */
    }

      footer span {
   color: #ffffff;
   
    font-size: 15px;
    text-shadow:2px 2px #68646457;/*color del sombreado del titulo*/
      
    }
      /*--posicion del menu	---------------*/
	#btn-menu{
		display: none;
        cursor: pointer;    
      
	}
 ul {
  text-align: left;
    padding: 5px;
    margin: 0px;

    background: rgba(131, 129, 140, 0.2);
    list-style-type: none;
    backdrop-filter: blur(4px) saturate(180%);
    
}

  
	   
    /*--transicion y tamaño	---------------*/
	.container-menu{
		position:fixed;
	
		width: 100%;

		top: 0;left: 0;
		transition: all 900ms ease;
		
		visibility: hidden;
	}
	#btn-menu:checked ~ .container-menu{
		opacity: 1;
		visibility: visible;
	}
	.cont-menu{
	
		width: 100%;
		max-width: 250px;
        background: rgba(15, 12, 12, 0.777);/*color del menu*/
        
        
		height: 100vh;
		position: relative;
		transition: all 500ms ease;
		transform: translateX(-100%);
	}
	#btn-menu:checked ~ .container-menu .cont-menu{
		transform: translateX(0%);
	}
	.cont-menu nav{
		transform: translateY(5%);
    /*margin-top: 115px;que el menu este abajo del header*/
	}
	.cont-menu nav a{
		display: block;
		text-decoration: none;
		padding: 20px;
    text-align:center;
		color: #c7c7c7;/*color de letras*/
		
		transition: all 400ms ease;
	}
    /*--la efecto subrayado	---------------*/
	.cont-menu nav a:hover{
		border-left: 2px solid #c7c7c7;
		
	}
    /*--la x	---------------*/
	.cont-menu label{
		position: absolute;
		right: 5px;
		top: 10px;
		color: #fff;
		cursor: pointer;
		font-size: 18px;
	}
  </style>
</head>
<body>
  <div class="layout">
  <header>
    <div class="logo">
      <a href="../../../../index.html">
        <img src="../../../../imagenes/logo.png" alt="Search Job Logo">
      </a>
    </div>
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
           <a href="../../../../index.html" >Home</a>
      <a href="../../../../pages/premium.html">Premium</a>
      <a href="../../../../pages/categorias.html" >Categorías</a>
      <a href="../../../../pages/notificaciones.html" >Bandeja de entrada</a>
      <a href="../../../../pages/adminpedidos.html" >Administrador de pedidos</a>
      <a href="../../../../pages/configuracion.html" >Configuracion</a>
      <a href="../../../../pages/ayuda.html">Ayuda</a>
        </nav>
		<label for="btn-menu">✖️</label>
	</div>
</div>
<main>
  <form method="post" autocomplete="off"> <!--ESTO -->
  <!-- Formulario de registro -->
  <div id="mainContent">
    <h3>Formulario de Registro del Trabajador</h3>
    
        <div class="estilo">
           <input type="text" id="cuil" name="cuil" required>
           <label >Cuil:</label>
        </div>
        <div class="estilo">
           <input type="text" id="matricula" name="matricula" required>
           <label >Matricula:</label>
        </div>
        <div class="estilo">
           <input type="text" id="ocupacion" name="ocupacion" required>
           <label >Ocupacion:</label>
        </div>
        <div class="estilo">
           <input type="text" id="identificador" name="identificador" required>
           <label >Identificador (DNI):</label>
        </div>

      <button type="submit" name="send">Registrarse</button> <!-- ESTO-->
    </form>
    
  </div> 
  </form>
</main>
<!--ESTO-->
  <?php 
     include("send2.php");
  ?>

  <script>
   
  </script>
   <footer>
       <span>Search Job</span>
    <div class="mainfooter">
       
    <div class="social-icons">
      <a href="https://www.instagram.com" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      <a href="https://wa.me/1234567890" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
      <a href="https://twitter.com" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
    </div>
  </footer>
  </div>
     

<script>
function aplicarConfiguracionGlobal() {
    // -------------------------------
    // 1️⃣ Tema oscuro / claro
    // -------------------------------
    const tema = localStorage.getItem('tema') || 'claro';
    const body = document.body;

    if (tema === 'oscuro') {
        body.style.backgroundColor = '#2e2e2e';
        body.style.color = '#fff';
        document.getElementById('mainContent').style.backgroundColor = '#212020ff';
        document.getElementById('mainContent').style.color = '#fff';
        document.querySelectorAll('input, button, label').forEach(el => {
            el.style.color = '#fff';
            el.style.backgroundColor = (el.tagName === 'BUTTON') ? '#444' : 'transparent';
        });
    } else {
        body.style.backgroundColor = '#fff';
        body.style.color = '#000';
        document.getElementById('mainContent').style.backgroundColor = '#fff';
        document.getElementById('mainContent').style.color = '#000';
        document.querySelectorAll('input, button, label').forEach(el => {
            el.style.color = '#000';
            el.style.backgroundColor = (el.tagName === 'BUTTON') ? '#dec2b2' : 'transparent';
        });
    }

    // -------------------------------
    // 2️⃣ Idioma
    // -------------------------------
    const idioma = localStorage.getItem('idioma') || 'es';
    const textos = {
        es: {
            formulario: "Formulario de Registro",
            dni: "DNI:",
            nombre: "Nombre:",
            telefono: "Teléfono:",
            localidad: "Localidad:",
            gmail: "Gmail:",
            contrasena: "Contraseña:",
            trabajador: "Voy a ser trabajador",
            boton: "Registrarse",
            home: "Home",
            premium: "Premium",
            categorias: "Categorías",
            bandeja: "Bandeja de entrada",
            admin: "Administrador de pedidos",
            config: "Configuración",
            ayuda: "Ayuda"
        },
        en: {
            formulario: "Registration Form",
            dni: "ID:",
            nombre: "Name:",
            telefono: "Phone:",
            localidad: "Location:",
            gmail: "Email:",
            contrasena: "Password:",
            trabajador: "I will be a worker",
            boton: "Sign Up",
            home: "Home",
            premium: "Premium",
            categorias: "Categories",
            bandeja: "Inbox",
            admin: "Order Manager",
            config: "Settings",
            ayuda: "Help"
        },
        pt: {
            formulario: "Formulário de Registro",
            dni: "ID:",
            nombre: "Nome:",
            telefono: "Telefone:",
            localidad: "Localidade:",
            gmail: "Email:",
            contrasena: "Senha:",
            trabajador: "Vou ser trabalhador",
            boton: "Registrar",
            home: "Início",
            premium: "Premium",
            categorias: "Categorias",
            bandeja: "Caixa de entrada",
            admin: "Gerenciador de pedidos",
            config: "Configurações",
            ayuda: "Ajuda" 
        }
    };
    const t = textos[idioma];

    // ----- Formulario -----
    const h3 = document.querySelector('#mainContent h3');
    if (h3) h3.textContent = t.formulario;

    const labels = document.querySelectorAll('#mainContent label');
    labels.forEach(label => {
        switch(label.innerText.trim()) {
            case 'DNI:': label.textContent = t.dni; break;
            case 'Nombre:': label.textContent = t.nombre; break;
            case 'Teléfono:': label.textContent = t.telefono; break;
            case 'Localidad:': label.textContent = t.localidad; break;
            case 'Gmail:': label.textContent = t.gmail; break;
            case 'Contraseña:': label.textContent = t.contrasena; break;
            case 'Voy a ser trabajador': label.childNodes[1].textContent = " " + t.trabajador; break;
        }
    });

    const btn = document.querySelector('#mainContent button[type="submit"]');
    if (btn) btn.textContent = t.boton;

    // ----- Menú lateral -----
    const itemsMenu = document.querySelectorAll('.cont-menu nav a');
    itemsMenu.forEach(a => {
        const clave = a.getAttribute('data-texto');
        if (t[clave]) a.textContent = t[clave];
    });
}

window.addEventListener("load", aplicarConfiguracionGlobal);
</script>
</body>
</html>