<?php
include 'includes/navbar.php';
// include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Image Analysis</title>
    <link rel="shortcut icon" type="image/png" href="">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style2.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>

<style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        font-family: Arial, sans-serif;
        background: url(images/coconut-bg.jpg) no-repeat;
        background-size: cover;
        background-position: center center;
        background-attachment: fixed;
    }

    .container {
        margin-top: 100px;
        background-color: white;
        max-width: 1000px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    h1 {
        text-align: center;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
    }

    .form-group input[type="file"] {
        display: block;
    }

    .form-group input[type="submit"] {
        color: wheat;
        background-color: #c5626d;
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
    }

    .result {
        margin-top: 20px;
        margin-left: 50px;
        text-align: left;
    }

    .preview-container {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        margin-top: 10px;
    }

    .preview-image {
        width: 400px;
        height: 400px;
        border: 1px solid #ccc;
        padding: 10px;
    }

    #preview {
        max-width: 100%;
        max-height: 100%;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        border-radius: 5px;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
    }

    .modal-close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .modal-close:hover,
    .modal-close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .prediction-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        margin-top: 20px;
    }

    .prediction-container img {
        width: 200px;
    }

    /* Camera styles */
    .camera-container {
        position: relative;
        width: 100%;
        height: auto;
        max-width: 1000px;
        margin-bottom: 20px;
        overflow: hidden;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .camera-video {
        width: 100%;
        height: auto;
    }

    .camera-capture {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #c5626d;
        color: wheat;
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
    }
</style>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $targetFolder = 'uploads/'; // Replace with the actual folder path where you want to save the image
    $targetFile = $targetFolder . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        // Image saved successfully
        echo "<script>console.log('Image saved successfully.');</script>";

        // Send the uploaded file to the FastAPI endpoint using cURL
        $url = 'http://209.172.2.70:8000/predict'; // Replace with the appropriate FastAPI endpoint URL

        // Create a cURL file object
        $cFile = new CURLFile($targetFile);

        // Create an array of post data
        $postData = [
            'image' => $cFile
        ];

        // Initialize cURL
        $curl = curl_init($url);

        // Set cURL options
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request
        $response = curl_exec($curl);

        // Check for cURL errors
        if (curl_errno($curl)) {
            echo 'cURL Error: ' . curl_error($curl);
        } else {
            // Close cURL
            curl_close($curl);

            // Handle the response from the FastAPI endpoint
            $responseData = json_decode($response, true);
            if ($responseData && isset($responseData['class1']) && isset($responseData['confidence1'])) {
                // Get the class and confidence values from the response data
                $class = $responseData['class1'];
                $confidence = $responseData['confidence1'];

                // Display the prediction result in the modal
                echo "<script>
                    var modal = document.getElementById('resultModal');
                    var modalImage = document.getElementById('modalImage');
                    var classElement = document.createElement('p');
                    var confidenceElement = document.createElement('p');

                    classElement.innerHTML = 'Class: <strong>" . $class . "</strong>';
                    confidenceElement.innerHTML = 'Confidence: <strong>" . $confidence . "</strong>';

                    modalImage.src = 'uploads/" . basename($_FILES['image']['name']) . "';
                    modalImage.alt = 'Uploaded Image';
                    modal.style.display = 'block';
                    modal.querySelector('.prediction-info').innerHTML = '';
                    modal.querySelector('.prediction-info').appendChild(classElement);
                    modal.querySelector('.prediction-info').appendChild(confidenceElement);
                </script>";
            } else {
                echo "<script>console.log('Prediction data is missing or invalid.');</script>";
            }
        }
    } else {
        // Failed to save image
        echo "<script>console.log('Failed to save image.');</script>";
    }
} else {
    // Invalid request
    echo "<script>console.log('Invalid request.');</script>";
}
?>


<body>
    <div class="container">
        <h1>Image Analysis</h1>
        <div class="camera-container">
            <video id="cameraVideo" class="camera-video"></video>
            <button id="captureButton" class="camera-capture">Capture</button>
        </div>
        <div class="preview-container" style="display: none;">
            <div class="preview-image">
                <img id="preview" src="#" alt="Preview">
            </div>
        </div>
        
        <input type="submit" name="submit" value="Upload">
    </div>
    
    <?php
            if (isset($responseData)) {
                echo "<h2>Prediction Result</h2>";
                echo "<div class='prediction-container'>";
                echo "<img src='" . $targetFile . "' alt='Uploaded Image'>";
                echo "<div class='prediction-info'>";
                echo "<p>Class: " . $responseData['class1'] . "</p>";
                echo "<p>Confidence: " . $responseData['confidence1'] . "%</p>";
                echo "</div>";
                echo "</div>";
            }
            ?>
    <script>
        var previewBtn = document.getElementById('captureButton');
        var previewImage = document.getElementById('preview');

        // Camera stream and capture
        var video = document.getElementById('cameraVideo');
        var captureButton = document.getElementById('captureButton');
        var previewContainer = document.querySelector('.preview-container');

        // Get media stream from the camera
        function getCameraStream() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function (stream) {
                        video.srcObject = stream;
                        video.play();
                    })
                    .catch(function (error) {
                        console.error('Error accessing the camera: ', error);
                    });
            } else {
                console.error('getUserMedia is not supported');
            }
        }

        // Convert data URL to Blob object
        function dataURLtoBlob(dataURL) {
            var parts = dataURL.split(';base64,');
            var contentType = parts[0].split(':')[1];
            var byteCharacters = atob(parts[1]);
            var byteArrays = [];
            for (var i = 0; i < byteCharacters.length; i++) {
                byteArrays.push(byteCharacters.charCodeAt(i));
            }
            return new Blob([new Uint8Array(byteArrays)], { type: contentType });
        }

        // Save the captured image to the server
        function saveImage(dataURL) {
            var imageData = dataURLtoBlob(dataURL);
            var imageFile = new File([imageData], 'capture_image.png');

            var formData = new FormData();
            formData.append('image', imageFile);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Handle the response from the server if needed
                    console.log('Image saved successfully.');
                } else {
                    console.error('Error saving image. Status:', xhr.status);
                }
            };
            xhr.send(formData);
        }

        // Capture frame from the camera stream
        function captureFrame() {
            var canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            var context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert the canvas image to a data URL
            var dataURL = canvas.toDataURL('image/png');
            previewImage.src = dataURL;
            previewContainer.style.display = 'flex';

            // Save the captured image
            saveImage(dataURL);
        }

        // Add event listeners
        previewBtn.addEventListener('click', captureFrame);

        // Start camera stream
        getCameraStream();
    </script>
</body>

</html>
