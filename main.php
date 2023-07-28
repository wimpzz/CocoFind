<?php
    include 'includes/db.php';
    include_once 'includes/navbar.php';
    
    // Check if the form is submitted
    if (isset($_POST['submit_code'])) {
        $submittedCode = $_POST['tree_code'];
    
        // Sanitize the submitted code to prevent SQL injection
        $submittedCode = mysqli_real_escape_string($conn, $submittedCode);
    
        // Query to check if the code exists in the table
        $query = "SELECT * FROM coco_db WHERE code = '$submittedCode'";
        $result = mysqli_query($conn, $query);
    
        if (mysqli_num_rows($result) > 0) {
            // Code exists in the table
            $url = "homepage.php?code=" . urlencode($submittedCode);
            echo "<script>window.location.href='$url';</script>";
            exit();
        } else {
            // Code does not exist in the table
            echo "<script>alert('Invalid Tree Code. Please Try Again!');</script>";
        }
    
        // Free the result set
        mysqli_free_result($result);
    }
?>

<!--<!DOCTYPE html>-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style2.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>CocoFind</title>
</head>

<body>
    <section class="main">
        <div>
            <h1>Welcome to CocoFind</h1>
            <!--<div class="moving-text">&#x1F965;Welcome to CocoFind&#x1F965;</div>-->
            <br><br>
            
            <h2>Enter Tree Code</h2>
            <form action="" method="post">
                <div class="input-group">
                    <input type="text" name="tree_code" placeholder="Please enter the code (COCO-QZN-2023F-0000)" required>
                    <button type="submit" name="submit_code">Submit</button>
                </div>
            </form>
            
            <div class="or-container">
                <span class="or-text">OR</span>
            </div>
            
            <div class="button-container">
                <button class="main-btn" onclick="window.location='cctv.php';" title="Scan QR Using Camera">
                    <i class="fas fa-search"></i>
                </button>
                
                <form action="setting.php" method="post">
                    <button class="main-btn" type="submit" title="Generate New QR">
                      <i class="fas fa-qrcode"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>
    
    <!--<section class="services" id="contact">-->
    <!--    <div class="content">-->
    <!--        <div class="card">-->
    <!--            <form action="" method="post">-->
    <!--                <div class="input-group">-->
    <!--                    <input type="text" name="tree_code" placeholder="" required>-->
    <!--                    <button type="submit" name="submit_code">Submit</button>-->
    <!--                </div>-->
    <!--            </form>-->
    <!--        </div>-->
    <!--        <div class="card">-->
    <!--            <div class="icon">-->
    <!--                <i class="fas fa-envelope"></i>-->
    <!--            </div>-->
    <!--            <div class="info">-->
    <!--                <h3>Email</h3>-->
    <!--                <p>business@islam.com</p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->

    <!--<section class="services" id="services">-->
    <!--    <h2 class="title">Services</h2>-->
    <!--    <div class="contentss">-->
    <!--        <div class="card">-->
    <!--            <div class="icon">-->
    <!--                <i class="fas fa-edit"></i>-->
    <!--            </div>-->
    <!--            <div class="info">-->
    <!--                <h3>Script Writing</h3>-->
    <!--                <p>Research and write scripts for different topics that can then be directly recorded for video production</p>-->
    <!--            </div>-->
    <!--        </div>-->
            
    <!--        <div class="card">-->
    <!--            <div class="icon">-->
    <!--                <i class="fas fa-video"></i>-->
    <!--            </div>-->
    <!--            <div class="info">-->
    <!--                <h3>Video Production</h3>-->
    <!--                <p>Record and shoot videos taking into consideration proper settings, followed by production & post-production work</p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="card">-->
    <!--            <div class="icon">-->
    <!--                <i class="fas fa-graduation-cap"></i>-->
    <!--            </div>-->
    <!--            <div class="info">-->
    <!--                <h3>Curriculum Development</h3>-->
    <!--                <p>Develop complete curriculums for various technical topics to produce digital transformative courses</p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->

    <!--<section class="projects" id="projects">-->
    <!--    <h2 class="title">Projects</h2>-->
    <!--    <div class="content">-->
    <!--        <div class="project-card">-->
    <!--            <div class="project-image">-->
                    <!--<img src="images/work1.jpg" />-->
    <!--            </div>-->
    <!--            <div class="project-info">-->
    <!--                <p class="project-category">Script Writing & Video Production</p>-->
    <!--                <strong class="project-title">-->
    <!--                    <span>Dev Books</span>-->
    <!--                    <a href="#" class="more-details">More details</a>-->
    <!--                </strong>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="project-card">-->
    <!--            <div class="project-image">-->
                    <!--<img src="images/work2.jpg" />-->
    <!--            </div>-->
    <!--            <div class="project-info">-->
    <!--                <p class="project-category">Script Writing & Video Production</p>-->
    <!--                <strong class="project-title">-->
    <!--                    <span>Dev Books</span>-->
    <!--                    <a href="#" class="more-details">More details</a>-->
    <!--                </strong>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="project-card">-->
    <!--            <div class="project-image">-->
                    <!--<img src="images/work3.jpg" />-->
    <!--            </div>-->
    <!--            <div class="project-info">-->
    <!--                <p class="project-category">Script Writing & Video Production</p>-->
    <!--                <strong class="project-title">-->
    <!--                    <span>Dev Books</span>-->
    <!--                    <a href="#" class="more-details">More details</a>-->
    <!--                </strong>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="project-card">-->
    <!--            <div class="project-image">-->
                    <!--<img src="images/work4.jpg" />-->
    <!--            </div>-->
    <!--            <div class="project-info">-->
    <!--                <p class="project-category">Script Writing & Video Production</p>-->
    <!--                <strong class="project-title">-->
    <!--                    <span>Dev Books</span>-->
    <!--                    <a href="#" class="more-details">More details</a>-->
    <!--                </strong>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="project-card">-->
    <!--            <div class="project-image">-->
                    <!--<img src="images/work5.jpg" />-->
    <!--            </div>-->
    <!--            <div class="project-info">-->
    <!--                <p class="project-category">Script Writing & Video Production</p>-->
    <!--                <strong class="project-title">-->
    <!--                    <span>Dev Books</span>-->
    <!--                    <a href="#" class="more-details">More details</a>-->
    <!--                </strong>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="project-card">-->
    <!--            <div class="project-image">-->
                    <!--<img src="images/work6.jpg" />-->
    <!--            </div>-->
    <!--            <div class="project-info">-->
    <!--                <p class="project-category">Script Writing & Video Production</p>-->
    <!--                <strong class="project-title">-->
    <!--                    <span>Internet</span>-->
    <!--                    <a href="#" class="more-details">More details</a>-->
    <!--                </strong>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->

    <!--<section class="cards contact" id="contact">-->
    <!--    <h2 class="title">Let's work together</h2>-->
    <!--    <div class="content">-->
    <!--        <div class="card">-->
    <!--            <div class="icon">-->
    <!--                <i class="fas fa-phone"></i>-->
    <!--            </div>-->
    <!--            <div class="info">-->
    <!--                <h3>Phone</h3>-->
    <!--                <p>12345678</p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
    
    
</body>
<?php
    include_once 'includes/footer.php';
?>
</html>


