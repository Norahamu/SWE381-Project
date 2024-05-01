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

$query1 = "SELECT L.first_name AS learner_first_name, 
                 L.last_name AS learner_last_name, 
                 L.photo AS learner_photo,
                 L.learner_id AS learnerID, 
                 PR.Status AS RStatus,
                 PR.SessionDuration AS REQsession_Duration,
                 PR.preferred_schedule AS REQSchedule,
                 PR.RequestID AS REQID
          FROM requests_partner AS PR
          JOIN learners AS L ON PR.learnerID = L.learner_ID
          WHERE PR.partnerID = $partner_id";
          
$Result = mysqli_query($con, $declineQuery); 

          // Fetch and display session details
         while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='session'>";
    echo "<img src='{$row['learner_photo']}' alt='{$row['learner_first_name']} photo' class='image--cover'>";
    echo "<a href='#' class='TPName' id='partnerName' data-learner-id='{$row['learnerID']}'>{$row['learner_first_name']} {$row['learner_last_name']}</a><br>";    
    echo "<h6 class='text2'>{$row['RStatus']}</h6>"; 
              
    if ($row['RStatus'] == 'Pending') {
    echo '<div class="button-container">';
	echo "<button type='button' class='button1' id='button1' data-partner-id='$partner_id'  data-learner-id='$learnerID'  data-req-ID='{$row['REQID']}'    data-req-sch='{$row['REQSchedule']}'   data-req-dur='{$row['REQsession_Duration']}' >Accept</button>";
	echo "<button type='button' class='button2' id='button2'  data-partner-id='$partner_id' data-req-ID='{$row['REQID']}' >Decline</button>";

    echo '</div>';
}

    echo "</div>";
}   

if (!$decliningResult) {
    echo "<script>alert('failure')</script>";
} else {
    echo "<script>alert('suceess')</script>";
}

mysqli_close($con);
?>


