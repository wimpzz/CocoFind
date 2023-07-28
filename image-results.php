<?php
include 'includes/navbar.php';
include 'includes/db.php';

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve the columns
$sql = "SELECT code, class, confidence FROM ia_result";
$result = $conn->query($sql);

// Check if any rows were returned
if ($result->num_rows > 0) {
    // Start creating the HTML table
    echo "<table>";
    echo "<tr><th>Code</th><th>Class</th><th>Confidence</th><th>Action</th></tr>";

    // Loop through each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["code"] . "</td>";
        echo "<td>" . $row["class"] . "</td>";
        echo "<td>" . $row["confidence"] . "</td>";
        echo "<td><button type='button' onclick='fillForm(\"" . $row["code"] . "\", \"" . $row["class"] . "\", \"" . number_format($row["confidence"], 2) . "\")'>Submit</button></td>";
        echo "</tr>";
    }

    // Close the HTML table
    echo "</table>";
} else {
    echo "No results found.";
}

// Close the database connection
$conn->close();
?>

<form action="https://sheetdb.io/api/v1/90somk0ibfn61" method="post" id="sheetdb-form">
    <input type="text" name="Code" id="Code">
    <input type="text" name="Class" id="Class">
    <input type="text" name="Confidence" id="Confidence">
    <button type="submit">Submit</button>
</form>

<script>
function fillForm(code, classValue, confidence) {
    // Set the values in the form fields
    document.getElementById("Code").value = code;
    document.getElementById("Class").value = classValue;
    document.getElementById("Confidence").value = confidence;
}

var form = document.getElementById('sheetdb-form');
form.addEventListener("submit", e => {
    e.preventDefault();
    fetch(form.action, {
        method: "POST",
        body: new FormData(document.getElementById("sheetdb-form")),
    }).then(
        response => response.json()
    ).then((html) => {
        alert("Form submitted successfully!");
        window.location.href = 'image-results.php';
    });
});
</script>


