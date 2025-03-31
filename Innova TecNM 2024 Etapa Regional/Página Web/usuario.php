<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['Email_Session'])) {
    header("Location: SignIn.php");
    exit();
}

// Verificar si el nombre del usuario está almacenado en la sesión
// Si no, puedes establecer un nombre predeterminado o vaciar el mensaje
$username = isset($_SESSION['Username_Session']) ? htmlspecialchars($_SESSION['Username_Session']) : 'Usuario';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario - Amoxtli-Jap</title>
    <!-- Incluir CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
/* Background */
body {
    font-family: 'Open Sans', sans-serif;
    background-image: url('img/Amoxtli_fondo_difuminado_2.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed;
}

/* Navigation */
#menu {
    padding: 0.5px 0;
    background-color: rgb(255, 59, 190);
    border-color: rgba(231, 231, 231, 0);
}

#menu .navbar-header {
    display: flex;
    align-items: center;
}

#menu .navbar-brand {
    display: flex;
    align-items: center;
    font-size: 22px;
    color: #ffffff;
    font-weight: 700;
    text-decoration: none;
}

#menu .navbar-brand img {
    width: 65px;
    height: auto;
    margin-right: 5px;
}

#menu .welcome-message {
    font-size: 22px;
    color: #ffffff;
    margin-left: 10px;
}

#menu .navbar-nav > li > a {
    text-transform: uppercase;
    color: #ffffff;
    font-size: 13px;
    letter-spacing: 1px;
}

#menu .navbar-nav > li > a:hover {
    color: #2541e0;
}

.navbar-toggler {
    border-radius: 0;
}

.navbar-toggler:hover, .navbar-toggler:focus {
    background-color: #2541e0;
    border-color: #bd46b3;
}

.navbar-toggler:hover > .icon-bar {
    background-color: #FFF;
}
/* Media Queries */
@media (max-width: 767px) {
    body {
        background-color: #ffffff; /* Fondo blanco en dispositivos móviles */
        background-image: none; /* Eliminar imagen de fondo en dispositivos móviles */
    }

    main {
        background-color: #ffffff; /* Fondo blanco en dispositivos móviles */
        margin: 0; /* Eliminar margen en dispositivos móviles */
        padding: 15px; /* Reducir el relleno en dispositivos móviles */
        min-height: 100vh; /* Asegura que main cubra toda la altura de la ventana */
        display: flex; /* Utiliza flexbox */
        flex-direction: column; /* Dirección de los elementos flexibles */
    }

    .container {
        width: 100%;
        padding: 0 15px; /* Añadir algo de padding horizontal */
    }

    .table {
        font-size: 12px; /* Ajustar el tamaño de la fuente en pantallas pequeñas */
        background-color: #ffffff; /* Fondo blanco en la tabla para pantallas pequeñas */
        border-radius: 0; /* Eliminar bordes redondeados en la tabla para pantallas pequeñas */
        margin: 0 auto; /* Centrar la tabla horizontalmente */
        padding: 0; /* Eliminar relleno en la tabla para pantallas pequeñas */
        width: 100%; /* Asegura que la tabla ocupe todo el ancho disponible en pantallas pequeñas */
        border-collapse: collapse; /* Eliminar espacios entre celdas */
    }

    /* Ajustar el tamaño de la fuente en el encabezado y celdas de la tabla */
    .table thead th, .table tbody td {
        font-size: 8px; /* Tamaño de fuente más pequeño en pantallas pequeñas */
    }

    /* Ajustar el tamaño del texto de los enlaces en la navegación */
    #menu .navbar-nav > li > a {
        font-size: 12px; /* Tamaño de fuente más pequeño para los enlaces en pantallas pequeñas */
    }

    /* Ajustar el tamaño del texto de bienvenida */
    #menu .welcome-message {
        font-size: 20px; /* Tamaño del título en dispositivos móviles */
    }

    /* Ajustar la descripción de la sección */
    .description-section {
        flex-direction: column; /* Cambiar a columna en pantallas pequeñas */
        text-align: center; /* Centrar el contenido de la sección de descripción */
    }

    .description-section img {
        max-width: 100%; /* Ajustar la imagen al 100% en pantallas pequeñas */
        margin: 20px 0; /* Añadir márgenes verticales en pantallas pequeñas */
    }

    /* Centrar el título en pantallas pequeñas */
    .panel-title {
        text-align: center; /* Centrar el título */
        font-size: 22px; /* Tamaño de fuente del título */
        margin: 20px 0; /* Margen superior e inferior */
    }

    /* Ajustar el menú */
    #menu {
        text-align: center; /* Centrar el contenido dentro del menú */
    }

    #menu .navbar-brand {
        display: inline-block; /* Asegura que la marca se alinee en línea */
        margin: 0; /* Eliminar márgenes para centrarlo mejor */
    }

    #menu .welcome-message {
        font-size: 20px; /* Tamaño del título en dispositivos móviles */
        display: block; /* Asegura que el título se comporte como bloque */
        margin: 0 auto; /* Centrar el título horizontalmente */
        text-align: center; /* Centrar el texto del título */
    }

    #menu .navbar-nav {
        display: block; /* Mostrar los elementos de navegación en columna */
        margin: 0; /* Eliminar márgenes para centrarlos */
        text-align: center; /* Alinear los enlaces al centro dentro del contenedor */
    }

    #menu .navbar-nav > li {
        display: block; /* Mostrar los elementos de la lista en columna */
    }

    #menu .navbar-nav > li > a {
        color: #ffffff; /* Mantener color de los enlaces original */
        margin: 10px 0; /* Ajustar márgenes para espaciado vertical */
        display: block; /* Asegura que los enlaces se comporten como bloques */
        padding: 5px; /* Ajustar el padding para un mejor espaciado */
    }

    #menu .navbar-nav > li > a:hover {
        color: #25e0d0; /* Mantener color de hover original */
    }
}

/* Footer */
#footer {
	background-color: #222222;
	color: #777;
	padding: 15px 0 10px 0;
}
#footer p {
	font-size: 13px;
	margin-top: 10px;
}
#footer a {
	color: #aaa;
}
#footer a:hover, #footer a:focus {
	text-decoration: none;
	color: #bd46b3;
}
    </style>
</head>
 <link rel="icon" href="img/Logo Cinturon.svg" type="image/svg">
<!-- Otros metadatos y enlaces -->
<body>
    <!-- Navigation -->
    <nav id="menu" class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/Logo5.svg" alt="Logo de Amoxtli-Jap">
            </a>
            <span class="welcome-message">Hola, <?php echo $username; ?>!</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="panel_de_control.php">Panel de Control</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="comprar.php">Comprar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="php/cerrar_sesion.php">Cerrar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="politicas_de_privacidad.php">Política de Privacidad</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<!-- Content Section -->
<div class="container content-section mt-5">
    <div class="row d-flex align-items-center">
        <!-- Primera Columna - 4 descripciones -->
        <div class="col-md-4">
            <h5>Módulo WiFi (ESP32)</h5>
            <p>Tu cinturón Amoxtli-Jap cuenta con un módulo WiFi para otorgar conectividad a nuestra Página Web y tu WhatsApp.</p>
            <h5>Detector de Caídas (MPU6050)</h5>
            <p>Tu cinturón Amoxtli-Jap está equipado con un sensor que detecta caídas y envía notificaciones de alerta.</p>
            <h5>GPS (NEO-6M)</h5>
            <p>El GPS incluido en tu cinturón permite una localización precisa del usuario en caso de una caída.</p>
            <h5>Alarma Sonora (Buzzer)</h5>
            <p>El buzzer integrado en tu cinturón emite una alarma sonora para alertar a las personas cercanas en caso de una caída.</p>
        </div>
        
        <!-- Columna Central - Imagen -->
        <div class="col-md-4 text-center">
            <img src="img/Descripción.png" alt="Imagen del cinturón Amoxtli-Jap" class="img-fluid my-4">
        </div>
        
        <!-- Segunda Columna - 4 descripciones -->
        <div class="col-md-4">
            <h5>Puntos de Apoyo(Soportes)</h5>
            <p>Tu cinturón Amoxtli-Jap cuenta con puntos de apoyo para una mayor movilidad y asistencia al usuario.</p>
            <h5>Navegación (Página Web)</h5>
            <p>Amoxtli-Jap te permite navegar en nuestra Página Web para obtener información sobre tu dispositivo.</p>
            <h5>Base de Datos (Panel de Control)</h5>
            <p>Contamos con una base de datos en nuestra Página Web para otorgarte un Panel de Control y puedas tener un monitoreo preciso.</p>
            <h5>Comodidad (Diseño ergonómico)</h5>
            <p>Amoxtli-Jap es un cinturón que garantiza una comodidad y seguridad mediante su diseño único.</p>
        </div>
    </div>
</div>
<style>
/* Cambiar la fuente globalmente */
body {
    font-family: 'OpenSans', sans-serif;
}

/* Estilo para los títulos */
h5 {
    font-size: 1.25rem; /* Tamaño del texto de los títulos */
    font-weight: 500; /* Grosor del texto */
    color: #333; /* Color del texto */
    margin-bottom: 12px; /* Espacio debajo de los títulos */
}

/* Estilo para los párrafos */
p {
    font-size: 0.9rem; /* Tamaño del texto de los párrafos */
    color: #555; /* Color del texto */
    line-height: 1.6; /* Altura de línea para mejorar la legibilidad */
    margin-bottom: 14px; /* Espacio debajo de los párrafos */
}
</style>

    <!-- Footer -->
    <footer id="footer" class="text-center">
        <p>&copy; 2024 Amoxtli-Jap. Todos los derechos reservados.</p>
    </footer>

    <!-- Incluir JS de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
