<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        // Retrieve the uploaded image file
        $imageFile = $_FILES['image'];

        // Check if the file is uploaded successfully
        if ($imageFile['error'] === UPLOAD_ERR_OK) {
            // Get the temporary location of the uploaded file
            $tmpFilePath = $imageFile['tmp_name'];

            // Create a cURL file object
            $cFile = new CURLFile($tmpFilePath);

            // Create an array of post data
            $postData = [
                'file' => $cFile
            ];

            // Set the endpoint URL
            $url = 'http://209.172.2.70:8000/predict';

            // Initialize cURL
            $curl = curl_init($url);

            // Set cURL options
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            // Execute the cURL request
            $response = curl_exec($curl);

            // Check for errors
            if ($response === false) {
                echo "Error: " . curl_error($curl);
            } else {
                // Display the response
                echo $response;
            }

            // Close cURL
            curl_close($curl);
        } else {
            echo "Error uploading image: " . $imageFile['error'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Image Upload</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="image" accept="image/*">
        <input type="submit" name="submit" value="Upload and Predict">
    </form>
    
</body>
</html>
