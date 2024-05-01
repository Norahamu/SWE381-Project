<?php
DEFINE('DB_USER', 'root');
DEFINE('DB_PSWD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'lingo');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$partner_id = 123456790;

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


$result = mysqli_query($conn, $query1);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8"> <!-- character encoding-->
 <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- viewport settings-->
<link rel="stylesheet" type="text/css" href="style.css" media="screen" > 
<title>partner Requests</title>
   <!-- icon -->
  <link href="assets/img/Lingoblue.png" rel="icon" >

  <!-- Google Fonts -->
 

  <!-- Vendor CSS Files -->

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


  <!-- Main CSS File -->
  <link href="style.css" rel="stylesheet">
  <link href="sessionsStyle.css" rel="stylesheet">
  <link href="buttons.css" rel="stylesheet">
  
  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

    function generateSessionID() {
        // Generate a random 9-digit number
        return Math.floor(Math.random() * 999999999) + 100000000;
    }
    
    
$(document).ready(function(){

    $("#button2").click(function(){
        console.log("Button clicked");
        var partnerId = $(this).data('partner-id');
        var requestID = $(this).data('req-ID');
        $.ajax({
            url: "declineRequest.php",
            method: "POST", 
            data: { PID: partnerId, REQID: requestID },
            success: function(status){
                console.log("successful que");
            },
            error: function(xhr, status, error) {
                console.error("failure");
            }
        });
        console.log("out of the query");
    });

    $("#button1").click(function(){
        console.log("Button1 clicked");
        var partnerId = $(this).data('partner-id');
        var requestID = $(this).data('req-ID');
        var learnerID = $(this).data('learner-id');
        var RSch = $(this).data('req-sch');
        var RDur = $(this).data('req-dur');
        var sessionID= generateSessionID(); // Assuming generateSessionID() is defined elsewhere

        // Check for overlapping sessions before accepting the request
        $.getJSON("partnerSessions.php?PID=" + partnerId, function(response) {
            console.log("Button1 after partnerSessions");
            response.forEach(function(session) {
                var session_start = new Date(session.session_date + ' ' + session.session_time).getTime();
                var session_end = session_start + (session.session_duration * 60 * 60 * 1000); // Convert duration to milliseconds

                // Convert requested schedule to timestamp
                var requested_start = new Date(RSch).getTime();
                var requested_end = requested_start + (RDur * 60 * 60 * 1000); // Convert duration to milliseconds

                // Check for overlap
                if ((requested_start >= session_start && requested_start < session_end) ||
                    (requested_end > session_start && requested_end <= session_end) ||
                    (requested_start < session_start && requested_end > session_end)) {
                    // There is an overlap, display an error message
                    alert("This request can't be accepted! You have an existing session that overlaps this request preferred time.");
                    return;
                }
            });

            // If no overlap, proceed to accept the request
            $.ajax({
                url: "acceptRequest.php",
                method: "POST",
                data: { PID: partnerId, REQID: requestID, LID: learnerID, RSchedule: RSch, RDuration: RDur, SESID: sessionID },
                success: function(status) {
                    console.log("Response from server: " + status);
                    window.location.reload(); // Reload the page after receiving the response
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        });
    });
    
        $("#all").click(function(){
                    var partnerId = $(this).data('partner-id');
                    var txt="";
                    $.getJSON("QueryAllRequest.php?PID="+partnerId, function(response, status){
                    
                    	txt += "<table border='1'><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Age</th><th>Hometown</th><th>Job</th></tr>";
                        	for (x in response) {
                              	txt += "<div class='session'> <img src='" + response[x].learner_photo + "' alt= '"+response[x].FirstName+" photo'  class='image--cover'> echo "<a href='#' class='TPName' id='partnerName' data-learner-id='"+response[x].learnerID+">"+ response[x].learner_first_name+response[x].learner_last_name"</a><br>" ;
                              	txt+= "<h6 class='text2'>"response[x].RStatus"</h6>";
                              	if ( response[x].RStatus == "Pending" ){
                              		txt+="<div class='button-container'>";
                              		txt+="<button type='button' class='button1' id='button1' data-partner-id='"+$partner_id+"' data-learner-id='"+response[x].learnerID+"' data-req-ID='" +response[x].REQID +"' data-req-sch='" +  response[x].REQSchedule+ "' data-req-dur='"+ response[x].REQsession_Duration + "' >Accept</button>";
                              		txt+="<button type='button' class='button2' id='button2'  data-partner-id='"+ $partner_id+"' data-req-ID='"+response[x].REQID+"' >Decline</button> </div>";
                              	}
                        	}
                    window.location.reload();
                    });
                });
                
                
          $("#accepted").click(function(){
                    var partnerId = $(this).data('partner-id');
                    var txt="";
                    $.getJSON("QueryAllRequest.php?PID="+partnerId, function(response, status){
                    
                    	txt += "<table border='1'><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Age</th><th>Hometown</th><th>Job</th></tr>";
                        	for (x in response) {
                              	txt += "<div class='session'> <img src='" + response[x].learner_photo + "' alt= '"+response[x].FirstName+" photo'  class='image--cover'> echo "<a href='#' class='TPName' id='partnerName' data-learner-id='"+response[x].learnerID+">"+ response[x].learner_first_name+response[x].learner_last_name"</a><br>" ;
                              	txt+= "<h6 class='text2'>"response[x].RStatus"</h6>";
                        	}
                    window.location.reload();
                    });
                });
    
    
});
</script>


  
</head>
<body>

<!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center">
      <a href="index.html" class="logo me-auto"><img src="assets/img/Lingowhite.png" alt="Lingo logo" class="img-fluid"></a>
    </div>
    <nav id="navbar" class="navbar">
      <ul> 
    <li><a class="nav-link scrollto " href="HomePage.html">Sign out</a></li>
    <li><a class="nav-link scrollto" href="myprofilelearner.html">My profile</a></li>
    <li><a class="nav-link scrollto" href="currentSessionsLearner.html">Sessions</a></li>
    <li><a class="nav-link scrollto" href="RequestsList.html">Manage Language Learning Request</a></li>
    <li><a class="nav-link scrollto" href="PartnersList.html">Partners List</a></li>
    <li><a class="nav-link scrollto" href="ReviewLearner.html">Review my Partner</a></li>
      </ul>

    </nav>
  </header>
  <!-- End Header -->
<section class="section-bg">
 <div class="section-title">
<h2>Language Learning Requests</h2> </div>
<br>

<div class="menu">
  <a href="allRequestsPartner.php" class="menu-item" id="all" data-learner-id='<?php echo $row['learnerID']; ?>'>All</a>
  <a href="allRequestsPartner.php" class="menu-item" id="accepted" data-learner-id='<?php echo $row['learnerID']; ?>'>Accepted</a>
  <a href="allRequestsPartner.php" class="menu-item" id="pending" data-learner-id='<?php echo $row['learnerID']; ?>'>Pending</a>
  <a href="allRequestsPartner.php" class="menu-item" id="declined" data-learner-id='<?php echo $row['learnerID']; ?>'>Declined</a>
</div>

<div id="site">
  <?php        
          
    while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='session'>";
    echo "<img src='{$row['learner_photo']}' alt='{$row['learner_first_name']} photo' class='image--cover'>";
    echo "<a href='#' class='TPName' id='partnerName' data-learner-id='{$row['learnerID']}'>{$row['learner_first_name']} {$row['learner_last_name']}</a><br>";    
    echo "<h6 class='text2'>{$row['RStatus']}</h6>"; 
              
    if ($row['RStatus'] == 'Pending') {
    echo '<div class="button-container">';
	echo "<button type='button' class='button1' id='button1' data-partner-id='$partner_id'  data-learner-id='{$row['learnerID']}'  data-req-ID='{$row['REQID']}'    data-req-sch='{$row['REQSchedule']}'   data-req-dur='{$row['REQsession_Duration']}' >Accept</button>";
	echo "<button type='button' class='button2' id='button2'  data-partner-id='$partner_id' data-req-ID='{$row['REQID']}' >Decline</button>";

    echo '</div>';
}

    echo "</div>";
}
?>



          

      </div>
    </section>
  </div>
     <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-6 footer-contact">
            <a href="index.html" class="logo me-auto"><img src="assets/img/Lingoblue.png" alt="" class="img-fluid"></a>
            <p>
              King Saud University <br>
              Riyadh <br>
              Saudi Arabia <br><br>
              <strong>Email:</strong> lingo@project.com<br>
            </p>
          </div>
          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="HomePage.html">Sign out</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="myprofilelearner">My profile</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="currentSessionsLearner.html">Sessions</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="RequestsList.html">Language Learning Requests</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="PartnersList.html">Partner List</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="ReviewLearner.html">Review my partner</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Social Networks</h4>
            <div class="social-links mt-3">
              <a href="https://www.instagram.com/" class="instagram"><i class="bx bxl-instagram"></i></a>
              <a href="https://www.linkedin.com/" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container footer-bottom clearfix">
      <div class="copyright">
        © Copyright <strong><span>Lingo</span></strong>. All Rights Reserved
      </div>
      <div class="credits"></div>
    </div>
  </footer>
  <script>    
  	const partnerNameElement = document.getElementById('partnerName');
    partnerNameElement.addEventListener("click", redirectToLearnerPage);




    function redirectToLearnerPage(event) {
        event.preventDefault();
        const learnerId = this.getAttribute('data-learner-id');
        const url = `learnerCard.php?id=${learnerId}`;
        window.location.href = url;
    }
   </script> 

<?php
          // Free result set
          mysqli_free_result($result);

          // Close connection
          mysqli_close($conn);
          ?>
</body>
</html>
