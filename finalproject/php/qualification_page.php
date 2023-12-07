<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qualification Page</title>
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

if (isset($_GET['qualification'])) {
    $selectedQualification = $_GET['qualification'];
    // get course info and prereqs
    $sql = "SELECT COURSE.COURSE_NAME, COURSE.COURSE_CODE FROM COURSE WHERE COURSE.QUALIFICATION_CODE = '$selectedQualification'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<h2>Course(s) for Qualification: $selectedQualification</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "Course Name: " . $row['COURSE_NAME'] . "<br>";
            // check for prereqs
            $courseCode = $row['COURSE_CODE'];
            $prerequisiteQuery = "SELECT QUALIFICATION_CODE FROM PREREQUISITE WHERE COURSE_CODE = '$courseCode'";
            $prerequisiteResult = $conn->query($prerequisiteQuery);
            if ($prerequisiteResult->num_rows > 0) {
                echo "Prerequisites: ";
                while ($prerequisiteRow = $prerequisiteResult->fetch_assoc()) {
                    echo "<a href='qualification_page.php?qualification=" . $prerequisiteRow['QUALIFICATION_CODE'] . "'>" . $prerequisiteRow['QUALIFICATION_CODE'] . "</a> ";
                }
            } else {
                echo "No Prerequisites";
            }
            // shows sessions
            echo "<br><a href='courses_page.php?course_code=" . $courseCode . "'>View Sessions</a>";
            echo "</li><br>";
        }
        echo "</ul>";
    } else {
        echo "No courses found for the selected qualification.";
    }
}
$conn->close();
?>
</body>
</html>
