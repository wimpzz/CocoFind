<?php
    include_once 'includes/navbar.php';
    include 'includes/db.php';
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
      }

      h1 {
        font-size: 36px;
        text-align: center;
        color: wheat;
      }
      h2 {
        font-size: 26px;
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

      .no-camera-message {
        text-align: center;
        font-size: 18px;
        color: red;
        margin-top: 20px;
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

      .capture-btn:hover {
        background: #fbd0d9;
        color: #99454c;
        border-style: solid;
        border-width: 3px;
        border-color: #99454c;
        box-shadow: 0 3px 3px rgba(0, 0, 0, .5);
        transform: scale(1.1);
      }

    </style>
    
    <section class="home-section">

    <h1>Capture Images</h1>
    <h2>
        <?php
            $submittedCode = $_GET['code'];
            echo $submittedCode;
        ?>
    </h2>
    

    <button class="cam-btn" onclick="toggleCamera()"><i class="fas fa-sync-alt"></i></button>
    <div class="no-camera-message" id="no-camera-message"></div>

    <div class="footage">
      <video autoplay="true" id="preview"></video>
    </div>

    <div class = "button-container">
        <button class="capture-btn" onclick="captureImage('captured_leaves')">Capture Leaves</button>
        <button class="capture-btn" onclick="captureImage('captured_trunk')">Capture Trunk</button>
        <button class="capture-btn" onclick="captureImage('captured_root')">Capture Root</button>
    </div>
    
    
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
      scanner = new Instascan.Scanner({
        video: document.getElementById('preview'),
        mirror: false
      })

      Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
          if (cameraId === 'back') {
            // Use the back camera if available
            let backCamera = cameras.find(camera => camera.name.includes('back'));
            if (backCamera) {
              scanner.start(backCamera);
            } else {
              // If back camera is not found, use the first camera
              scanner.start(cameras[0]);
            }
          } else {
            // Use the front camera if available
            let frontCamera = cameras.find(camera => camera.name.includes('front'));
            if (frontCamera) {
              scanner.start(frontCamera);
            } else {
              // If front camera is not found, use the last camera
              scanner.start(cameras[cameras.length - 1]);
            }
          }
        } else {
          // No cameras found, display a message
          document.getElementById('no-camera-message').textContent = 'No camera found.';
        }
      }).catch(function(e) {
        console.error(e);
        document.getElementById('no-camera-message').textContent = 'Failed to access camera.';
      });
    }

    function toggleCamera() {
      if (previousCamera === 'front') {
        startScanner('back');
        previousCamera = 'back';
      } else {
        startScanner('front');
        previousCamera = 'front';
      }
    }

    function captureImage(folderName) {
      let video = document.getElementById('preview');
      let canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

      // Convert the canvas image to base64 data URL
      let imageDataURL = canvas.toDataURL('image/png');
      console.log('Captured Image Data URL:', imageDataURL);

      // Create a form data object
      let formData = new FormData();
      formData.append('image', imageDataURL);
      formData.append('folder', folderName);

      // Send the image data to the server using AJAX
      let xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload.php?code=<?php echo $submittedCode;?>', true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          console.log('Image uploaded successfully!');
          if (folderName === 'captured_leaves') {
            alert('Leaves image uploaded successfully!');
          }
          if (folderName === 'captured_trunk') {
            alert('Trunk image uploaded successfully!');
          }
          if (folderName === 'captured_root') {
            alert('Root image uploaded successfully!');
          }
        }
      };
      xhr.send(formData);
    }

    // Start the scanner initially with the back camera
    startScanner('back');
  </script>
</body>

</html>
