<?php

define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPWD", "");
define("DBNAME", "lingo");

$learnerID = $_POST['LID'];
$requestID = $_POST['REQID'];
$partnerID = $_POST['PID'];


try {
    $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update requests_partner table
    $declineQuery = "UPDATE requests_partner SET Status = 'Declined' WHERE learnerID = :learnerID AND requestID = :requestID";
    $stmt = $pdo->prepare($declineQuery);
    $stmt->bindParam(':learnerID', $learnerID);
    $stmt->bindParam(':requestID', $requestID);
    $stmt->execute();

    // Update requests_learner table
    $declineQueryLearner = "UPDATE requests_learner SET Status = 'Declined' WHERE RequestID = :requestID AND PartnerID = :partnerID";
    $stmtLearner = $pdo->prepare($declineQueryLearner);
    $stmtLearner->bindParam(':requestID', $requestID);
    $stmtLearner->bindParam(':partnerID', $partnerID);
    $stmtLearner->execute();

    echo "success"; // Return a simple string indicating success
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;

?>
