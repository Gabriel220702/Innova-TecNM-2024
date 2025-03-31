import matplotlib.pyplot as plt
import numpy as np

# Datos de ejemplo ajustados para incluir el punto final (1,1)
falsos_positivos = [0.00, 0.02, 0.04, 0.06, 0.08, 0.10, 0.12, 0.14, 0.16, 1.0]
verdaderos_positivos = [0.00, 0.82, 0.90, 0.94, 0.96, 0.98, 0.99, 1.00, 1.00, 1.00]

# Crear la curva ROC
plt.plot(falsos_positivos, verdaderos_positivos, color='b', label='Curva ROC')

# Buscar el índice donde TPR es 0.95
tpr_95_index = next(i for i, v in enumerate(verdaderos_positivos) if v >= 0.95)

# Marcar el punto del 95% de éxito
plt.plot(falsos_positivos[tpr_95_index], verdaderos_positivos[tpr_95_index], 
         'go', label=f'95% Éxito ({falsos_positivos[tpr_95_index]:.2f}, {verdaderos_positivos[tpr_95_index]:.2f})')

# Agregar línea diagonal de azar
plt.plot([0, 1], [0, 1], color='black', linestyle='--', label='Línea de azar')

# Configurar el gráfico para extender los ejes
plt.xlim([0.0, 1.01])  # Extender eje X a 1.2
plt.ylim([0.0, 1.01])  # Extender eje Y a 1.2
plt.xlabel('Tasa de Falsos Positivos (FP)')  # Etiqueta del eje X
plt.ylabel('Tasa de Verdaderos Positivos (TP)')  # Etiqueta del eje Y
plt.title('Curva ROC para la Detección de Caídas')  # Título de la gráfica
plt.legend(loc="lower right")

# Mostrar la gráfica
plt.grid(True)
plt.show()
