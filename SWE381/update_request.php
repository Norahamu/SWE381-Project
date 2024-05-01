<?php
DEFINE('DB_USER', 'root');
DEFINE('DB_PSWD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'lingo');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$action = $_POST['action'];
$partnerId = $_POST['partnerId'];
$requestId = $_POST['requestId'];

if ($action == 'accept') {
    $schedule = $_POST['schedule'];
    $duration = $_POST['duration'];
    $status = 'Accepted'; // Define your status as per your DB schema
} elseif ($action == 'decline') {
    $status = 'Declined';
}

// Prepare an SQL statement to update the request status
$sql = "UPDATE requests_partner SET Status = ? WHERE RequestID = ? AND partnerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $status, $requestId, $partnerId);
$stmt->execute();

if($stmt->affected_rows > 0){
    echo "Update successful";
} else {
    echo "Update failed";
}

$stmt->close();
$conn->close();
?>