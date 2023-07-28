<?php
    include 'includes/db.php';
    include_once 'includes/navbar.php';
    $submittedCode = $_GET['code'];
    
    // Retrieve the image filenames for the specific code from the database
    $sql = "SELECT leaves, trunk, root FROM coco_images WHERE code = '$submittedCode'";
    $result = $conn->query($sql);
    
    // Check if the query was successful and if any rows were returned
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $leavesImage = $row['leaves'];
        $trunkImage = $row['trunk'];
        $rootImage = $row['root'];
    
        // Generate the URLs for the image files
        $leavesURL = 'captured_images/'.$submittedCode.'/leaves/'.$leavesImage;
        $trunkURL = 'captured_images/'.$submittedCode.'/trunk/'.$trunkImage;
        $rootURL = 'captured_images/'.$submittedCode.'/root/'.$rootImage;
    
        // Output the HTML to display the images
    } else {
        // No images found for the specified code
        echo "<script>alert('No images found for the code: $submittedCode');";
        echo "window.history.back();</script>";
    }
?>

    
<!DOCTYPE html>
<html>
    <head>
        <title>Image Analysis</title>
        <link rel="shortcut icon" type="image/png" href="">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style2.css">
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    </head>
    
    <style>
        .view-section {
            height:auto;
            background: url(images/coconut-bg.jpg) no-repeat;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
        }
        
        h2 {
            font-size: 26px;
            text-align: center;
            color: wheat;
         }

        .image-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .image-container img {
            max-width: 400px;
            border: 2px solid #ddd;
            border-radius: 5px;
            margin: 0 10px;
        }

        .image-container h2 {
            margin-top: 10px;
        }

        .no-images {
            color: red;
            font-weight: bold;
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
        
        .prediction-container img{
            width:400px;
        }

    </style>

    <body>
        <section class="view-section">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <h2>Preview Images for</h2>
                    <h2><?php echo $submittedCode; ?></h2>
                    <br>
                    <?php if ($rootImage && file_exists($rootURL)): ?>
                        <h2>Root Image</h2>
                        <div class="image-container">
                            <img src="<?php echo $rootURL; ?>" alt="Root Image">
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($trunkImage && file_exists($trunkURL)): ?>
                        <h2>Trunk Image</h2>
                        <div class="image-container">
                            <img src="<?php echo $trunkURL; ?>" alt="Trunk Image">
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($leavesImage && file_exists($leavesURL)): ?>
                        <h2>Leaves Image</h2>
                        <div class="image-container">
                            <img src="<?php echo $leavesURL; ?>" alt="Leaves Image">
                        </div>
                        
                        <div class="button-container">
                            <button class="main-btn" name="submit" value="Upload and Predict">
                                <i class="fas fa-upload"></i>
                                Upload and Predict
                            </button>
                        </div>

                    <?php endif; ?>
                </div>
            </form>
    
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Check if the form is submitted
                    if (isset($_POST['submit'])) {
                        if ($leavesImage && file_exists($leavesURL)) {
                            // Get the filename from the leavesURL
                            $filename = basename($leavesURL);
                
                            // Set the target directory to save the uploaded image
                            $targetDir = 'image-analysis/' . $submittedCode . '/';
                
                            // Create the target directory if it doesn't exist
                            if (!is_dir($targetDir)) {
                                mkdir($targetDir, 0777, true);
                            }
                
                            $targetFile = $targetDir . $filename;
                
                            // Copy the image file to the target directory
                            if (copy($leavesURL, $targetFile)) {
                                // Retrieve the FastAPI endpoint URL from the database
                                $sql = "SELECT curl FROM fastapi_endpoint";
                                $result = $conn->query($sql);
                
                                if ($result && $result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $url = $row['curl'];
                                    $url = trim($url);  // Remove any leading/trailing whitespace
                
                                    // Send the uploaded file to the FastAPI endpoint using cURL
                                    $cFile = new CURLFile($targetFile);
                
                                    $postData = [
                                        'file' => $cFile
                                    ];
                
                                    $curl = curl_init($url);
                
                                    curl_setopt($curl, CURLOPT_POST, true);
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                
                                    $response = curl_exec($curl);
                
                                    curl_close($curl);
                
                                    $responseData = json_decode($response, true);
                                    if ($responseData && isset($responseData['class1']) && isset($responseData['confidence1'])) {
                                        $class = $responseData['class1'];
                                        $confidence = $responseData['confidence1'];
                                        $confidence_2 = number_format($responseData['confidence1'], 2);
                
                                        date_default_timezone_set('Asia/Manila');
                                        $datetimeToday = date('Y-m-d H:i:s');
                
                                        $newFilename = $submittedCode . '_' . $datetimeToday . '_' . $class . '_' . $confidence_2 . '.' . pathinfo($filename, PATHINFO_EXTENSION);
                                        $newTargetFile = $targetDir . $newFilename;
                
                                        if (rename($targetFile, $newTargetFile)) {
                                            $targetFile = $newTargetFile;
                
                                            $sql = "INSERT INTO ia_result (code, class, confidence, time) VALUES ('$submittedCode', '$class', '$confidence','$datetimeToday')
                                                    ON DUPLICATE KEY UPDATE class = '$class', confidence = '$confidence', time ='$datetimeToday'";
                                            if ($conn->query($sql) === TRUE) {
                                                echo "<script>console.log('Prediction result saved to the database.');</script>";
                                            } else {
                                                echo "<script>console.log('Error: " . $conn->error . "');</script>";
                                            }
                                        }
                                    } else {
                                        echo "<script>alert('Unable to connect to the server!');</script>";
                                    }
                
                                } else {
                                    echo "<script>alert('Unable to retrieve FastAPI endpoint URL from the database.');</script>";
                                }
                
                            } else {
                                echo "Error uploading leaves image.";
                            }
                        }
                    }
                }
            ?>

            <!-- Modal -->
            <div id="resultModal" class="modal">
                <div class="modal-content">
                    <span id="closeModal" class="modal-close">&times;</span>
                    <?php
                    if (isset($responseData)) {
                        echo "<h2 style=color:black;>Prediction Result</h2>";
                        echo "<div class='prediction-container'>";
                        echo "<img src='" . $targetFile . "' alt='Uploaded Image'>";
                        echo "<div class='prediction-info'>";
                        echo "<p>Class: " . $responseData['class1'] . "</p>";
                        echo "<p>Confidence: " . $responseData['confidence1'] . "%</p>";
                        echo "</div>";
                        echo "<br>";
                        echo "<form action='https://sheetdb.io/api/v1/90somk0ibfn61' method='post' id='sheetdb-form'>";
                        echo "<input type='text' name='Code' id='Code' value='" . $submittedCode . "' style='display:none;'>";
                        echo "<input type='text' name='Class' id='Class' value='" . $class . "' style='display:none;'>";
                        echo "<input type='text' name='Confidence' id='Confidence' value='" . $confidence_2 . "' style='display:none;'>";
                        echo "<button id='submitButton' type='submit' style='opacity:0'>Close</button>";
                        echo "<form>";
                        echo "</div>";
                    }
                    ?>
                
                    <script>
                        var form = document.getElementById('sheetdb-form');
                        form.addEventListener("submit", e => {
                            e.preventDefault();
                            fetch(form.action, {
                                method: "POST",
                                body: new FormData(document.getElementById("sheetdb-form")),
                            }).then(
                                response => response.json()
                            ).then((html) => {
                                alert("Prediction Success!");
                                window.location.href = 'view_images.php?code=<?php echo urlencode($submittedCode); ?>';
                            });
                        });
                
                        // Automatically click the button
                        document.getElementById('submitButton').click();
                    </script>
                </div>

            </div>
            
            <script>
                var modal = document.getElementById('resultModal');
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
        
            </script>
    </body>
</html>