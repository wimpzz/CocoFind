<?php
    include 'includes/navbar.php';
    include 'includes/db.php';
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
</style>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        // Get the uploaded file details
        $file = $_FILES['image'];

        // Check if the file is uploaded successfully
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Set the target directory to save the uploaded file
            $targetDir = 'uploads/';
            $targetFile = $targetDir . basename($file['name']);
            
            // Create the target directory if it doesn't exist
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
                
            // Move the uploaded file to the target directory
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                
                // Retrieve the FastAPI endpoint URL from the database
                $sql = "SELECT curl FROM fastapi_endpoint";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $url = $row['curl'];
                    $url = trim($url);  // Remove any leading/trailing whitespace

                    // Send the uploaded file to the FastAPI endpoint using cURL
                    $cFile = new CURLFile($targetFile);
                
                    // Send the uploaded file to the FastAPI endpoint using cURL
                    // $url = 'http://209.172.2.70:8001/predict';  // Replace with the appropriate FastAPI endpoint URL
    
                    // Create an array of post data
                    $postData = [
                        'file' => $cFile
                    ];
    
                    // Initialize cURL
                    $curl = curl_init($url);
    
                    // Set cURL options
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
                    // Execute the cURL request
                    $response = curl_exec($curl);
    
                    // Close cURL
                    curl_close($curl);
    
                    // Handle the response from the FastAPI endpoint
                    $responseData = json_decode($response, true);
                    if ($responseData && isset($responseData['class1']) && isset($responseData['confidence1'])) {
                        // Get the class and confidence values from the response data
                        $class = $responseData['class1'];
                        $confidence = $responseData['confidence1'];
                    } else {
                        echo "<script>alert('Unable to connect to the server!');</script>";
                    }
                }else {
                    echo "<script>alert('Unable to retrieve FastAPI endpoint URL from the database.');</script>";
                }   
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Error: " . $file['error'];
        }
    }
}
?>

<body>
    <div class="container">
        <h1>Image Analysis</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Select an image:</label>
                <input type="file" name="image" id="image" accept="image/*" required>
                <div class="preview-container">
                    <div class="preview-image">
                        <img id="preview" src="#" alt="Preview">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" value="Upload">
                <input type="submit" id="previewBtn" value="Preview">
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div id="resultModal" class="modal">
        <div class="modal-content">
            <span id="closeModal" class="modal-close">&times;</span>
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
        </div>
    </div>


    <script>
        var modal = document.getElementById('resultModal');
        var previewBtn = document.getElementById('previewBtn');
        var modalImage = document.getElementById('modalImage');
        var previewImage = document.getElementById('preview');
        var responseData = <?php echo isset($responseData) ? json_encode($responseData) : "null"; ?>; // Pass the PHP variable to JavaScript

        // Open modal if responseData is not empty
        if (responseData && responseData.hasOwnProperty('class1') && responseData.hasOwnProperty('confidence1')) {
            document.addEventListener('DOMContentLoaded', function () {
                modal.style.display = 'block';
            });
        }

        // Close modal
        document.getElementById('closeModal').addEventListener('click', function () {
            modal.style.display = 'none';
        });

        // Preview image
        document.getElementById('image').addEventListener('change', function (event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function () {
                previewImage.src = reader.result; // Update preview image

                // Set submitted image in modal
                modalImage.src = reader.result;
            };
            reader.readAsDataURL(input.files[0]);
        });

        // Reopen modal on Preview button click
        previewBtn.addEventListener('click', function () {
            modal.style.display = 'block';
        });
    </script>


</body>

</html>
