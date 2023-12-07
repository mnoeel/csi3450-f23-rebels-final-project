<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "companydb";
$conn = new mysqli($servername, $username, $password, $dbname);

$selectedSessionId = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $candidateId = $_POST["candidate_id"];
    $sessionId = $_POST["session_id"];
    $courseCode = $_POST["course_code"];

    // ENROLLMENT entry
    $enrollmentQuery = "INSERT INTO ENROLLMENT (CANDIDATE_ID, SESSION_ID) VALUES ('$candidateId', '$sessionId')";
    if ($conn->query($enrollmentQuery)) {
        echo "Registration successful for Session $sessionId";
        $selectedSessionId = $sessionId;
        // undo registration
        echo "<form method='post' action='undo_registration.php'>";
        echo "<input type='hidden' name='candidate_id' value='$candidateId'>";
        echo "<input type='hidden' name='session_id' value='$selectedSessionId'>";
        echo "<input type='hidden' name='course_code' value='" . $courseCode . "'>";
        echo "<input type='submit' name='undo' value='Undo'>";
        echo "</form>";
    }
}

if (isset($_GET['session_id'])) {
    $selectedSessionId = $_GET['session_id'];
    $sessionQuery = "SELECT COURSE_CODE FROM SESSION WHERE SESSION_ID = $selectedSessionId";
    $sessionResult = $conn->query($sessionQuery);
    if ($sessionResult) {
        if ($sessionResult->num_rows > 0) {
            $sessionRow = $sessionResult->fetch_assoc();
            // registration form
            echo "<h2>Registration Form:</h2>";
            echo "<form method='post' action='session_registration.php'>";
            echo "CANDIDATE_ID: <input type='text' name='candidate_id' required><br>";
            echo "<input type='hidden' name='session_id' value='" . $selectedSessionId . "'>";
            echo "<input type='hidden' name='course_code' value='" . $sessionRow['COURSE_CODE'] . "'>";
            echo "<input type='submit' value='Register'>";
            echo "</form>";
        } else {
            echo "No session found for the selected ID.";
        }
    }
    $sessionResult->close();
} 

$conn->close();
?>
