<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Coconut Information</title>
    <link rel="shortcut icon" type="image/png" href="images/pup-logo.png">
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" href="style.css">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>
    
<?php
    include 'includes/navbar.php';
    include 'includes/db.php';
    
    $submittedCode = $_GET['code'];
    
    // Perform a SELECT query to retrieve all rows from the table
    $sql = "SELECT * FROM coco_db WHERE code = '$submittedCode'";
    $result = $conn->query($sql);
    
    if (isset($_POST['save'])) {
        // Retrieve the updated values from the form
        $identification = $_POST['identification'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $height = $_POST['height'];
        $diameter = $_POST['diameter'];
        $age = $_POST['age'];
        $growthRate = $_POST['growth-rate'];
        $flowering = $_POST['flowering'];
        $seedProduction = $_POST['seedProduction'];
        $light = $_POST['light'];
        $soil = $_POST['soil'];
        $symptoms = $_POST['symptoms'];
        $others = $_POST['others'];
    
        // Get the current date and time in Manila timezone
        date_default_timezone_set('Asia/Manila');
        $dateToday = date('Y-m-d H:i:s');
    
        // Update the database with the new values
        $sql = "UPDATE coco_db SET
            identification = '$identification',
            latitude = '$latitude',
            longitude = '$longitude',
            height = '$height',
            diameter = '$diameter',
            age = '$age',
            growthRate = '$growthRate',
            flowering = '$flowering',
            seedProduction = '$seedProduction',
            light = '$light',
            soil = '$soil',
            symptoms = '$symptoms',
            pest = '$pest',
            confidence = '$confidence',
            others = '$others',
            date = '$dateToday'
            WHERE code = '$submittedCode'";
    
        if ($conn->query($sql) === true) {
            echo '<script>alert("Details Updated Successfully"); window.location.href = "homepage.php?code=' . $submittedCode . '";</script>';
            exit();
        } else {
            echo "Error updating data: " . $conn->error;
        }
    
        // Close the connection
        $conn->close();
    }
    
    // Check if any rows are returned
    if ($result->num_rows > 0) {
        // Fetch the row and print the values
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $code = $row['code'];
        $qrCode = $row['qr_code'];
        $qrCodeImg = $row['qr_code_img'];
        $identification = $row['identification'];
        $latitude = $row['latitude'];
        $longitude = $row['longitude'];
        $height = $row['height'];
        $diameter = $row['diameter'];
        $age = $row['age'];
        $growthRate = $row['growthRate'];
        $flowering = $row['flowering'];
        $seedProduction = $row['seedProduction'];
        $light = $row['light'];
        $soil = $row['soil'];
        $symptoms = $row['symptoms'];
        $others = $row['others'];
        $date = $row['date'];
    
        // Retrieve the class and confidence from the 'ia_result' table
        $iaResultSql = "SELECT class, confidence FROM ia_result WHERE code = '$code'";
        $iaResultResult = $conn->query($iaResultSql);
    
        // Check if any rows are returned from the 'ia_result' table
        if ($iaResultResult->num_rows > 0) {
            // Fetch the class and confidence values
            $iaResultRow = $iaResultResult->fetch_assoc();
            $class = $iaResultRow['class'];
            $confidence = $iaResultRow['confidence'];
        } else {
            $class = "No Data Available";
            $confidence = "No Data Available";
        }
    
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
        } else {
            // No images found for the specified code
            echo "No images found!";
        }
    } else {
        echo "No rows found.";
    }
    
    // Close the connection
    $conn->close();
?>


<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
    
    .sectionb {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: url(images/coconut-bg.jpg) no-repeat;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        padding: 100px 200px; /* Add left and right padding */
        }

    .wrapper-settings {
        width: 100%;
        max-width: 1500px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    ::selection {
        background: #6d50e2;
        color: #fff;
    }

    .container {
        background: rgb(238, 230, 230);
        min-width: 500px;
        max-width: 1750px;
        width: 100%;
        height: 100%;
        padding: 10px;
        margin: 20px;
        border-radius: 20px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        text-decoration-line: none;
    }

    .container .nav {
        text-decoration: none;
        text-decoration-line: none;
        margin: 20px;
    }

    .container .nav .setting {
        padding: 5px;
        margin: 5px;
        opacity: 0.25;
        text-decoration: none;
        text-decoration-line: none;
        color: #99454c;
    }

    .container .nav .setting:hover {
        padding: 5px;
        margin: 5px;
        opacity: 1;
        text-decoration: none;
        text-decoration-line: underline;
        color: #99454c;
    }

    .container .nav .active {
        padding: 5px;
        margin: 5px;
        opacity: 1;
        text-decoration-line: underline;
        color: #99454c;
    }

    .container .topic {
        font-size: 24px;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .container .topic a {
        padding: 5px;
    }

    .content {
        display: flex;
        height: 100%;
    }

    .content .list {
        display: flex;
        flex-direction: column;
        width: 25%;
        height: 100%;
        margin-right: 10px;
        position: relative;
    }

    .content .list label {
        height: 60px;
        font-size: 16px;
        font-weight: 300;
        line-height:50px;
        cursor: pointer;
        padding-left: 10px;
        transition: all 0.5s ease;
        color: black;
        z-index: 1;
    }
    
    .content .list .title{
        font-size:20px;
    }

    #view:checked ~ .list label.view,
    #create:checked ~ .list label.create,
    #edit:checked ~ .list label.edit,
    #edit1:checked ~ .list label.edit1 {
        color: #fff;
    }
    
    /*#view:checked ~ .list label.view .title {*/
    /*  color: #fff;*/
    /*}*/

    .content .slider {
        position: absolute;
        left: 0;
        top: 0;
        height: 60px;
        width: 100%;
        border-radius: 12px;
        background: #c9717a;
        transition: all 0.4s ease;
    }

    #view:checked ~ .list .slider {
        top: 0px;
    }

    #edit:checked ~ .list .slider {
        top: 60px;
    }

    #edit1:checked ~ .list .slider {
        top: 120px;
    }


    .content .text-content {
        width: 100%;
        height: 100%;
    }

    .content .text {
        display: none;
        width: fit-content;
        height: 200%;
        overflow-y: auto;
    }

    .content .text .title {
        font-size: 18px;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .content .text p {
        text-align: justify;
    }

    .content .text-content .view,
    .content .text-content .edit,
    .content .text-content .edit1,
    .content .text-content .generate {
        display: none;
    }

    #view:checked ~ .text-content .view,
    #create:checked ~ .text-content .create,
    #edit:checked ~ .text-content .edit,
    #edit1:checked ~ .text-content .edit1,
    #create:checked ~ .text-content .generate {
        display: block;
        color:black;
    }
    

    .content input[type="radio"] {
        display: none;
    }

    .wrap-qr {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: space-around;
    }

    .input-field {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 3px 3px rgba(0, 0, 0, 0.5);
        width:100%;
        height: auto;
        flex-shrink: 0;
        text-align: left;
        padding: 20px;
    }

    .input-field-left {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 3px 3px rgba(0, 0, 0, 0.5);
        width: 100%;
        height: 100%;
        flex-shrink: 0;
        text-align: left;
        padding: 20px;
    }

    .qr-field {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 3px 3px rgba(0, 0, 0, 0.5);
        width: 45%;
        height: auto;
        flex-shrink: 0;
        text-align: center;
    }

    form {
        position: relative;
        padding: 20px;
        width:100%;
        margin-right: 10px;
    }

    form .form {
        position: absolute;
        transition: 0.3s ease;
        margin: 10px;
    }

    form .form.second {
        opacity: 0;
        pointer-events: none;
        transform: translateX(100%);
    }

    form.secActive .form.second {
        opacity: 1;
        pointer-events: auto;
        transform: translateX(0);
    }

    form.secActive .form.first {
        opacity: 0;
        pointer-events: none;
        transform: translateX(-100%);
    }

    form .title {
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
        font-weight: 500;
        margin: 6px 0;
        color: #333;
    }

    .form-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 45px;
        width: 100%;
        border: none;
        outline: none;
        color: #fff;
        border-radius: 5px;
        margin: 25px 0;
        background-color: #c5626d;
        transition: all 0.3s linear;
        cursor: pointer;
    }

    .form-btn:hover {
        background: #fbd0d9;
        color:#99454c;
        border-style:solid;
        border-width:3px;
        border-color:#99454c;
        box-shadow: 0 3px 3px rgba(0,0,0,.5);
        transform: scale(1.1);
    }
    

    form .btnText {
        font-size: 14px;
        font-weight: 400;
    }

    form button:hover {
        background: #fbd0d9;
        color: #802a31;
        border-style: solid;
        border-width: 2px;
        border-color: #802a31;
        box-shadow: 0 3px 3px rgba(0, 0, 0, 0.5);
    }

    form .input-qr input,
    select {
        outline: none;
        font-size: 14px;
        font-weight:500;
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }

    form .input-qr textarea {
        outline: none;
        font-size: 14px;
        font-weight: 300;
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-bottom: 10px;
        resize: vertical;
        min-height: 100px;
    }

    form .input-qr label {
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
        font-weight: 500;
        color: #333;
    }
    
    form .input-row {
        display: flex;
        justify-content: space-between;
    }

    form .input-row input[type="text"],
    form .input-row input[type="number"] {
        flex: 1;
    }
    
    form .input-row input[type="radio"] {
        display:block;
    }
    
    
    form .input-row label {
        white-space: nowrap; /* Prevent label text from wrapping */
        align-self:center;
        margin-right:5px;
    }

    .qr-code {
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
        padding: 10px;
        text-align: center;
    }

    .qr-code img {
        width: 200px;
        height: 200px;
        object-fit: contain;
    }

    .qr-code p {
        font-size: 14px;
        font-weight: 300;
        margin-top: 10px;
    }

    .qr-code a {
        text-decoration: none;
        color: #99454c;
        font-weight: 500;
    }

    .qr-code a:hover {
        text-decoration: underline;
    }
    
    /* CSS styles for the modal */
        .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        }
        
        .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        }
        
        .close:hover,
        .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
        }
        
        .image-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .image-container img {
            max-width: 300px;
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

    @media (max-width: 1200px) {
        /* Styles for screen widths up to 1200px */
        form .fields .input-field {
            width: 80%;
        }
        
        /*.input-field{*/
        /*    width:100%;*/
        /*}*/
        
        .content .list {
            width: 15%;
            }
        
        .content .list .title{
            display:none;
        }

        .list .icon {
            font-size: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
        }
        
        .modal-content {
            margin: 30% auto;
            width: 60%;
        }
    }

</style>


<section class="sectionb">
    <?php
      // Check if the code parameter is present in the URL
      if (isset($_GET['code'])) {
        $submittedCode = $_GET['code'];        
      }
    ?>
    
    <div class="wrapper-settings"><!--wrapper setting-->
        <div class="container">
            <div class="topic">
                <i class="fa-solid fa-qrcode"></i>&nbsp;<?php echo $submittedCode ?>
                <button class="form-btn" style="width: 10%; margin-top: 10px; margin-right: 10px; border-radius: 20px; float: right;" onclick="window.location.href='import_image.php?code=<?php echo urlencode($submittedCode); ?>'" title="Import Images">
                  <i class="fas fa-upload"></i>
                </button>
                <button class="form-btn" style="width: 10%; margin-top: 10px; margin-right: 10px; border-radius: 20px; float: right;" onclick="window.location.href='camera.php?code=<?php echo urlencode($submittedCode); ?>'" title="Capture Images">
                  <i class="fas fa-camera"></i>
                </button>
                <!--<button class="form-btn" style="width: 10%; margin-top: 10px; margin-right: 10px; border-radius: 20px; float: right;" onclick="window.location.href='view_images.php?code=<?php echo urlencode($submittedCode); ?>'" title="View Images">-->
                <!--  <i class="fas fa-eye"></i>-->
                <!--</button>-->
                
            </div>
            
            <div class="nav">
                <a href="homepage.php?code=<?php echo urlencode($submittedCode); ?>" class="setting active">QR Details</a>
            </div>
            
            <div class="content">
                <input type="radio" name="slider" checked id="view">
                <input type="radio" name="slider" id="edit">
                <input type="radio" name="slider" id="edit1">
                
                <div class="list" style="max-width: 500px;">
                    <label for="view" class="view" data-bs-toggle="tooltip" data-bs-placement="right" title="View Details">
                        <span class="icon"><i class="fas fa-info-circle"></i></span>
                        <span class="title">View</span>
                    </label>
                
                    <input type="radio" id="edit" name="slider">
                
                    <label for="edit" class="edit" data-bs-toggle="tooltip" data-bs-placement="right" title="Edit Details">
                        <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
                        <span class="title">Edit</span>
                    </label>
                    
                    <input type="radio" id="edit1" name="slider">
                    
                    <label for="edit1" class="edit1" data-bs-toggle="tooltip" data-bs-placement="right" title="View Images">
                        <span class="icon"><i class="fa-solid fa-eye"></i></span>
                        <span class="title">Images</span>
                    </label>
                
                    <div class="slider"></div>
                </div>

                <div class="text-content" style="display: flex; justify-content: center; align-items: center;"><!--start of text-content-->
                    <div class="view"><!--start of view list content-->
                        <div class="wrap-qr"><!--start of wrap-qr-->
                            <div class="input-field">
                                <div class="card-body">
                                    <form method="POST">
                                        <h2 style="font-weight:600; font-size:22px; text-align:left; color:black; margin-bottom:20px; text-decoration: underline">Identification and Location</h2>
                                        
                                        <div class="input-qr">
                                            <label style = "font-weight:bold">Species Identification and Classification:</label>
                                            <input type="text" class="form-control" name="identification" value="<?php echo htmlspecialchars($identification); ?>" style = "font-weight:bold" readonly>
                                        </div>
                                        
                                        <div class="input-qr">
                                            <label style = "font-weight:bold">Geographic Location:</label>
                                            <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Latitude" name="latitude" value="<?php echo $latitude !== '' ? htmlspecialchars("Latitude: " .$latitude) : "Latitude: ".'No Data Available'; ?>" readonly>
                                            </div>
                                            <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Longitude" name="longitude" value="<?php echo $longitude !== '' ? htmlspecialchars("Longitude: " .$longitude) : "Longitude: " .'No Data Available'; ?>" readonly>
                                            </div>    
                                        </div>
                                        
                                        <h2 style="font-weight:600; font-size:22px; text-align:left; color:black; margin-bottom:20px; text-decoration: underline">Growth and Characteristics</h2>
                                        
                                        <div class="input-qr">
                                            <label style = "font-weight:bold">Physical Characteristics:</label>
                                            <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Height (in m)" name="height" value="<?php echo $height !== '' ? htmlspecialchars("Height: ".$height).' m' : "Height: ".'No Data Available'; ?>" readonly>
                                            </div>
                                            <div class="input-row">  
                                                <input class="form-control" type="text" placeholder="Circumference (in m)" name="diameter" value="<?php echo $diameter !== '' ? htmlspecialchars("Circumference: ".$diameter).' m' : "Diameter: ".'No Data Available'; ?>" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="input-qr">
                                            <label style = "font-weight:bold">Age and Growth Rate:</label>
                                            
                                            <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Age in (year/s)" name="age" value="<?php echo $age !== '' ? htmlspecialchars("Age: ".$age). ' yr/s' : "Age: ".'No Data Available'; ?>" readonly>
                                            </div>
                                            
                                            <div class="input-row">    
                                                <input class="form-control" type="text" placeholder="Growth Rate (in %)" name="growth-rate" value="<?php echo $growthRate !== '' ? htmlspecialchars("Growth Rate: ".$growthRate). ' %': "Growth Rate: ".'No Data Available'; ?>" readonly>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="input-qr">
                                            <label style = "font-weight:bold">Reproductive Information:</label>
                                            
                                            <div class="input-row">
                                                <input class="form-control" type="text" name="flowering" value="<?php echo $flowering !== '' ? htmlspecialchars("Flowering: ".$flowering) : "Flowering: ".'No Data Available'; ?>" readonly>
                                            </div>
                                            <div class="input-row">
                                                <input class="form-control" type="text" name="seedProduction" value="<?php echo $seedProduction !== '' ? htmlspecialchars("Seed Production: ".$seedProduction) : "Seed Production: ".'No Data Available'; ?>" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="input-qr">
                                            <label style = "font-weight:bold">Environmental Factors:</label>
                                            <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Light" name="light" value="<?php echo $light !== '' ? htmlspecialchars("Light: ".$light) : "Light: ".'No Data Available'; ?>" readonly>
                                            </div>
                                            <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Soil Type" name="soil" value="<?php echo $soil !== '' ? htmlspecialchars("Soil: ".$soil) : "Soil: ".'No Data Available'; ?>" readonly>
                                            </div>
                                        </div>
                                        
                                        <h2 style="font-weight:600; font-size:22px; text-align:left; color:black; margin-bottom:20px; text-decoration: underline">Health and Pest Management</h2>
                                           
                                        <div class="input-qr">
                                            <label style = "font-weight:bold">Health Assessment:</label>
                                            <div class="input-row">
                                                <input class="form-control" type="text" name="symptoms" value="<?php echo $symptoms !== '' ? htmlspecialchars("Status: ".$symptoms) : "Status: ".'No Data Available'; ?>" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="input-qr">
                                            <label style="font-weight:bold">Pest and Disease Information:
                                                <a href="view_images.php?code=<?php echo urlencode($submittedCode); ?>" class="setting active" style="color:#99454c; float:right; margin-right:5px;" data-toggle="tooltip" data-placement="top" title="View Images">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </label>
                                            
                                            <div class="input-row">
                                                <input class="form-control" type="text" name="class" value="<?php echo $class !== '' ? htmlspecialchars("Class: ".$class) : "Class: ".'No Data Available'; ?>" readonly>
                                            </div>

                                            <div class="input-row">
                                                <input class="form-control" type="text" name="confidence" value="<?php echo $confidence !== '' ? htmlspecialchars("Level: ".$confidence) : "Level: ".'No Data Available'; ?>" readonly>
                                            </div>
                                        </div>

                                        
                                        <h2 style="font-weight:600; font-size:20px; text-align:left; color:black; margin-bottom:20px;">Other Information:</h2>
                                        <div class="input-qr">
                                            <div class="input-row">
                                                <p>
                                                    <?php if ($date !== '0000-00-00 00:00:00'): ?>
                                                        DATE: <?php $formattedDate = date("F d, Y (H:i:s)", strtotime($date)); echo $formattedDate; ?><br>
                                                        NOTE: <?php echo $others; ?>
                                                    <?php endif; ?>
                                                </p>


                                            </div>
                                        </div>

                                    </form>
                                </div>  
                            </div>
                        </div><!--end of wrap-qr-->
                    </div><!--end of view text--> 
                    
                    <div class="edit">
                        <!--start of user list content-->
                        <div class="wrap-qr">
                            <!--start of wrap-qr-->
                            <div class="input-field">
                                <div class="card-header">
                                    <h2 style="font-weight:700; text-align:center; color:black;">Edit Coconut Information</h2>
                                </div>
                        
                                <div class="card-body">
                                    <form method="POST">
                                        <h2 style="font-weight:600; font-size:22px; text-align:left; color:black; margin-bottom:20px; text-decoration: underline">Identification and Location</h2>
                                        <div class="input-qr">
                                            <label>Species Identification and Classification:</label>
                                            <select class="form-control" name="identification">
                                            <option value="Cocos nucifera (Coconut)">Cocos nucifera (Coconut)</option>
                                            <option value="Others">Others</option>
                                            </select>
                                        </div>
                                
                                        <div class="input-qr">
                                            <label>Geographic Location:</label>
                                            <div class="input-row">
                                                <input class="form-control" type="number" step="any" placeholder="Latitude" name="latitude" value="<?php echo $latitude; ?>">
                                                <input class="form-control" type="number" step="any" placeholder="Longitude" name="longitude" value="<?php echo $longitude; ?>">
                                            </div>
                                        </div>
                                
                                        <h2 style="font-weight:600; font-size:22px; text-align:left; color:black; margin-bottom:20px; text-decoration: underline">Growth and Characteristics</h2>
                                
                                        <div class="input-qr">
                                            <label>Physical Characteristics:</label>
                                            <div class="input-row">
                                                <input class="form-control" type="number" step="any" placeholder="Height (in m)" name="height" value="<?php echo $height; ?>">
                                                <input class="form-control" type="number" step="any" placeholder="Circumference (in m)" name="diameter" value="<?php echo $diameter; ?>">
                                            </div>
                                        </div>
                                
                                        <div class="input-qr">
                                            <label>Age and Growth Rate:</label>
                                            <div class="input-qr">
                                                <div class="input-row">
                                                    <input class="form-control" type="number" placeholder="Age in (year/s)" name="age" value="<?php echo $age; ?>">
                                                    <!--<input class="form-control" type="number" placeholder="Growth Rate (in %)" name="growth-rate" value="<?php echo $growthRate; ?>">-->
                                                </div>
                                            </div>
                                        </div>
                                
                                        <div class="input-qr">
                                            <label>Reproductive Information:</label>
                                            <div class="input-row">
                                                <label>Flowering:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                <input type="radio" name="flowering" id="flowering-yes" value="Yes" <?php if ($flowering === 'Yes') echo 'checked'; ?>>
                                                <label for="flowering-yes">Yes</label>
                                                <input type="radio" name="flowering" id="flowering-no" value="No" <?php if ($flowering === 'No') echo 'checked'; ?>>
                                                <label for="flowering-no">No</label>
                                            </div>
                                            <div class="input-row">
                                                <label>Seed Production:</label>
                                                <input type="radio" name="seedProduction" id="seeding-yes" value="Yes" <?php if ($seedProduction === 'Yes') echo 'checked'; ?>>
                                                <label for="seeding-yes">Yes</label>
                                                <input type="radio" name="seedProduction" id="seeding-no" value="No" <?php if ($seedProduction === 'No') echo 'checked'; ?>>
                                                <label for="seeding-no">No</label>
                                            </div>
                                        </div>
                                
                                        <div class="input-qr">
                                            <label>Environmental Factors:</label>
                                            <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Light" name="light" value="<?php echo $light; ?>">
                                                </div>
                                                <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Soil Type" name="soil" value="<?php echo $soil; ?>">
                                            </div>
                                        </div>
                                
                                        <h2 style="font-weight:600; font-size:22px; text-align:left; color:black; margin-bottom:20px; text-decoration: underline">Health and Pest Management</h2>
                                
                                        <div class="input-qr">
                                            <label>Health Assessment:</label>
                                            <div class="input-row">
                                            <input class="form-control" type="text" placeholder="Symptoms" name="symptoms" value="<?php echo $symptoms; ?>">
                                            </div>
                                        </div>
                                
                                        <!--<div class="input-qr">-->
                                        <!--  <label>Pest and Disease Information:</label>-->
                                        <!--  <div class="input-row">-->
                                        <!--    <input class="form-control" type="text" placeholder="Pest" name="pest" value="<?php echo $pest; ?>">-->
                                        <!--  </div>-->
                                        <!--  <div class="input-row">-->
                                        <!--    <input class="form-control" type="text" placeholder="Disease" name="disease2" value="<?php echo $disease2; ?>">-->
                                        <!--  </div>-->
                                        <!--</div>-->
                                
                                        <h2 style="font-weight:600; font-size:20px; text-align:left; color:black; margin-bottom:20px;">Other Information (Optional):</h2>
                                        
                                        <div class="input-qr">
                                            <div id="character-count"></div>
                                            <div class="input-row">
                                                <textarea class="form-control" placeholder="Others" name="others" rows="2" maxlength="150" oninput="showCharactersLeft(this)"><?php echo $others; ?></textarea>
                                            </div>
                                        </div>
                                    
                                        <button class="form-btn" type="submit" name="save">Update</button>
                                        <button class="form-btn cancel-btn" type="button">Cancel</button>
                                        
                                        <script>
                                            // Get a reference to the cancel button
                                            var cancelButton = document.querySelector('.cancel-btn');
                                        
                                            // Add event listener to the cancel button
                                            cancelButton.addEventListener('click', function() {
                                                // Reload the window
                                                location.reload();
                                            });
                                        </script>
            
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--end of wrap-qr-->
                    </div>
                    <!--end of edit text-->
                    
                    <div class="edit1">
                        <div class="wrap-qr">
                            <!--start of wrap-qr-->
                            <div class="input-field">
                                <div class="card-header">
                                    <h2 style="font-weight:700; text-align:center; color:black;">View Images</h2>
                                </div>
                        
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data">
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
                                
                                            <!--<div class="button-container">-->
                                            <!--    <button class="form-btn" type="submit" name="upload" onclick="window.location.href='import_image.php?code=<?php echo urlencode($submittedCode); ?>'" title="Import Images">-->
                                            <!--        <i class="fas fa-upload"></i>&nbsp;Upload and Predict-->
                                            <!--    </button>-->
                                            <!--</div>-->
                                        <?php endif; ?>
                                
                                        <?php if (!$rootImage && !$trunkImage && !$leavesImage): ?>
                                            <p>No images available.</p>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--end of edit1 text-->
                    </div><!-- text content-->
                </div>
            </div><!--content-->
        </div><!--container-->
    </div><!--wrapper settings-->
    
</section>

  
<script>
    const form = document.querySelector("form"),
    nextBtn = form.querySelector(".nextBtn"),
    backBtn = form.querySelector(".backBtn"),
    allInput = form.querySelectorAll(".first input");

    nextBtn.addEventListener("click", ()=> {
        allInput.forEach(input => {
            if(input.value != ""){
                form.classList.add('secActive');
            }else{
                form.classList.remove('secActive');
            }
        })
    })

    backBtn.addEventListener("click", () => form.classList.remove('secActive'));

</script>

<script src="https://kit.fontawesome.com/49bf074e28.js" crossorigin="anonymous"></script>

<script>//modal script
      document.getElementById('button').addEventListener("click", function() {
      document.querySelector('.edit-bg-modal').style.display = "flex";
    });

    document.querySelector('.close').addEventListener("click", function() {
      document.querySelector('.edit-bg-modal').style.display = "none";
    });
</script>

<script>
    document.getElementById('manageCoconut').addEventListener('click', function() {
    // Remove 'active' class from all elements
    var elements = document.getElementsByClassName('setting');
    for (var i = 0; i < elements.length; i++) {
    elements[i].classList.remove('active');
    }
    
    // Add 'active' class to the clicked element
    this.classList.add('active');
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>