#include <Wire.h>
#include <WiFi.h>
#include <MPU6050.h>
#include <HTTPClient.h>
#include <TinyGPS++.h>

MPU6050 mpu;

const char *ssid = "Redmi Note 13";
const char *password = "22072207";
const char *host = "192.168.1.71"; // IP local de tu computadora
const int buzzerPin = 5;
const int buttonPin = 18;

WiFiClient client;

String token = "Bearer EAAQ8j5c9GKUBOy43CLu6XuIIJ4xFZB56gclkmwGj7Nc7lw4Wh3NY9mf3gTYrQboTwymBh1i8MVvQ7jEEJrakcUqq5Lf8B4QZAOsdLzZCs5ckZAjU1elzN23ZCxTao4zRaP3TxAU7QxWBMuQ6KRrOa9gZAltv7ZBgHVuwz7is2FdEXFBUwc7FyyAeDKWaTzEWPqh";
String servidor = "https://graph.facebook.com/v19.0/345081398691756/messages";

// Configuración del id del dispositivo
String idDispositivo = "44"; // Reemplaza "42" con el id del usuario correspondiente

enum class Posicion { Normal, BocaArriba, BocaAbajo, LadoIzquierdo, LadoDerecho };
Posicion posicionActual = Posicion::Normal;
Posicion ultimaPosicionValida = Posicion::Normal; // Almacena la última posición válida detectada
Posicion posicionCaidaEsperada = Posicion::Normal; // Almacena la posición esperada para la caída
unsigned long ultimoCambioPosicion = 0;
const unsigned long tiempoMinimoCambioPosicion = 5000;
bool alarmaActivada = false;
bool estadoFuerte = true;
bool caidaDetectada = false; // Indica si se detectó una caída en una posición inválida
bool datosEnviados = false; // Indica si los datos de la caída han sido enviados
bool caidaInformada = false; // Variable de estado para controlar si ya se ha enviado la informacion de la caida
bool alertaEnviada = false; // Para asegurar que la alerta solo se envie una vez por caida
bool caidaConfirmada = false; // Nueva bandera para confirmar la caída

TinyGPSPlus gps;
#define RXD2 16
#define TXD2 17
HardwareSerial neogps(1);

bool deteccion_de_caida();
void actualizarPosicion();
bool enviarDatos(String posicionCaida);
String obtenerDatosGPS();
String obtenerFechaHora();
void alternarBuzzer();
void detenerBuzzer();
String obtenerNombrePosicion(Posicion posicion);

void setup() {
  Serial.begin(115200);
  Wire.begin();
  mpu.initialize();

  Serial.println("Conectando a la red WiFi...");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi conectado.");

  pinMode(buzzerPin, OUTPUT);
  pinMode(buttonPin, INPUT_PULLUP);

  neogps.begin(9600, SERIAL_8N1, RXD2, TXD2);
}

void loop() {
  static unsigned long lastInternetCheckTime = 0;
  if (millis() - lastInternetCheckTime >= 10000) {
    lastInternetCheckTime = millis();
    if (WiFi.status() == WL_CONNECTED) {
      Serial.println("Conectado a Internet.");
    } else {
      Serial.println("Error: No se pudo conectar a Internet.");
    }
  }

  if (deteccion_de_caida()) {
    Serial.println("CAÍDA DETECTADA");

    // Esperar 5 segundos para obtener la nueva posición
    delay(5000);
    actualizarPosicion();
    String posicionCaida = obtenerNombrePosicion(posicionActual);
    Serial.println("Posición después de la caída: " + posicionCaida);

    if (posicionActual != Posicion::Normal) {
      caidaDetectada = true;

      // Solo proceder si no se ha enviado una alerta aún
      if (!alertaEnviada) {
        posicionCaidaEsperada = posicionActual; // Guarda la posición esperada para la caída
        ultimaPosicionValida = posicionActual; // Actualiza la última posición válida
        if (enviarDatos(posicionCaida)) {
          Serial.println("Datos enviados al servidor.");
          alertaEnviada = true; // Marcar alerta como enviada
          caidaConfirmada = true; // Confirmar que la caída ha sido gestionada
          datosEnviados = true; // Marcar datos como enviados
        } else {
          Serial.println("Error al conectar con el servidor.");
        }
        alarmaActivada = true; // Solo activar la alarma si se envían los datos correctamente
      }
    } else {
      caidaDetectada = true; // Marcar caída detectada para esperar la posición válida
    }
    delay(1000);
  } else {
    // Si no se detecta una caída, reiniciar el control de alerta si se ha gestionado una caída
    if (caidaDetectada && alertaEnviada) {
      caidaDetectada = false;
      caidaInformada = false;
      alertaEnviada = false;
      caidaConfirmada = false;
      datosEnviados = false; // Reiniciar datos enviados
    }
  }

  actualizarPosicion();

  // Enviar datos si se detecta una posición válida después de una caída
  if (caidaDetectada && posicionActual != Posicion::Normal && posicionActual != ultimaPosicionValida && posicionActual != posicionCaidaEsperada) {
    if (!caidaInformada && caidaConfirmada && datosEnviados) {
      // Código para enviar datos y activar alarma
      if (enviarDatos(obtenerNombrePosicion(posicionActual))) {
        Serial.println("Datos enviados al servidor.");
        caidaInformada = true; // Marca que la caída ha sido informada
      }
    }
  }

  // Manejo de la alarma
  if (alarmaActivada) {
    alternarBuzzer();
    if (digitalRead(buttonPin) == LOW) {
      detenerBuzzer();
      alarmaActivada = false; // Restablecer alarmaActivada a false
      caidaInformada = false; // Restablecer caidaInformada a false
      alertaEnviada = false; // Restablecer alertaEnviada a false para nuevas caídas
      caidaConfirmada = false; // Restablecer caidaConfirmada a false para nuevas caídas
      datosEnviados = false; // Restablecer datosEnviados a false
      Serial.println("Alarma detenida por el botón.");
    }
  }

  if (digitalRead(buttonPin) == LOW) {
    detenerBuzzer();
    alarmaActivada = false;
    caidaInformada = false; // Restablecer la variable de estado en false
    alertaEnviada = false; // Restablecer alertaEnviada a false para nuevas caídas
    caidaConfirmada = false; // Restablecer caidaConfirmada a false para nuevas caídas
    datosEnviados = false; // Restablecer datosEnviados a false
    Serial.println("Alarma detenida por el botón.");
  }

  delay(100);

  while (neogps.available() > 0) {
    gps.encode(neogps.read());
  }
}

bool deteccion_de_caida() {
  int16_t ax, ay, az;
  mpu.getAcceleration(&ax, &ay, &az);

  float acceleration = sqrt(ax * ax + ay * ay + az * az);
  float threshold = 35500.0;

  if (posicionActual != Posicion::Normal && acceleration > threshold) {
    return true;
  } else {
    return false;
  }
}

bool enviarDatos(String posicionCaida) {
  bool success = false;

  if (WiFi.status() == WL_CONNECTED) {
    // Obtener datos GPS
    String gpsData = obtenerDatosGPS();
    String latitud = gpsData.substring(0, gpsData.indexOf(','));
    String longitud = gpsData.substring(gpsData.indexOf(',') + 1);

    // Enviar datos a la página web
    HTTPClient http;
    http.begin("http://192.168.1.71/Amoxtli_Jap/php/recibir_datos.php"); // URL local del archivo PHP
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // Solo enviar datos si la posición no es "Normal"
    if (posicionCaida != "Normal") {
      String payload = "id_dispositivo=" + idDispositivo + "&latitud=" + latitud + "&longitud=" + longitud + "&fecha_hora=" + obtenerFechaHora() + "&posicion_caida=" + posicionCaida;
      Serial.println("Enviando datos con payload: " + payload);

      int httpResponseCode = http.POST(payload);
      if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println(response);
        success = true;
      } else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }
      http.end();
    }

    // Enviar alerta a WhatsApp
    HTTPClient httpWhatsApp;
    httpWhatsApp.begin(servidor);
    httpWhatsApp.addHeader("Content-Type", "application/json");
    httpWhatsApp.addHeader("Authorization", token);

    String payloadWhatsApp = "{\"messaging_product\":\"whatsapp\",\"to\":\"526761055560\",\"type\":\"text\",\"text\":{\"body\":\"¡Alerta de caída! Hemos detectado que Gabriel ha sufrido una caída. Ingresa a este enlace inmediatamente para obtener información y auxiliar a tu familiar: http://192.168.1.71/Amoxtli_Jap/formulario.php\"}}";
    Serial.println("Enviando mensaje de WhatsApp con payload: " + payloadWhatsApp);

    int httpPostCode = httpWhatsApp.POST(payloadWhatsApp);
    if (httpPostCode > 0) {
      int httpResponseCode = httpWhatsApp.GET();
      if (httpResponseCode > 0) {
        Serial.print("Código de respuesta: ");
        Serial.println(httpResponseCode);
        String response = httpWhatsApp.getString();
        Serial.println(response);
        success = true;
      } else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }
    } else {
      Serial.print("Error code: ");
      Serial.println(httpPostCode);
    }
    httpWhatsApp.end();
  } else {
    Serial.println("No hay conexión WiFi.");
  }

  return success;
}

String obtenerDatosGPS() {
  if (gps.location.isValid()) {
    return String(gps.location.lat(), 6) + "," + String(gps.location.lng(), 6);
  } else {
    return "0.0,0.0";
  }
}

String obtenerFechaHora() {
  // Obtener la hora UTC del GPS
  int year = gps.date.year();
  int month = gps.date.month();
  int day = gps.date.day();
  int hour = gps.time.hour();
  int minute = gps.time.minute();
  int second = gps.time.second();
  
  // Ajustar a la zona horaria local (ejemplo: UTC-6)
  int timezoneOffset = -6; // Cambia esto según tu zona horaria
  hour += timezoneOffset;

  // Manejar el cambio de día y fecha si es necesario
  if (hour < 0) {
    hour += 24;
    day -= 1;
    // Ajustar el mes y año si es necesario
    if (day < 1) {
      month -= 1;
      if (month < 1) {
        month = 12;
        year -= 1;
      }
      // Ajustar el día del mes según el nuevo mes y año
      day = getDaysInMonth(month, year);
    }
  } else if (hour >= 24) {
    hour -= 24;
    day += 1;
    // Ajustar el mes y año si es necesario
    if (day > getDaysInMonth(month, year)) {
      day = 1;
      month += 1;
      if (month > 12) {
        month = 1;
        year += 1;
      }
    }
  }

  // Formatear la fecha y hora en formato de 24 horas
  char fechaHora[20];
  sprintf(fechaHora, "%04d-%02d-%02d %02d:%02d:%02d", year, month, day, hour, minute, second);
  
  return String(fechaHora);
}

int getDaysInMonth(int month, int year) {
  switch (month) {
    case 4: case 6: case 9: case 11: return 30;
    case 2: return (year % 4 == 0 && year % 100 != 0) || (year % 400 == 0) ? 29 : 28;
    default: return 31;
  }
}

void alternarBuzzer() {
  if (estadoFuerte) {
    tone(buzzerPin, 900);
    delay(500);
  } else {
    tone(buzzerPin, 500);
    delay(500);
  }
  noTone(buzzerPin);
  estadoFuerte = !estadoFuerte;
}

void detenerBuzzer() {
  noTone(buzzerPin);
}

void actualizarPosicion() {
  int16_t ax, ay, az;
  mpu.getAcceleration(&ax, &ay, &az);

  float pitch = atan2(-ax, sqrt(ay * ay + az * az)) * RAD_TO_DEG;
  float roll = atan2(ay, az) * RAD_TO_DEG;

  Posicion nuevaPosicion = Posicion::Normal;

  // Ajustar umbrales y tolerancias
  float umbralLado = 16000.0;
  float umbralCuerpo = 14000.0;

  if (pitch > 45.0 && pitch < 135.0 && roll > -45.0 && roll < 45.0) {
    nuevaPosicion = Posicion::Normal;  // De pie o sentado
  } else if (az > umbralCuerpo) {
    nuevaPosicion = Posicion::BocaArriba;  // Boca arriba
  } else if (az < -umbralCuerpo) {
    nuevaPosicion = Posicion::BocaAbajo;  // Boca abajo
  } else if (ay < -umbralLado) {
    nuevaPosicion = Posicion::LadoIzquierdo;  // Lado izquierdo
  } else if (ay > umbralLado) {
    nuevaPosicion = Posicion::LadoDerecho;  // Lado derecho
  }
    // Verificar si ha pasado suficiente tiempo desde el último cambio
    if (nuevaPosicion != posicionActual && millis() - ultimoCambioPosicion > tiempoMinimoCambioPosicion) {
        posicionActual = nuevaPosicion;
        ultimoCambioPosicion = millis();
        Serial.print("Nueva posición detectada: ");
        Serial.println(obtenerNombrePosicion(posicionActual));
        if (caidaDetectada && posicionActual != Posicion::Normal) {
            // Enviar datos correspondientes a la caída
            posicionCaidaEsperada = posicionActual; // Actualizar posicionCaidaEsperada
            String posicionCaida = obtenerNombrePosicion(posicionCaidaEsperada);
            if (enviarDatos(posicionCaida)) {
                Serial.println("Datos enviados al servidor.");
                caidaDetectada = false;
                caidaInformada = false;
            } else {
                Serial.println("Error al conectar con el servidor.");
            }
            alarmaActivada = true; // Activar la alarma
            if (caidaDetectada && posicionActual == posicionCaidaEsperada) {
                caidaDetectada = false;
                caidaInformada = false;
            }
        }
    }
}

String obtenerNombrePosicion(Posicion posicion) {
  switch (posicion) {
    case Posicion::Normal:
      return "Normal";
    case Posicion::BocaArriba:
      return "Acostado Boca Arriba";
    case Posicion::BocaAbajo:
      return "Acostado Boca Abajo";
    case Posicion::LadoIzquierdo:
      return "Acostado sobre su Lado Izquierdo";
    case Posicion::LadoDerecho:
      return "Acostado sobre su Lado Derecho";
    default:
      return "Desconocida";
  }
}
