<?php
$partnerID = $_POST['PID'];
$requestID = $_POST['REQID'];

define("DBHOST","localhost");
define("DBUSER","root");
define("DBPWD","");
define("DBNAME","lingo");

$con = mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
echo mysqli_connect_error();

if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

$declineQuery = "UPDATE requests_partner SET Status = 'Declined' WHERE partnerID = '".$partnerID."' AND requestID = '".$requestID."';";
$decliningResult = mysqli_query($con, $declineQuery); 

if (!$decliningResult) {
    echo "Error: " . mysqli_error($con);
    echo "<br>Decline Query: " . $declineQuery;
} else {
    echo "<script>alert('suceess')</script>";
}

mysqli_close($con);
?>