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

$declineQuery = "UPDATE requests_partner SET Status = 'Declined' WHERE partnerID = '".$partnerID."' AND requestID = '".$requestID."'";
$decliningResult = mysqli_query($con, $declineQuery); 

if (!$decliningResult) {
    echo "<script>alert('failure')</script>";
} else {
    echo "<script>alert('suceess')</script>";
}

mysqli_close($con);
?>


