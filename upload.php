<?php
include 'includes/db.php';
include_once 'includes/navbar.php';

// Check if image data and folder name are provided
if (isset($_POST['image']) && isset($_POST['folder'])) {
  // Get the base64 image data
  $imageData = $_POST['image'];

  $currentDateTime = date('Y-m-d H:i:s');

  $submittedCode = $_GET['code'];

  // Remove the "data:image/png;base64," prefix from the image data
  $imageData = str_replace('data:image/png;base64,', '', $imageData);

  // Decode the base64 image data
  $decodedImage = base64_decode($imageData);

  // Get the folder name
  $folder = $_POST['folder'];

  // Create the directory if it doesn't exist
  if (!is_dir($folder)) {
    mkdir($folder);
  }
  
  $part = '';
  if ($folder === 'captured_leaves') {
    $part = 'leaves';
  } elseif ($folder === 'captured_trunk') {
    $part = 'trunk';
  } elseif ($folder === 'captured_root') {
    $part = 'root';
  }

  // Generate a unique filename for the image
  // $filename = $submittedCode . '(' . $currentDateTime .')' . '.png';
  $filename = $submittedCode.'-'. $currentDateTime. '.png';

  // Set the path for saving the image
  $path = $folder . '/' . $filename;

  // Save the image to the specified folder
  file_put_contents($path, $decodedImage);

  // Determine the column based on the folder name
  $column = '';
  if ($folder === 'captured_leaves') {
    $column = 'leaves';
  } elseif ($folder === 'captured_trunk') {
    $column = 'trunk';
  } elseif ($folder === 'captured_root') {
    $column = 'root';
  }

  if (!empty($column)) {
  // Prepare and execute the SQL statement to insert or update the filename into the table
  $sql = "INSERT INTO coco_images (code, $column) VALUES ('$submittedCode', '$filename')
          ON DUPLICATE KEY UPDATE $column = '$filename'";
  
  if ($conn->query($sql) === TRUE) {
    echo "Image uploaded successfully and filename saved in the database!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
} else {
  echo "Error: Invalid folder name.";
}

// Close the database connection
$conn->close();


} else {
  // Return an error response if image data or folder name is missing
  echo 'Error: Image data or folder name is missing.';
}
?>

