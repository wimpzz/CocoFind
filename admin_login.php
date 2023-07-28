<?php
// include 'includes/navbar.php';
include 'includes/db.php';

// Function to sanitize user inputs
function sanitize($input)
{
    global $conn;
    return mysqli_real_escape_string($conn, $input);
}

// Start the session
session_start();

// Check if the admin is already logged in
if (isset($_SESSION['admin_username'])) {
    // Redirect to the admin folder's index.php
    header("Location: admin/index.php");
    exit();
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the entered username and password
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    // Query to check if the username and password exist in the database
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful and if a matching row was found
    if ($result && mysqli_num_rows($result) > 0) {
        // Store the admin username in a session variable
        $_SESSION['admin_username'] = $username;

        // Redirect to the admin folder's index.php
        header("Location: admin/index.php");
        exit();
    } else {
        // Invalid username or password
        $error = "Invalid username or password";
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="shortcut icon" type="image/png" href="">
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
        color:black;
        font-weight:600;
    }

    .error-msg{
        display: block;
        background: #ed969e;
        color:#721c24;
        margin-top: 20px;
        border-radius: 5px;
        font-size: 16px;
        text-align:center;
      }

    .form-group {
        margin-top: 20px;
    }
    
    .form-group p{
        text-align: center;
    }
    
    .form-group p span {
        font-weight: bold;
        color: #99454c;
        cursor: pointer;
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

<body>
    <div class="container">
        <form action="" method="post">
            
                <h1>Login As Admin</h1>
                <h2>Please enter your credentials</h2>
                <?php
                    if (isset($error)) {
                        echo '<div class="error-msg">' . $error . '</div>';
                    }
                ?>
                
                <div class="form-group">
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
