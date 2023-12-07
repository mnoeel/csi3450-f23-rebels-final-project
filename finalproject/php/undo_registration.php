<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undo Registration</title>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "companydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["undo"])) {
    $candidateId = $_POST["candidate_id"];
    $sessionId = $_POST["session_id"];
    // delete entry from ENROLLMENT
    $undoQuery = "DELETE FROM ENROLLMENT WHERE CANDIDATE_ID = '$candidateId' AND SESSION_ID = '$sessionId'";
    
    if ($conn->query($undoQuery)) {
        echo "Undo successful for Session $sessionId";
    }
}

$conn->close();
?>

</body>
</html>
