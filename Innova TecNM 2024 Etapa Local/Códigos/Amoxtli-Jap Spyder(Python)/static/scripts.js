// scripts.js
console.log('Script cargado correctamente.');

// Función para verificar si hay alerta de caída
function checkAlert() {
    fetch('/check_alert')  // Realiza una solicitud GET a la ruta /check_alert
        .then(response => response.json())  // Parsea la respuesta como JSON
        .then(data => {
            if (data.alert) {
                // Si hay una alerta, muestra un mensaje
                alert('¡Alerta de caída recibida!');
            }
        })
        .catch(error => console.error('Error al verificar la alerta:', error));
}

// Verificar la alerta cada 5 segundos
setInterval(checkAlert, 5000);