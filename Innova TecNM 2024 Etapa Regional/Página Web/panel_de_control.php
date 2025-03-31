<?php
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['Email_Session'])) {
    echo '<script>
            alert("Por favor debes iniciar sesión");
            window.location = "SignUp.php";
          </script>';
    session_destroy();
    die();
}

// Conectar a la base de datos
include 'php/conexion_be.php';

// Consulta para obtener el id_usuario a partir del correo electrónico
$correo_usuario = $_SESSION['Email_Session']; // Corregido para usar la sesión correcta
$sql_usuario = "SELECT ID FROM register WHERE email = ?";
$stmt_usuario = $conexion->prepare($sql_usuario);
$stmt_usuario->bind_param("s", $correo_usuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();
$id_usuario = $usuario['ID'];

if (!$id_usuario) {
    die('No se encontró el usuario.');
}

// Consulta para obtener datos de la tabla sensores filtrados por el id_usuario
$sql_sensores = "SELECT sensores.*
                 FROM sensores
                 WHERE sensores.id_dispositivo = ?";
$stmt_sensores = $conexion->prepare($sql_sensores);
$stmt_sensores->bind_param("i", $id_usuario);
$stmt_sensores->execute();
$result_sensores = $stmt_sensores->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
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
    background-color: #2033e0; /* Color del fondo del menú */
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
    color: #ffffff; /* Color del texto de la marca */
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
    color: #ffffff; /* Color del texto de bienvenida */
    margin-left: 10px;
}

#menu .navbar-nav > li > a {
    text-transform: uppercase;
    color: #ffffff; /* Color del texto de los enlaces */
    font-size: 13px;
    letter-spacing: 1px;
}

#menu .navbar-nav > li > a:hover {
    color: #ff86d7; /* Color del texto de los enlaces al pasar el cursor */
}

.navbar-toggler {
    border-radius: 0;
}

.navbar-toggler:hover, .navbar-toggler:focus {
    background-color: #ff86d7; /* Color de fondo del botón de alternancia */
    border-color: #bd46b3; /* Color del borde del botón de alternancia */
}

.navbar-toggler:hover > .icon-bar {
    background-color: #FFF; /* Color de las barras del icono del botón de alternancia */
}

/* Table */
.table {
    margin-top: 20px;
    background-color: rgba(255, 255, 255, 0.3); /* Fondo más translúcido */
    border-radius: 8px;
    overflow: hidden; /* Para que los bordes redondeados se apliquen correctamente */
    width: 100%; /* Asegura que la tabla ocupe todo el ancho disponible */
    box-sizing: border-box; /* Incluye el padding y el borde en el cálculo del ancho total */
}

.table thead {
    background-color: #2033e0; /* Fondo sólido para el encabezado */
    color: #ffffff;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(249, 249, 249, 0.3); /* Fondo más translúcido para las filas alternas */
}

.table-striped tbody tr:nth-of-type(even) {
    background-color: rgba(255, 255, 255, 0.3); /* Fondo más translúcido para las filas pares */
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
        font-size: 18px; /* Tamaño de fuente más pequeño para el mensaje de bienvenida */
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
        color: #ff86d7; /* Mantener color de hover original */
    }
}
    </style>
</head>
 <link rel="icon" href="img/Logo Cinturon.svg" type="image/svg">
<body>
   <!-- Navigation -->
<nav id="menu" class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="img/Logo5.svg" alt="Logo de Amoxtli-Jap">
        </a>
        <span class="welcome-message">Tu Panel de Control</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="SignIn_SignUp/SignIn.php">Usuario</a>
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
<!-- Data Table -->
<div class="container">
    <?php if ($result_sensores->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha y Hora</th>
                    <th>Posición de Caída</th>
                    <th>Latitud</th>
                    <th>Longitud</th>
                    <th>Mapa</th> <!-- Nueva columna -->
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result_sensores->fetch_assoc()): ?>
                    <?php
                        // Formatear la fecha y hora en formato AM/PM
                        $timestamp = $row["timestamp"];
                        $datetime = new DateTime($timestamp);
                        $formattedDate = $datetime->format('d/m/Y h:i:s A'); // Cambia el formato según tus necesidades

                        // Coordenadas
                        $latitud = htmlspecialchars($row["gps_latitud"]);
                        $longitud = htmlspecialchars($row["gps_longitud"]);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id_dispositivo"]); ?></td>
                        <td><?php echo htmlspecialchars($formattedDate); ?></td>
                        <td><?php echo htmlspecialchars($row["posicion"]); ?></td>
                        <td><?php echo htmlspecialchars($latitud); ?></td>
                        <td><?php echo htmlspecialchars($longitud); ?></td>
                        <td><a href="https://www.google.com/maps?q=<?php echo $latitud; ?>,<?php echo $longitud; ?>" target="_blank">Ver</a></td> <!-- Enlace a Google Maps -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron datos de sensores.</p>
    <?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
