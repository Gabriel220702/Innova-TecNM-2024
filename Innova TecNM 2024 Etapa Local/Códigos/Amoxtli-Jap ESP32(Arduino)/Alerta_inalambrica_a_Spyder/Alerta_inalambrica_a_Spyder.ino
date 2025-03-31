#include <Wire.h>
#include <WiFi.h>
#include <MPU6050.h>

MPU6050 mpu;

const char *ssid = "INFINITUM86C2 xd";
const char *password = "ELd4TfVbHW";
const char *host = "192.168.1.70";

WiFiClient client;

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
}

void loop() {
  // Verificar la conexión a Internet cada 10 segundos
  static unsigned long lastInternetCheckTime = 0;
  if (millis() - lastInternetCheckTime >= 10000) {
    lastInternetCheckTime = millis();
    if (WiFi.status() == WL_CONNECTED) {
      Serial.println("Conectado a Internet.");
    } else {
      Serial.println("Error: No se pudo conectar a Internet.");
    }
  }
  
  // Lógica para detectar caídas
  if (deteccion_de_caida()) {
    Serial.println("CAÍDA DETECTADA");
    if (!enviar_alerta()) {
      Serial.println("Error al conectar con el servidor.");
    }
    delay(5000); // Evita enviar múltiples alertas por una misma caída
  }
  
  delay(100);
}

bool deteccion_de_caida() {
  // Variables para almacenar los valores del acelerómetro
  int16_t ax, ay, az;
  
  // Leer los valores del acelerómetro
  mpu.getAcceleration(&ax, &ay, &az);
  
  // Calcular la magnitud del vector de aceleración
  float acceleration = sqrt(ax*ax + ay*ay + az*az);
  
  // Ajustar el umbral para detectar caídas
  float threshold = 27000.0; // Este valor puede requerir ajuste
  
  // Si la aceleración supera el umbral, se considera una caída
  if (acceleration > threshold) {
    return true;
  } else {
    return false;
  }
}

bool enviar_alerta() {
  if (!client.connect(host, 80)) {
    return false;
  }
  client.print("GET /alert HTTP/1.1\r\n");
  client.print("Host: ");
  client.print(host);
  client.print("\r\n\r\n");
  delay(10);
  client.stop();
  return true;
}
