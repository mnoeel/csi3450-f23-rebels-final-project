<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "companydb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    // add candidate
    $insertCandidateQuery = "INSERT INTO CANDIDATE (CANDIDATE_NAME) VALUES ('$name')";
    if ($conn->query($insertCandidateQuery)) {
        $candidateId = $conn->insert_id;
        echo "<h2>Registration Successful</h2>";
        echo "Hello, $name! Your CANDIDATE_ID is: $candidateId. You can use this when enrolling for courses.";
    } 
}

$conn->close();
?>
