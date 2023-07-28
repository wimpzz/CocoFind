from flask import Flask, request, jsonify, render_template
from flask_cors import CORS
from werkzeug.utils import secure_filename
import numpy as np
from PIL import Image
import io
import os
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'
import tensorflow as tf

app = Flask(__name__)
CORS(app)

model_path = "./saved_models/new"  # Relative path to the saved_models folder

# Load the model
MODEL = tf.keras.models.load_model(model_path)
CLASS_NAMES = ['Cadang_Cadang', 'Caterpillars', 'Coconut_Bud_Rot', 'Coconut_Leaf_Spot', 'Coconut_Scale_Insect', 'Drying_of_Leaflets', 'Normal_Coconut', 'Yellowing']

@app.route("/ping", methods=["GET"])
def ping():
    return "Test Ping....."

@app.route("/", methods=["GET"])
def root():
    return jsonify({"message": "Hello World!!"})

# @app.route("/predict", methods=["POST"])
# def predict():
#     if "file" not in request.files:
#         return jsonify({"error": "No file uploaded"})

#     file = request.files["file"]
#     if file.filename == "":
#         return jsonify({"error": "No file selected"})

#     if file:
#         image = Image.open(io.BytesIO(file.read())).convert("RGB")
#         image = image.resize((256, 256))  # Resize the image to match the expected input shape

#         img_array = np.array(image)  # Convert the PIL image to a NumPy array
#         img_batch = np.expand_dims(img_array, 0)  # Add an extra dimension for the batch

#         predictions = MODEL.predict(img_batch)

#         predicted_class = CLASS_NAMES[np.argmax(predictions[0])]
#         confidence = np.max(predictions[0])
        
#         return jsonify({
#             'class': predicted_class,
#             'confidence': float(confidence)*100
#         })

@app.route("/predict", methods=["POST"])
def predict():
    if "file" not in request.files:
        return jsonify({"error": "No file uploaded"})

    file = request.files["file"]
    if file.filename == "":
        return jsonify({"error": "No file selected"})

    if file:
        image = Image.open(io.BytesIO(file.read())).convert("RGB")
        image = image.resize((256, 256))  # Resize the image to match the expected input shape

        img_array = np.array(image)  # Convert the PIL image to a NumPy array
        img_batch = np.expand_dims(img_array, 0)  # Add an extra dimension for the batch

        predictions = MODEL.predict(img_batch)

        predicted_classes = [CLASS_NAMES[idx] for idx in np.argsort(predictions[0])[-2:][::-1]]
        confidences = [float(conf) * 100 for conf in np.sort(predictions[0])[-2:][::-1]]

        return jsonify({
            'class1': predicted_classes[0],
            'confidence1': confidences[0],
            # 'class2': predicted_classes[1],
            # 'confidence2': confidences[1]
        })


@app.route("/upload", methods=["GET"])
def upload():
    return render_template("index.html")
    
if __name__ == "__main__":
    # app.run(host="www.cocodatahub.com", port="8001")
    app.run(host="209.172.2.70", port="8001")
