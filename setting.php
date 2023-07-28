<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Generate QR Code</title>
    <link rel="shortcut icon" type="image/png" href="images/pup-logo.png">
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" href="style.css">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>
    
<?php
include_once 'includes/navbar.php';
include 'includes/db.php';

// Check if the form is submitted
if (isset($_POST['save'])) {
    $identification = $_POST['identification'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $height = $_POST['height'];
    $diameter = $_POST['diameter'];
    $age = $_POST['age'];
    $growthRate = $_POST['growth-rate'];
    $floweringValue = isset($_POST['flowering']) ? $_POST['flowering'] : '';
    $seedProductionValue = isset($_POST['seedProduction']) ? $_POST['seedProduction'] : '';
    $light = $_POST['light'];
    $soil = $_POST['soil'];
    $others = $_POST['others'];

    // Assuming you have already established a database connection, replace 'your_table_name' with the actual name of the table
    $stmt = $conn->prepare("INSERT INTO your_table_name (
        identification,
        latitude,
        longitude,
        height,
        diameter,
        age,
        growth_rate,
        flowering,
        seed_production,
        light,
        soil,
        others
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        // An error occurred while preparing the statement
        echo "Error: " . $conn->error;
    } else {
        // Bind parameters to the prepared statement
        $stmt->bind_param(
            "ssssssssssss",
            $identification,
            $latitude,
            $longitude,
            $height,
            $diameter,
            $age,
            $growthRate,
            $floweringValue,
            $seedProductionValue,
            $light,
            $soil,
            $others
        );

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Data saved successfully
            echo '<script>alert("Success!");</script>';
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
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
    width: 15%;
    height: 100%;
    margin-right: 10px;
    position: relative;
  }

  .content .list label {
    height: 60px;
    font-size: 18px;
    font-weight: 300;
    line-height:50px;
    cursor: pointer;
    padding-left: 10px;
    transition: all 0.5s ease;
    color: #333;
    z-index: 1;
  }
  

  #view:checked ~ .list label.view,
  #create:checked ~ .list label.create,
  #edit:checked ~ .list label.edit,
  #create:checked ~ .list label.generate {
    color: #fff;
  }
  
  #view:checked ~ .list label.view .title {
    color: #fff;
  }

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

  #view:checked ~ .list .slider,
  #create:checked ~ .list .slider,
  #edit:checked ~ .list .slider,
  #create:checked ~ .list .slider {
    top: 0px;
  }

  #edit:checked ~ .list .slider,
  #create:checked ~ .list .slider {
    top: 60px;
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
  .content .text-content .create,
  .content .text-content .edit,
  .content .text-content .generate {
    display: none;
  }

  #view:checked ~ .text-content .view,
  #create:checked ~ .text-content .create,
  #edit:checked ~ .text-content .edit,
  #create:checked ~ .text-content .generate {
    display: block;
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
    border-radius: 10px;
    box-shadow: 0 3px 3px rgba(0, 0, 0, 0.5);
    width: 45%;
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
    width: 60%;
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
    background-color: #99454c;
    transition: all 0.3s linear;
    cursor: pointer;
  }

  .form-btn:hover {
    background: #fbd0d9;
    color: #99454c;
    border-style: solid;
    border-width: 3px;
    border-color: #99454c;
    box-shadow: 0 3px 3px rgba(0, 0, 0, 0.5);
  }
  
  .form-btn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
    box-shadow: none;
    border: none;
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



@media (min-width: 400px) and (max-width: 800px) {
  /* Styles for screen widths between 600px and 800px */
  form {
    overflow-y: scroll;
  }

  form::-webkit-scrollbar {
    display: none;
  }

  form .fields .input-field {
    width: calc(100% / 2 - 15px);
  }
  content.view .title {
    display: none;
  }
  
  #view:checked ~ .list label.view .title {
    display:none;
  }
  
  .modal-content {
    margin: 50% auto;
    width: 60%;
  }

}

@media (max-width: 1200px) {
  /* Styles for screen widths up to 1200px */
  form .fields .input-field {
    width: 100%;
  }

  .list .icon {
    font-size: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
  }

  .list .slider {
    height: 60px;
  }
  
  #view:checked ~ .list label.view .title {
    display:none;
  }
  
  .modal-content {
    margin: 30% auto;
    width: 60%;
  }
}

</style>


<section class="sectionb">
    <div class="wrapper-settings"><!--wrapper setting-->
        <div class="container">
            <div class="topic">
                <i class="fa-solid fa-qrcode"></i>&nbsp;Generate QR CODE
            </div>
            <div class="nav">
                <a href="setting.php" class="setting active">QR Settings</a>
                <!--<a href="#" class="setting" id="manageCoconut">Manage Coconut</a>-->
            </div>
        
            <div class="content">
                <input type="radio" name="slider" checked id="view">
                <input type="radio" name="slider" id="edit">

                <div class="text-content" style="display: flex; justify-content: center; align-items: center;"><!--start of text-content-->
                    <div class="view"><!--start of user list content-->
                        <div class="wrap-qr"><!--start of wrap-qr-->
                            <div class="input-field" style="width:100%;">
                                <div class="card-header">
                                    <h2 style="font-weight:700; text-align:center; color:black;">Enter Coconut Information</h2>
                                </div>
                              
                                <div class="card-body">
                                    <form method="POST">
                                        <h2 style="font-weight:600; font-size:22px; text-align:left; color:black; margin-bottom:20px; text-decoration: underline">Identification and Location</h2>
                                        
                                        <div class="input-qr">
                                            <label>Species Identification and Classification:<span style = "color:red">*</span></label>
                                            <select class="form-control" name="identification" required onchange="toggleButton(this)">
                                                <option value="" selected disabled>Please Select Species Identification...</option>
                                                <option value="Cocos nucifera (Coconut)">Cocos nucifera (Coconut)</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            
                                            <script>
                                                function toggleButton(selectElement) {
                                                    var generateBtn = document.getElementById("generateBtn");
                                                    generateBtn.disabled = (selectElement.value === "");
                                                }
                                            </script>
                                        </div>
                                        
                                        <div class="input-qr">
                                            <label>Geographic Location:</label>
                                            <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Latitude" name="latitude">
                                                <input class="form-control" type="text" placeholder="Longitude" name="longitude">
                                            </div>
                                        </div>
                                        
                                        <h2 style="font-weight:600; font-size:22px; text-align:left; color:black; margin-bottom:20px; text-decoration: underline">Growth and Characteristics</h2>
            
                                        <div class="input-qr">
                                            <label>Physical Characteristics:</label>
                                            <div class="input-row">
                                                <input class="form-control" type="number" placeholder="Height (in m)" name="height">
                                                <input class="form-control" type="number" placeholder="Diameter (in m)" name="diameter">
                                            </div>
                                        </div>
                                        
                                        <div class="input-qr">
                                            <label>Age and Growth Rate:</label>
                                            <div class="input-qr">
                                              <div class="input-row">
                                                <input class="form-control" type="number" placeholder="Age in (year/s)" name="age">
                                                <!--<input class="form-control" type="number" placeholder="Growth Rate (in %)" name="growth-rate">-->
                                              </div>
                                            </div>
                                        </div>
                
                                        <div class="input-qr">
                                            <label>Reproductive Information:</label>
                                            <div class="input-row">
                                                <label>Flowering:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                <input type="radio" name="flowering" id="flowering-yes" value="Yes">
                                                <label for="flowering-yes">Yes</label>
                                                <input type="radio" name="flowering" id="flowering-no" value="No">
                                                <label for="flowering-no">No</label>
                                            </div>
                                            <div class="input-row">
                                                <label>Seed Production:</label>
                                                <input type="radio" name="seedProduction" id="seeding-yes" value="Yes">
                                                <label for="seeding-yes">Yes</label>
                                                <input type="radio" name="seedProduction" id="seeding-no" value="No">
                                                <label for="seeding-no">No</label>
                                            </div>
                                        </div>
            
                                        <div class="input-qr">
                                            <label>Environmental Factors:</label>
                                            <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Light" name="light">
                                            </div>
                                            <div class="input-row">
                                                <input class="form-control" type="text" placeholder="Soil Type" name="soil">
                                            </div>
                                        </div>
                                        
                                        <h2 style="font-weight:600; font-size:20px; text-align:left; color:black; margin-bottom:20px;">Other Information (Optional):</h2>
                                            <div class="input-qr">
                                              <div id="character-count"></div>
                                              <div class="input-row">
                                                <textarea  class="form-control" type="text" placeholder="Others" name="others" rows="2" maxlength="150" oninput="showCharactersLeft(this)"></textarea>
                                              </div>
                                            </div>
                                            
                                             <script>
                                                function showCharactersLeft(input) {
                                                    var maxLength = input.maxLength;
                                                    var currentLength = input.value.length;
                                                    var charactersLeft = maxLength - currentLength;
                                                    var characterCount = document.getElementById("character-count");
                                                    characterCount.textContent = charactersLeft + "/150 characters left.";
                                                }
                                            </script>
            
                                        <!--<button class = "form-btn" type="submit" name="generate">Generate</button>-->
                                        <button class="form-btn" onclick="openModalAgain()" style="opacity: 0;" id="reopenBtn" disabled>Open Previous QR</button>

                                        <button class="form-btn" type="submit" name="save" id="generateBtn" disabled>Generate QR Code</button>
                                        
                                        
                                        
                                        <div id="myModal" class="modal">
                                            <div class="modal-content">
                                                <span class="close" onclick="closeModal()">&times;</span>
                                                <h2 style="font-weight:600;color:black;">QR Code Result</h2>
                                                <!--<p>This is the content of the modal.</p>-->
                                                <div style="text-align:center;">
                                                    <!-- Modal -->
                                                    <?php
                                                        echo '<h2 style="font-weight:600;color:black;">' .$code_name. '</h2><br>'; 
                                                        echo '<a href="homepage.php?code=' . urlencode($code_name) . '"><img src="' . $filename . '" style="width:200px;height:200px;"/></a>';
                                                    ?>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                        <script>
                                            var modal = document.getElementById('myModal');
                                            var closeButton = document.getElementsByClassName("close")[0];
                                            var reopenButton = document.getElementById('reopenBtn');
                                            var generateButton = document.getElementById('generateBtn');
                                        
                                            // Function to open the modal
                                            function openModal() {
                                                modal.style.display = "block";
                                            }
                                        
                                            // Function to close the modal
                                            function closeModal() {
                                                modal.style.display = "none";
                                            }
                                        
                                            // Trigger the modal opening after form submission
                                            <?php if (isset($_POST['save'])) : ?>
                                            window.onload = function() {
                                                openModal();
                                                reopenButton.style.opacity = 1; // Show the reopen button
                                                reopenButton.disabled = false; // Enable the reopen button
                                            };
                                            <?php endif; ?>
                                        
                                            // Function to reopen the modal
                                            function openModalAgain() {
                                                openModal();
                                            }
                                        
                                            // Disable the reopen button initially
                                            reopenButton.disabled = true;
                                        
                                            // Enable the reopen button when generateBtn is pressed
                                            generateButton.addEventListener('click', function() {
                                                reopenButton.style.opacity = 1; // Show the reopen button
                                                reopenButton.disabled = false;
                                            });
                                        </script>


                                    </form>
                                </div>  
                            </div>
                        </div><!--end of wrap-qr-->
                        
                       
                    </div><!--end of view text--> 
                
                </div><!-- text content-->
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