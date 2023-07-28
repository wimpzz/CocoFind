<?php
    include 'includes/navbar.php';
    include 'includes/db.php';
    
    session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Login</title>
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
        background: url(images/coconut-bg.jpg) no-repeat;
        background-size: cover;
        background-position: center center;
        background-attachment: fixed;
    }

    .container {
        align-items: center;
        justify-content: center;
        background-color: white;
        max-width: 1000px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 40px;
        width:600px;
        height:400px;
    }

    h1,h2 {
        text-align: center;
    }
    
    span {
        font-weight: bold;
        color: #c5626d
        cursor: pointer;
    }


    .form-group {
        margin-top: 50px;
    }
    
    .form-group p{
        text-align: center;
    }

    .form-group label {
        display: block;
        font-weight: bold;
    }
    
    
    .form-group input {
        display: block;
        width: 100%;
        padding: 10px 15px;
        font-size: 17px;
        margin: 8px 0;
        background: #eee;
        border-radius: 100px;
        border-color: transparent;
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
        border-radius: 125px;
        cursor: pointer;
        margin-top: 30px;
    }
    
    .form-group input[type="submit"]:hover{
        background: #fbd0d9;
        color:#99454c;
        border-style:solid;
        border-width:3px;
        border-color:#99454c;
        box-shadow: 0 3px 3px rgba(0,0,0,.5);
    }
    
    @media (max-width: 1200px) {
        /* Styles for screen widths up to 1200px */
        .container {
            width: 80%;
        }
    }

</style>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the form is submitted
        if (isset($_POST['submit'])) {
            // Retrieve the submitted username and password
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            // Perform your database query to check if the username and password exist in the admin table
            // Assuming $conn is your database connection
            
            // Replace the below query with your actual query to check the admin credentials
            $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
            $result = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($result) > 0) {
                // If the username and password exist in the admin table, navigate to admin/index.php
                header('Location: ./admin/index.php');
                exit();
            } else {
                // If the username and password do not match, display an error message
                $error = "Invalid username or password";
            }
        }
    }
?>

<body>
    <div class="container">
        <h1>Login As Admin</h1>
        <h2>Please enter your credentials</h2>
        <form action="login.php" method="post">
            <div class="form-group">
                <?php
                if (isset($error)) {
                    echo '<span class="error-msg">' . $error . '</span>';
                }
                ?>

                <input type="text" name="username" required placeholder="Username">
                <input type="password" name="password" required placeholder="Password">
                <input type="submit" name="submit" value="Login">

                <p>Don't have an account?
                    <!-- Trigger/Open The Modal -->
                    <span class="reg" id="register" onclick="window.location='admin_register.php'">Sign Up</span>
                </p>
            </div>
        </form>
    </div>
</body>

</html>
