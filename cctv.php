<?php
    include_once 'includes/navbar.php';
    include 'includes/db.php';
    session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Scan QR Code</title>
    <link rel="shortcut icon" type="image/png" href="">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style2.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
  </head>
  <body>
    <section class = "home-section">
      <style>
        .home-section {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          height: 100vh;
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

          /*border: 5px solid black;*/
        }

        h1 {
          font-size: 34px;
          text-align: center;
          color:wheat;
        }

        .qr-text {
          font-size: 16px;
          text-align: center;
        }

        .detected video {
          border: 10px solid orange;
        }
        
        .error video {
          border: 10px solid red;
          /*background-image: url(images/coconut-bg.jpg);*/
          /*background-repeat: no-repeat;*/
          /*background-position: center;*/
        }
        
        .success video {
          border: 10px solid green;
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
            /*transition: 0.7s ease;*/
        }
        
        .cam-btn:hover {
            background: #fbd0d9;
            color:#99454c;
            border-style:solid;
            border-width:3px;
            border-color:#99454c;
            box-shadow: 0 3px 3px rgba(0,0,0,.5);
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
        .no-camera-message {
            text-align: center;
            font-size: 18px;
            color: red;
            margin-top: 20px;
        }

      </style>

      <h1 >Scan QR Code Using Camera</h1>
      <button class="cam-btn" onclick="toggleCamera()"><i class="fas fa-sync-alt"></i></button>
      <div class="no-camera-message" id="no-camera-message"></div>

      <div class="footage">
        <video autoplay="true" id="preview"></video>
      </div>
      
      <div class="qr-text" id="qr-content"></div>
      
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
          scanner.addListener('scan', function(content) {
            if (content) {
              footage.classList.add('detected');
              var qrCode = content.split('code=')[1];
        
              clearTimeout(timeoutId); // Clear the previous timeout if any
        
              $.ajax({
                type: 'POST',
                url: 'check_qr.php',
                data: { qr_code: qrCode },
                success: function(response) {
                  if (response === 'found') {
                    // The qrCode is found in the database
                    // Do something here, e.g. redirect to another page
                    footage.classList.add('success');
                    window.location.href = "https://cocodatahub.com/homepage.php?code=" + qrCode;
                  } else {
                    // The qrCode is not found in the database
                    // Display an error message and add error icon
                    footage.classList.add('error');
                    $("#qr-content").html("This QR code appears to be unrelated to our website.<br>We recommend using a third-party software or application to scan and read the QR code.")
                    .css("color", "wheat");
        
                    timeoutId = setTimeout(function() {
                      $("#qr-content").empty(); // Clear the error message
                      footage.classList.remove('error'); // Remove error icon
                    }, 2000); // Adjust the delay time (in milliseconds) as desired
                  }
                  footage.classList.remove('detected');
                },
                error: function() {
                  // Handle the AJAX error here
                  alert('Error checking QR code.');
                  footage.classList.remove('detected');
                }
              });
            }
          });
        
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
              // Display a message indicating no cameras are available
              document.getElementById('no-camera-message').textContent = 'No cameras are available.';
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
              alert("No other cameras are available.");
              // Display a message indicating no other cameras are available
              document.getElementById('no-camera-message').textContent = 'No other cameras are available.';
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

    </script>

    <script src="https://kit.fontawesome.com/49bf074e28.js" crossorigin="anonymous"></script>
  </body>
</html>
