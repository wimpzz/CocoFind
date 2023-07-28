<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QR Code Scanner</title>
  <style>
    video {
      width: 100%;
      max-width: 500px;
      height: auto;
      position: relative;
    }

    .tracker {
      position: absolute;
      border: 2px solid red;
      pointer-events: none;
    }
  </style>
</head>
<body>
  <h1>QR Code Scanner</h1>
  <video id="preview"></video>
  <div class="tracker"></div>

  <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
  <script>
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    let tracker = document.querySelector('.tracker');

    scanner.addListener('scan', function (content) {
      alert('Scanned: ' + content);
    });

    Instascan.Camera.getCameras().then(function (cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        console.error('No cameras found.');
      }
    }).catch(function (e) {
      console.error(e);
    });

    function trackQRCode(event) {
      const codeLocation = event.detail.codeLocation;
      const { top, left, width, height } = codeLocation;

      tracker.style.top = top + 'px';
      tracker.style.left = left + 'px';
      tracker.style.width = width + 'px';
      tracker.style.height = height + 'px';
    }

    scanner.addListener('active', trackQRCode);
    scanner.addListener('inactive', () => {
      tracker.style.width = '0px';
      tracker.style.height = '0px';
    });
  </script>
</body>
</html>
