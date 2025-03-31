#include <Wire.h>
#include <WiFi.h>
#include <MPU6050.h>

MPU6050 mpu;

const char *ssid = "INFINITUM86C2 xd";
const char *password = "ELd4TfVbHW";
const char *host = "localhost";

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
  // Lógica para detectar caídas
  if (deteccion_de_caida()) {
    Serial.println("CAÍDA DETECTADA");
    enviar_alerta();
    delay(5000); // Evita enviar múltiples alertas por una misma caída
  }
  delay(100);
}

bool deteccion_de_caida() {
  // Implementa aquí tu lógica para detectar caídas
  // Puedes basarte en el código anterior o utilizar otro enfoque
  // Devuelve true si se detecta una caída, false de lo contrario
}

void enviar_alerta() {
  if (!client.connect(host, 80)) {
    Serial.println("Error al conectar con el servidor.");
    return;
  }
  client.print("GET /alert HTTP/1.1\r\n");
  client.print("Host: ");
  client.print(host);
  client.print("\r\n\r\n");
  delay(10);
  client.stop();
}
