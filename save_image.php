<?php
include 'includes/db.php';
include_once 'includes/navbar.php';

// Set the timezone to Philippine Standard Time (PST)
date_default_timezone_set('Asia/Manila');

// Retrieve the image data and part from the AJAX request
$imageData = $_POST['imageData'];
$part = $_POST['part'];

$currentDateTime = date('Y-m-d H:i:s');
$submittedCode = $_GET['code'];

// Remove the base64 encoding and get the image data
$imageData = str_replace('data:image/png;base64,', '', $imageData);
$imageData = str_replace(' ', '+', $imageData);
$imageData = base64_decode($imageData);

// Generate a unique file name for the image
$fileName = $submittedCode. '_'. $currentDateTime . '_'. $part. '.png';

// Specify the directory where you want to save the images based on the part
$savePath = '';

if ($part === 'Root') {
  $savePath = 'captured_images/'.$submittedCode.'/root/';
} else if ($part === 'Trunk') {
  $savePath = 'captured_images/'.$submittedCode.'/trunk/';
} else if ($part === 'Leaves') {
  $savePath = 'captured_images/'.$submittedCode.'/leaves/';
} else {
  // Handle the case if the part is not recognized
  http_response_code(400);
  echo 'Invalid';
  exit;
}

// Create the directory if it does not exist
if (!is_dir($savePath)) {
  mkdir($savePath, 0777, true);
}

// Save the image file in the specified directory
$file = $savePath . $fileName;
file_put_contents($file, $imageData);

// Determine the column based on the folder name
$column = '';
if ($part === 'Leaves') {
  $column = 'leaves';
} elseif ($part === 'Trunk') {
  $column = 'trunk';
} elseif ($part === 'Root') {
  $column = 'root';
}

if (!empty($column)) {
  // Prepare and execute the SQL statement to insert or update the filename into the table
  $sql = "INSERT INTO coco_images (code, $column) VALUES ('$submittedCode', '$fileName')
          ON DUPLICATE KEY UPDATE $column = '$fileName'";

  if ($conn->query($sql) === TRUE) {
    echo "Image uploaded successfully and filename saved in the database!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Return a success response to the AJAX request
http_response_code(200);
?>
