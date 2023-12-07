<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "companydb";

echo "<h2>Qualifying Candidates: </h2>";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyName = $_POST["companyName"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $hourlyPay = $_POST["hourlyPay"];
    $qualifications = $_POST["qualifications"];
    $openingTitle = $_POST["openingTitle"];

    $companyCheckQuery = "SELECT COMPANY_ID FROM COMPANY WHERE COMPANY_NAME = '$companyName'";
    $result = $conn->query($companyCheckQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $companyId = $row["COMPANY_ID"];
    } else {
        // new company if it doesn't exist
        $insertCompanyQuery = "INSERT INTO COMPANY (COMPANY_NAME) VALUES ('$companyName')";
        $conn->query($insertCompanyQuery);
        $companyId = $conn->insert_id;
    }

    // make opening
    $insertOpeningQuery = "INSERT INTO OPENING (COMPANY_NAME, OPENING_TITLE, OPENING_START, OPENING_END, OPENING_HOURLY_PAY, QUALIFICATION_CODE) VALUES ('$companyName', '$openingTitle', '$startDate', '$endDate', '$hourlyPay', '$qualifications')";
    $conn->query($insertOpeningQuery);
    $openingId = $conn->insert_id;

    displayCandidates($conn, $qualifications, $openingId, $openingTitle, $startDate, $endDate);

    $conn->close();
}

function displayCandidates($conn, $qualifications, $openingId, $openingTitle, $startDate, $endDate) {
    // get candidates w qualification
    $sql = "SELECT C.CANDIDATE_ID, C.CANDIDATE_NAME FROM CANDIDATE C
            JOIN EARNED_QUALIFICATION EQ ON C.CANDIDATE_ID = EQ.CANDIDATE_ID
            WHERE EQ.QUALIFICATION_CODE IN ('$qualifications')
            GROUP BY C.CANDIDATE_ID, C.CANDIDATE_NAME";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "Candidate Name: " . $row["CANDIDATE_NAME"] . "<br>";

            // show all qualifications
            $candidateQualificationsQuery = "SELECT QUALIFICATION_CODE FROM EARNED_QUALIFICATION WHERE CANDIDATE_ID = " . $row["CANDIDATE_ID"];
            $candidateQualificationsResult = $conn->query($candidateQualificationsQuery);
            if ($candidateQualificationsResult->num_rows > 0) {
                echo "Qualifications: ";
                while ($qualificationRow = $candidateQualificationsResult->fetch_assoc()) {
                    echo $qualificationRow["QUALIFICATION_CODE"] . " ";
                }
                echo "<br>";

                // insert into PLACEMENT table
                $placementInsertQuery = "INSERT INTO PLACEMENT (CANDIDATE_ID, OPENING_ID) VALUES (" . $row["CANDIDATE_ID"] . ", $openingId)";
                if ($conn->query($placementInsertQuery)) {
                    $placementId = $conn->insert_id;
                    // insert into JOB_HISTORY
                    $jobHistoryInsertQuery = "INSERT INTO JOB_HISTORY (CANDIDATE_ID, JOB_HISTORY_TITLE, JOB_HISTORY_START, JOB_HISTORY_END) VALUES (" . $row["CANDIDATE_ID"] . ", '$openingTitle','$startDate', '$endDate')";
                    $conn->query($jobHistoryInsertQuery);
                }
            }
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "No candidates found.";
    }
}
?>