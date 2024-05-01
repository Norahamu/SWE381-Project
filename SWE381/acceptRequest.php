<?php
$partnerID = $_GET['PID'];
$requestID = $_GET['REQID'];

define("DBHOST","localhost");
define("DBUSER","root");
define("DBPWD","");
define("DBNAME","lingo");

$con = mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);

if (!$con) {
    echo "<script>alert('couldnt')</script>";
    die('Could not connect: ' . mysqli_error($con));
}else{
	echo "<script>alert('connect')</script>";
}

	mysqli_begin_transaction($conn);
     // Insert the new session
    $insertQuery = "INSERT INTO sessions (session_ID, partner_id, learner_id, session_date, session_time) VALUES ('$sessionID', $partner_id, '2024-05-23', '08:00')";

    // Execute the queries
    $acceptingResult = mysqli_query($conn, $acceptQuery);
    $insertResult = mysqli_query($conn, $insertQuery);

    // Check if any query failed
    if (!$acceptingResult || !$insertResult) {
        mysqli_rollback($conn);
        die("Query failed: " . mysqli_error($conn));
        echo "<script>alert('This request cant be accepted! You have an existing session that overlaps this request preferred time.');</script>";$acceptQuery = "UPDATE requests_partner SET Status = 'Accepted' WHERE partnerID = $partner_id AND REQ_ID = $req_ID";
    

if (!$decliningResult) {
    echo "<script>alert('failure')</script>";
} else {
    echo "<script>alert('suceess')</script>";
}

mysqli_close($con);
?>


