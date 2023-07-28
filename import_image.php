<?php
include 'includes/db.php';
include_once 'includes/navbar.php';

$submittedCode = $_GET['code'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Directory names for root, trunk, and leaves same as captured_images folder
    $rootDir = 'captured_images/'.$submittedCode.'/root/';
    $trunkDir = 'captured_images/'.$submittedCode.'/trunk/';
    $leavesDir = 'captured_images/'.$submittedCode.'/leaves/';

    // Create the directories if they don't exist
    if (!is_dir($rootDir)) {
        mkdir($rootDir, 0777, true);
    }
    if (!is_dir($trunkDir)) {
        mkdir($trunkDir, 0777, true);
    }
    if (!is_dir($leavesDir)) {
        mkdir($leavesDir, 0777, true);
    }
    
    // Set the timezone to Philippine Standard Time (PST)
    date_default_timezone_set('Asia/Manila');
    $currentDateTime = date('Y-m-d H:i:s');

    // Move the uploaded files to their respective directories if they exist
    if ($_FILES['rootImage']['error'] === UPLOAD_ERR_OK) {
        $rootImage = $_FILES['rootImage']['tmp_name'];
        $rootImageName = $submittedCode. '_'. $currentDateTime . '_'. 'Root'. '.png';
        // $rootImageName = 'root - ' . $currentDateTime . '.png';
        move_uploaded_file($rootImage, $rootDir . $rootImageName);
    }

    if ($_FILES['trunkImage']['error'] === UPLOAD_ERR_OK) {
        $trunkImage = $_FILES['trunkImage']['tmp_name'];
        $trunkImageName = $submittedCode. '_'. $currentDateTime . '_'. 'Trunk'. '.png';
        move_uploaded_file($trunkImage, $trunkDir . $trunkImageName);
    }

    if ($_FILES['leavesImage']['error'] === UPLOAD_ERR_OK) {
        $leavesImage = $_FILES['leavesImage']['tmp_name'];
        $leavesImageName = $submittedCode. '_'. $currentDateTime . '_'. 'Leaves'. '.png';
        move_uploaded_file($leavesImage, $leavesDir . $leavesImageName);
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO coco_images (code, root, trunk, leaves)
            VALUES ('$submittedCode', '$rootImageName', '$trunkImageName', '$leavesImageName')
            ON DUPLICATE KEY UPDATE 
            root = IF('$rootImageName' != '', '$rootImageName', root),
            trunk = IF('$trunkImageName' != '', '$trunkImageName', trunk),
            leaves = IF('$leavesImageName' != '', '$leavesImageName', leaves)";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Import Images Successfully!!!');</script>";
        echo "<script>window.location.href = 'view_images.php?code=$submittedCode';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }


    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import Images</title>
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
        align-items: center;
        justify-content: center;
        height: auto;
        background: url(images/coconut-bg.jpg) no-repeat;
        background-size: cover;
        background-position: center center;
        background-attachment: fixed;
    }

    .container {
        margin-top:100px;
        margin-bottom:50px;
        background-color: white;
        width:400px;
        max-width: 1000px;
        padding: 50px;
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
        text-align: center;
    }

    
    .form-group input[type="submit"] {
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
    
    .form-group input[type="submit"]:hover{
        background: #fbd0d9;
        color: #99454c;
        border-style: solid;
        border-width: 3px;
        border-color: #99454c;
        box-shadow: 0 3px 3px rgba(0, 0, 0, .5);
        transform: scale(1.1);
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
        text-align: center; 
        margin: 0 auto;
    }

    .preview-image {
        width: 100%;
        height: 200px;
        border: 1px solid #ccc;
        padding: 10px;
    }

    #root-preview,
    #trunk-preview,
    #leaves-preview {
        max-width: 100%;
        max-height: 100%;
    }


</style>

<body>
    <div class="container">
        <h1>Import Images</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="rootImage">Root Image:</label>
                <input type="file" id="rootImage" name="rootImage" accept="image/*"><br>
                <div class="preview-container">
                    <div class="preview-image">
                        <img id="root-preview" src="#" alt="Preview">
                    </div>
                </div><br>
                <label for="trunkImage">Trunk Image:</label>
                <input type="file" id="trunkImage" name="trunkImage" accept="image/*"><br>
                <div class="preview-container">
                    <div class="preview-image">
                        <img id="trunk-preview" src="#" alt="Preview">
                    </div>
                </div><br>
                
                <label for="leavesImage">Leaves Image:</label>
                <input type="file" id="leavesImage" name="leavesImage" accept="image/*"><br>
                <div class="preview-container">
                    <div class="preview-image">
                        <img id="leaves-preview" src="#" alt="Preview">
                    </div>
                </div><br>
            </div>
            
            <div class="form-group">
                <input type="submit" value="Import Image Parts">
            </div>
           
            
         
        </form>
    </div>

    <script>
        var previewRootImage = document.getElementById('root-preview');
        var previewTrunkImage = document.getElementById('trunk-preview');
        var previewLeavesImage = document.getElementById('leaves-preview');

        // Preview Root Image
        document.getElementById('rootImage').addEventListener('change', function (event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function () {
                previewRootImage.src = reader.result; // Update preview image

                // Set submitted image in modal
                modalImage.src = reader.result;
            };
            reader.readAsDataURL(input.files[0]);
        });
        
        // Preview Trunk Image
        document.getElementById('trunkImage').addEventListener('change', function (event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function () {
                previewTrunkImage.src = reader.result; // Update preview image

                // Set submitted image in modal
                modalImage.src = reader.result;
            };
            reader.readAsDataURL(input.files[0]);
        });
        
        // Preview Leaves Image
        document.getElementById('leavesImage').addEventListener('change', function (event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function () {
                previewLeavesImage.src = reader.result; // Update preview image

                // Set submitted image in modal
                modalImage.src = reader.result;
            };
            reader.readAsDataURL(input.files[0]);
        });

    </script>

</body>
</html>
