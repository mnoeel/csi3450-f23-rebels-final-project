<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Page</title>
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "companydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['course_code'])) {
    $selectedCourseCode = $_GET['course_code'];
    // get session info
    $sessionQuery = "SELECT SESSION_ID, SESSION_STARTDATE, SESSION_ENDDATE, SESSION_FEE FROM SESSION WHERE COURSE_CODE = '$selectedCourseCode'";
    $sessionResult = $conn->query($sessionQuery);
    if ($sessionResult->num_rows > 0) {
        echo "<h2>Sessions for Course: </h2>";
        echo "<ul>";
        while ($sessionRow = $sessionResult->fetch_assoc()) {
            echo "<li>";
            echo "Session ID: " . $sessionRow['SESSION_ID'] . "<br>";
            echo "Start Date: " . $sessionRow['SESSION_STARTDATE'] . "<br>";
            echo "End Date: " . $sessionRow['SESSION_ENDDATE'] . "<br>";
            echo "Fee: $" . $sessionRow['SESSION_FEE'] . "<br>";
            // registration link
            echo "<a href='session_registration.php?session_id=" . $sessionRow['SESSION_ID'] . "'>Register</a><br>";
            echo "</li><br>";
        }
        echo "</ul>";
    } else {
        echo "No sessions available at this time for the selected course.";
    }
}
$conn->close();
?>

</body>
</html>
