<?php
include_once 'includes/navbar.php';
include 'includes/db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <title>Capture Images</title>
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
        .home-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin-top:30px;
            background: url(images/coconut-bg.jpg) no-repeat;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
        }

        .footage {
            width: 500px;
            height: 400px;
        }

        video {
            width: 100%;
            height: 100%;
            background-color: #333;
            border-radius: 20px;
        }

        h1 {
            font-size: 34px;
            text-align: center;
            color: wheat;
        }
        
        h2 {
            font-size: 28px;
            text-align: center;
            color: wheat;
        }

        .cam-btn {
            color: wheat;
            background-color: #c5626d;
            text-decoration: none;
            font-size: 1em;
            font-weight: 600;
            display: block;
            margin: 0 auto;
            padding: 0.5em 0.5em;
            letter-spacing: 1px;
            border-radius: 15px;
        }

        .cam-btn:hover {
            background: #fbd0d9;
            color: #99454c;
            border-style: solid;
            border-width: 3px;
            border-color: #99454c;
            box-shadow: 0 3px 3px rgba(0, 0, 0, .5);
            transform: scale(1.1);
        }

        .cam-btn:hover::before {
            content: "Swap Camera";
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            padding: 4px 8px;
            background-color: #000;
            color: #fff;
            font-size: 12px;
            border-radius: 4px;
            white-space: nowrap;
        }
        
        .capture-btn {
            color: wheat;
            background-color: #c5626d;
            text-decoration: none;
            font-size: 1em;
            font-weight: 600;
            display: block;
            margin: 0 auto;
            padding: 0.5em 0.5em;
            letter-spacing: 1px;
            border-radius: 15px;
            margin-top: 20px;
          }

        .capture-btn:hover {
            background: #fbd0d9;
            color: #99454c;
            border-style: solid;
            border-width: 3px;
            border-color: #99454c;
            box-shadow: 0 3px 3px rgba(0, 0, 0, .5);
            transform: scale(1.1);
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
        
        .modal-image {
            max-width: 100%;
            max-height: 80vh;
        }
      </style>
    
    <body>
        
    <section class="home-section">
        <h1>Capture Images</h1>
        <h2>
            <?php
                $submittedCode = $_GET['code'];
                echo $submittedCode;
            ?>
        </h2>
        <button class="cam-btn" onclick="toggleCamera()"><i class="fas fa-sync-alt"></i></button>

        <div class="footage">
            <video autoplay="true" id="preview"></video>
        </div>
      
        <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                  <span id="closeModal" class="modal-close">&times;</span>
                  <h3>Preview</h3>
                </div>
                <img id="modalImage" src="" alt="Captured Image">
                <div id="caption"></div>
            </div>
        </div>
      
        <div class="button-container">
            <button class="capture-btn" onclick="captureImage('Root')" title="Capture Root">
                <i class="fas fa-tree"></i> Capture Root
            </button>
            <button class="capture-btn" onclick="captureImage('Trunk')" title="Capture Trunk">
                <i class="fas fa-code-branch"></i> Capture Trunk
            </button>
            <button class="capture-btn" onclick="captureImage('Leaves')" title="Capture Leaves">
                <i class="fas fa-leaf"></i> Capture Leaves
            </button>
        </div>


        <!--<div class = "button-container">-->
        <!--    <button class="capture-btn" onclick="window.location.href='import_image.php?code=<?php echo urlencode($submittedCode); ?>'" title="Capture Images">-->
        <!--        <i class="fas fa-upload"></i>&nbsp;Import Images-->
        <!--    </button>-->
        <!--</div>-->
        
    </section>

    <script>
        let scanner;
        let footage = document.querySelector('.footage');
        let timeoutId;
        let previousCamera; // Variable to store the previous camera
        
        function startScanner(cameraId) {
            if (scanner) {
              scanner.stop(); // Stop the previous scanner if running
            }
            
            // Set up the scanner with the selected camera
            scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror: false });
            
            Instascan.Camera.getCameras().then(function(cameras) {
              if (cameras.length > 0) {
                if (cameraId === 'back') {
                  // Use the back camera if available
                  let backCamera = cameras.find(camera => camera.name.includes('back'));
                  if (backCamera) {
                    scanner.start(backCamera);
                  } else {
                    console.error('Back camera not found.');
                  }
                } else {
                  // Use the default camera (usually front) if no specific option is selected
                  scanner.start(cameras[0]);
                  storePreviousCamera(cameras[0]); // Store the default camera as the previous camera
                }
              } else {
                console.error('No cameras found.');
              }
            }).catch(function(e) {
              console.error(e);
            });
            }
            
            // Start the scanner with the default camera
            startScanner();
            
            let currentCameraIndex = 0; // Variable to keep track of the current camera index
            
            // Function to toggle between the front and back cameras
            function toggleCamera() {
            if (scanner) {
              scanner.stop(); // Stop the previous scanner if running
            }
            
            Instascan.Camera.getCameras().then(function(cameras) {
              if (cameras.length > 1) {
                currentCameraIndex = (currentCameraIndex + 1) % cameras.length; // Increment the camera index
            
                let selectedCamera = cameras[currentCameraIndex];
                if (selectedCamera) {
                  scanner.start(selectedCamera);
                  storePreviousCamera(selectedCamera); // Store the selected camera as the previous camera
                  document.getElementById('no-camera-message').textContent = ''; // Clear the message if previously shown
                } else {
                  console.error('Selected camera not found.');
                }
              } else {
                console.log('No other cameras found.');
                // Display a message indicating no other cameras are available
                alert("No other cameras are available.");
                // Use the previous camera if available
                if (previousCamera) {
                  scanner.start(previousCamera);
                  document.getElementById('no-camera-message').textContent = ''; // Clear the message if previously shown
                } else {
                  console.error('Previous camera not found.');
                }
              }
            }).catch(function(e) {
              console.error(e);
            });
        }
        
        // Function to store the previous camera
        function storePreviousCamera(camera) {
            previousCamera = camera;
        }
        
        // Function to capture an image
        function captureImage(part) {
            const video = document.getElementById('preview');
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            
            // Set the canvas dimensions to match the video dimensions
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw the current video frame onto the canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Get the image data from the canvas as a base64-encoded string
            const imageData = canvas.toDataURL('image/png');
            
            // Send the image data to the server and save the image file
            saveImageOnServer(imageData, part);
            
            // Display the captured image in the modal
            const modal = document.getElementById('myModal');
            const modalImage = document.getElementById('modalImage');
            const caption = document.getElementById('caption');
            
            modalImage.src = imageData;
            caption.textContent = 'Captured ' + part+ ' successfully!!';
            
            modal.style.display = 'block';
            
            // Close modal
            document.getElementById('closeModal').addEventListener('click', function() {
              modal.style.display = 'none';
            });
            
            modalImage.style.maxWidth = '100%';
            modalImage.style.maxHeight = '80vh';
            }
            
            // Function to send image data to the server and save the image file
            function saveImageOnServer(imageData, part) {
            // Create a new AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'save_image.php?code=<?php echo $submittedCode;?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            // Handle the AJAX response
            xhr.onreadystatechange = function() {
              if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                  console.log('Image saved successfully');
                } else {
                  console.error('Failed to save the image');
                }
              }
            };
            
            // Prepare the data to be sent
            const data = 'imageData=' + encodeURIComponent(imageData) + '&part=' + encodeURIComponent(part);
            
            // Send the AJAX request
            xhr.send(data);
        }
        </script>

        <script src="https://kit.fontawesome.com/49bf074e28.js" crossorigin="anonymous"></script>
    </body>
</html>
