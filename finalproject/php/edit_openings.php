<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "companydb";

$conn = new mysqli($servername, $username, $password, $dbname);
$companyName = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["companyName"])) {
    $companyName = $_POST["companyName"];
    // get openings
    $query = "SELECT * FROM OPENING WHERE COMPANY_NAME = '$companyName'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        echo "<h2>Openings for $companyName:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Opening ID</th><th>Title</th><th>Start Date</th><th>End Date</th><th>Hourly Pay</th><th>Edit Pay</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["OPENING_ID"] . "</td>";
            echo "<td>" . $row["OPENING_TITLE"] . "</td>";
            echo "<td>" . $row["OPENING_START"] . "</td>";
            echo "<td>" . $row["OPENING_END"] . "</td>";
            echo "<td>" . $row["OPENING_HOURLY_PAY"] . "</td>";
            echo "<td>";
            echo "<form method='post' action='edit_openings.php'>";
            echo "<input type='hidden' name='openingId' value='" . $row["OPENING_ID"] . "'>";
            echo "New Pay: <input type='number' name='newHourlyPay' min='0' required>";
            echo "<input type='submit' value='Edit'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else { echo "No openings found for $companyName.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newHourlyPay']) && isset($_POST['openingId'])) {
    $newHourlyPay = $_POST["newHourlyPay"];
    $openingId = $_POST["openingId"];
    // update pay
    $updateQuery = "UPDATE OPENING SET OPENING_HOURLY_PAY = '$newHourlyPay' WHERE OPENING_ID = '$openingId'";
    if ($conn->query($updateQuery) === TRUE) {
        echo "Hourly pay updated successfully!";
    }
}

$conn->close();
?>
