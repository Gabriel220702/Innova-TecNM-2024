#include <Wire.h>
#include <MPU6050.h>

MPU6050 mpu;

void setup() {
  Serial.begin(115200);
  Wire.begin();
  
  // Inicialización del sensor MPU6050
  mpu.initialize();
  
  // Establecer la escala de rango completo del acelerómetro
  mpu.setFullScaleAccelRange(MPU6050_ACCEL_FS_2);
  
  // Establecer la frecuencia de muestreo del giroscopio
  mpu.setDLPFMode(MPU6050_DLPF_BW_42);
}

void loop() {
  // Variables para almacenar los valores del acelerómetro
  int16_t ax, ay, az;
  
  // Leer los valores del acelerómetro
  mpu.getAcceleration(&ax, &ay, &az);
  
  // Calcular la magnitud del vector de aceleración
  float acceleration = sqrt(ax*ax + ay*ay + az*az);
  
  // Ajustar el umbral para detectar caídas
  float threshold = 25000.0; // Este valor puede requerir ajuste
  
  // Si la aceleración supera el umbral, se considera una caída
  if (acceleration > threshold) {
    Serial.println("¡Caída detectada!");
    // Añade aquí cualquier acción adicional que desees realizar cuando se detecte una caída
  }
  
  delay(100); // Esperar un breve periodo antes de la próxima lectura
}
