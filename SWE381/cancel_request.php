<?php
// Assuming you have a database connection established in RequestsList.php
include 'RequestsList.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["request_id"])) {
    // Retrieve the request ID from the POST data
    $requestId = $_POST["request_id"];

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Construct the SQL query to delete the request from requests_partner table
        $deleteQueryPartner = "DELETE FROM requests_partner WHERE RequestID = :requestId";

        // Prepare and execute the statement for requests_partner table
        $stmtPartner = $pdo->prepare($deleteQueryPartner);
        $stmtPartner->bindParam(':requestId', $requestId, PDO::PARAM_INT);
        $stmtPartner->execute();

        // Construct the SQL query to delete the request from requests_learner table
        $deleteQueryLearner = "DELETE FROM requests_learner WHERE RequestID = :requestId";

        // Prepare and execute the statement for requests_learner table
        $stmtLearner = $pdo->prepare($deleteQueryLearner);
        $stmtLearner->bindParam(':requestId', $requestId, PDO::PARAM_INT);
        $stmtLearner->execute();

        // Commit the transaction if both deletions are successful
        $pdo->commit();

        // Check if either of the deletions was successful
        if ($stmtPartner->rowCount() > 0 || $stmtLearner->rowCount() > 0) {
            // If deletion was successful, return a success response
            $response = array("success" => true);
            echo json_encode($response);
        } else {
            // If deletion failed for both tables, return a failure response with error message
            $response = array("success" => false, "error" => "No request found with the given ID");
            echo json_encode($response);
        }
    } catch (PDOException $e) {
        // Rollback the transaction if an error occurred
        $pdo->rollBack();

        // If an error occurred, return a failure response with error message
        $response = array("success" => false, "error" => "Failed to delete the request: " . $e->getMessage());
        echo json_encode($response);
    }
    
    // Reload the page after successful cancellation
    echo '<script>window.location.reload(); </script>';
    
} else {
    // If request is not POST or request_id is not set, return a failure response
    $response = array("success" => false, "error" => "Invalid request");
    echo json_encode($response);
}
?>


