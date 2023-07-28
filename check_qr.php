<?php
    include 'includes/db.php';
    session_start();

    $qrCode = $_POST['qr_code'];

    // Execute SELECT query to check if QR code is in database
    $sql = "SELECT * FROM coco_db WHERE code = '$qrCode'";
    $result = mysqli_query($conn, $sql);

    // Check if query was successful and if row was returned
    if ($result && mysqli_num_rows($result) > 0) {
        // QR code is in database
        echo "found";
    } else {
        // QR code not found in database
        echo "not found";
    }
?>