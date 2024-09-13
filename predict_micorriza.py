import sys
import tensorflow as tf
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image
import numpy as np

# Asegurarse de que la salida estándar use UTF-8
import io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

try:
    # Cargar el modelo .h5
    model = load_model('C:\\xampp\\htdocs\\laravel_micorisas\\micorriza_detector.h5')
except Exception as e:
    print(f"Error al cargar el modelo: {e}")
    sys.exit(1)

try:
    # Obtener el path de la imagen desde los argumentos del sistema
    image_path = sys.argv[1]

    # Preprocesar la imagen
    img = image.load_img(image_path, target_size=(256, 256))
    img_array = image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0) / 255.0

    # Hacer la predicción
    predictions = model.predict(img_array)

    # Devolver la clase con la mayor probabilidad
    predicted_class = np.argmax(predictions, axis=1)

    # Salida de la predicción con codificación UTF-8
    print(predicted_class[0])

except Exception as e:
    # Imprimir error con codificación UTF-8
    print(f"Error al procesar la imagen o hacer la predicción: {e}")
    sys.exit(1)
