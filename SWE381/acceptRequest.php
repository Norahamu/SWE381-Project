<?php

// Database configuration
define("DBHOST","localhost");
define("DBUSER","root");
define("DBPWD","");
define("DBNAME","lingo");

// Check if all necessary parameters are set in the URL or POST data
if (isset($_GET['PID'], $_GET['REQID'], $_GET['LID'], $_GET['reqDate'], $_GET['reqTime'], $_GET['reqDuration'])) {
    $partnerID = $_GET['PID'];
    $requestID = $_GET['REQID'];
    $learnerID = $_GET['LID'];
    $requDate = $_GET['reqDate'];
    $requTime = $_GET['reqTime'];
    $reqDuration = $_GET['reqDuration'];
    
    try {
        // Connect to the database using PDO
        $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Begin transaction
        $pdo->beginTransaction();

        // Assuming generateSessionID() safely generates a unique session ID.
        // Ensure this function exists and properly checks for uniqueness against the database.
        function generateSessionID($length = 9) {
            // Generate a random number with 9 digits
            $random_number = mt_rand(100000000, 999999999);
            return $random_number;
        }

        $sessionID = generateSessionID();

        // Insert the new session 
        $insertQuery = "INSERT INTO sessions (session_id, partner_id, learner_id, session_date, session_time, duration) VALUES (:sessionID, :partnerID, :learnerID, :requDate, :requTime, :reqDuration)";
        $stmtInsert = $pdo->prepare($insertQuery);

        // Bind parameters for the INSERT statement
        $stmtInsert->bindParam(':sessionID', $sessionID);
        $stmtInsert->bindParam(':partnerID', $partnerID);
        $stmtInsert->bindParam(':learnerID', $learnerID);
        $stmtInsert->bindParam(':requDate', $requDate);
        $stmtInsert->bindParam(':requTime', $requTime);
        $stmtInsert->bindParam(':reqDuration', $reqDuration);

        // Execute the INSERT statement
        if (!$stmtInsert->execute()) {
            throw new PDOException("Error inserting new session.");
        }

        // Update the request status to accepted 
        $acceptQuery = "UPDATE requests_partner SET Status = 'Accepted' WHERE RequestID = :requestID AND LearnerID = :learnerID";
        $stmtAccept = $pdo->prepare($acceptQuery);
        $stmtAccept->bindParam(':requestID', $requestID);
        $stmtAccept->bindParam(':learnerID', $learnerID); // Add this line for binding learnerID

        if (!$stmtAccept->execute()) {
            throw new PDOException("Error updating request status.");
        }

        $updateLearnerQuery = "UPDATE requests_learner SET Status = 'Accepted' WHERE RequestID = :requestID AND PartnerID = :partnerID";
        $stmtUpdateLearner = $pdo->prepare($updateLearnerQuery);
        $stmtUpdateLearner->bindParam(':requestID', $requestID);
        $stmtUpdateLearner->bindParam(':partnerID', $partnerID);
        $stmtUpdateLearner->execute();

        $insertPartnerSessionQuery = "INSERT INTO partner_sessions (partner_id, session_id, session_status) VALUES (:partnerID, :sessionID, 'Current')";
        $stmtInsertPartnerSession = $pdo->prepare($insertPartnerSessionQuery);
        $stmtInsertPartnerSession->bindParam(':partnerID', $partnerID);
        $stmtInsertPartnerSession->bindParam(':sessionID', $sessionID);
        $stmtInsertPartnerSession->execute();

        $insertLearnerSessionQuery = "INSERT INTO learner_sessions (learner_id, session_id, session_status) VALUES (:learnerID, :sessionID, 'Current')";
        $stmtInsertLearnerSession = $pdo->prepare($insertLearnerSessionQuery);
        $stmtInsertLearnerSession->bindParam(':learnerID', $learnerID);
        $stmtInsertLearnerSession->bindParam(':sessionID', $sessionID);
        $stmtInsertLearnerSession->execute();

        // Commit transaction
        $pdo->commit();
        echo json_encode(array('success' => true, 'message' => 'Session created and request accepted'));
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        error_log("Error: " . $e->getMessage());
        echo json_encode(array('success' => false, 'error' => $e->getMessage()));
    }

    // Close connection
    $pdo = null;

} else {
    echo json_encode(array('success' => false, 'error' => 'Missing necessary parameters'));
}
?>
