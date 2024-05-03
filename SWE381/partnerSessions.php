<?php
$partnerID = $_GET['PID'];

define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPWD", "");
define("DBNAME", "lingo");

$con = mysqli_connect(DBHOST, DBUSER, DBPWD, DBNAME);

if (!$con) {
    //echo "<script>alert('couldnt')</script>";
    die('Could not connect: ' . mysqli_error($con));
} else {
    //echo "<script>alert('connect')</script>";
}

// Check for overlapping sessions before accepting the request
$query = "SELECT * FROM sessions WHERE partner_id = $partnerID"; // Corrected variable name
$result = mysqli_query($con, $query); // Corrected connection variable

$rows = array();

while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

mysqli_close($con);

// Objects in PHP can be converted into JSON by using the PHP function json_encode()
print json_encode($rows);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}
?>
