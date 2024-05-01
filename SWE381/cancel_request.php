<?php
// Assuming you have a database connection established

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["request_id"])) {
    // Retrieve the request ID from the POST data
    $requestId = $_POST["request_id"];

    // Perform the deletion operation in the database
    // Replace 'your_database_name' with your actual database name
    $connection = mysqli_connect("localhost", "username", "password", "lingo2");

    // Check if the connection was successful
    if ($connection === false) {
        // If connection failed, return a failure response
        $response = array("success" => false, "error" => "Failed to connect to the database");
        echo json_encode($response);
        exit; // Stop further execution
    }

    // Construct the SQL query to delete the request
    $deleteQuery = "DELETE FROM requests_learner WHERE RequestID = $requestId";

    // Execute the deletion query
    $result = mysqli_query($connection, $deleteQuery);

    // Check if the deletion was successful
    if ($result) {
        // If deletion was successful, return a success response
        $response = array("success" => true);
        echo json_encode($response);
    } else {
        // If deletion failed, return a failure response with error message
        $errorMessage = mysqli_error($connection);
        $response = array("success" => false, "error" => "Failed to delete the request: $errorMessage");
        echo json_encode($response);
    }

    // Close the database connection
    mysqli_close($connection);
} else {
    // If request is not POST or request_id is not set, return a failure response
    $response = array("success" => false, "error" => "Invalid request");
    echo json_encode($response);
}
?>
