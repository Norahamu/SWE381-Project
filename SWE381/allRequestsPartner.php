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
$(document).ready(function(){
    $("#button2").click(function() {
        console.log("Button clicked");
        var learnerId = $(this).data('learner-id');
        var requestID = $(this).data('req-id');
        console.log(requestID);

        $.ajax({
            url: "declineRequest.php",
            method: "POST",
            data: { LID: learnerId, REQID: requestID },
            success: function(response) {
                if (response.trim() === "success") {
                    window.location.reload();
                    console.log("Request declined successfully");
                } else {
                    console.error("Error declining request:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error sending AJAX request:", error);
            }
        });
    });
    
    

    $("#button1").click(function(){
        var partnerId = $(this).data('partner-id');
        var requestID = $(this).data('req-id');
        var learnerID = $(this).data('learner-id');
        var RSch = $(this).data('req-sch');
        var RDur = $(this).data('req-dur');

        // Convert requested schedule to Date object
        var requested_date = new Date(RSch);
        var requested_year = requested_date.getFullYear();
        var requested_month = requested_date.getMonth() + 1;
        var requested_day = requested_date.getDate();
        var requested_hour = requested_date.getHours();
        var requested_minute = requested_date.getMinutes();
        var requDate = requested_year + "-" + requested_month + "-" + requested_day;
        var requTime = requested_hour + ":" + requested_minute+":00";

        // Check for overlapping sessions before accepting the request
        // Check for overlapping sessions before accepting the request
$.getJSON("partnerSessions.php?PID=" + partnerId, function(response) {
    console.log("Button1 after partnerSessions");
})
.done(function(response) {
    var overlap = false;
    response.forEach(function(session) {
        // Parse session date and time
        
        // Convert session start and end times to milliseconds
        var session_start = session.session_time;
        var session_duration_m = session.session_duration * 60 ;
        var session_end = session_start + session_duration_m;
        
        console.log("session_start: ", session_start);
        console.log("session_duration_m: ", session_duration_m);
        console.log("session_end: ", session_end);

        // Convert requested start and end times to milliseconds
        var requested_start = new Date(RSch).getTime();
        var requested_end = requested_start + (RDur * 60 );
        console.log("requested_start: ", requested_start);
        console.log("requested_end: ", requested_end);

        // Check for overlap, considering session durations
        if ((requested_start <= session_end && requested_end >= session_start) ||
            (requested_end >= session_start && requested_start <= session_end)) {
            // Overlapping sessions
            overlap = true;
            console.log("Sessions overlap");
        }
    });


            
            if (!overlap) {
                console.log("no overlap");
                $.get("acceptRequest.php", { 
                    LID: learnerID, 
                    PID: partnerId,
                    REQID: requestID, 
                    reqDate: requDate,
    				reqTime: requTime, 
    				reqDuration: RDur 
                }, function(response) {
                    if (response.success) {
                        window.location.reload();
                        alert("Booking accepted successfully!");
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error accepting request:", textStatus, errorThrown);
                    alert("An error occurred while accepting the booking. Please try again later.");
                });
            }else{
            alert("you have an existing session that overlaps this session time!");
            }
        });
    });
$("#all").click(function(event){
    event.preventDefault();
    var partnerId = $(this).data('partner-id');
    var txt="";
    $.getJSON("QueryAllRequest.php?PID="+partnerId, function(response){
        for (var x in response) {
            console.log("inside for all ");
            txt += "<div class='session'> <img src='" + response[x].learner_photo + "' alt='" + response[x].FirstName + " photo' class='image--cover'> <a href='#' class='TPName' id='partnerName' data-learner-id='" + response[x].learnerID + "'>" + response[x].learner_first_name + response[x].learner_last_name + "</a><br>";
            txt += "<h6 class='text2'>" + response[x].RStatus + "</h6>";
            if ( response[x].RStatus == "Pending" ){
                txt+="<div class='button-container'>";
                txt+="<button type='button' class='button1' id='button1' data-partner-id='"+partnerId+"' data-learner-id='"+response[x].learnerID+"' data-req-ID='" +response[x].REQID +"' data-req-sch='" +  response[x].REQSchedule+ "' data-req-dur='"+ response[x].REQsession_Duration + "' >Accept</button>";
                txt+="<button type='button' class='button2' id='button2'  data-partner-id='"+ partnerId+"' data-req-ID='"+response[x].REQID+"' >Decline</button> </div>";
            }
        }
        document.getElementById("site").innerHTML = txt;
    });
});

$("#accepted").click(function(event){
    event.preventDefault();
    var partnerId = $(this).data('partner-id');
    var txt="";
    $.getJSON("QueryAcceptedRequest.php?PID="+partnerId, function(response){
        for (var x in response) {
            txt += "<div class='session'> <img src='" + response[x].learner_photo + "' alt='" + response[x].FirstName + " photo' class='image--cover'> <a href='#' class='TPName' id='partnerName' data-learner-id='" + response[x].learnerID + "'>" + response[x].learner_first_name + response[x].learner_last_name + "</a><br>";
            txt += "<h6 class='text2'>" + response[x].RStatus + "</h6>";
        }
        document.getElementById("site").innerHTML = txt;
    });
});

$("#declined").click(function(event){
    event.preventDefault();
    var partnerId = $(this).data('partner-id');
    var txt="";
    $.getJSON("QueryDeclinedRequest.php?PID="+partnerId, function(response){
        for (var x in response) {
            txt += "<div class='session'> <img src='" + response[x].learner_photo + "' alt='" + response[x].FirstName + " photo' class='image--cover'> <a href='#' class='TPName' id='partnerName' data-learner-id='" + response[x].learnerID + "'>" + response[x].learner_first_name + response[x].learner_last_name + "</a><br>";
            txt += "<h6 class='text2'>" + response[x].RStatus + "</h6>";
        }
        document.getElementById("site").innerHTML = txt;
    });
});


$("#pending").click(function(event){
    event.preventDefault();
    var partnerId = $(this).data('partner-id');
    var txt="";
    console.log("before call");
    $.getJSON("QueryPendingRequest.php?PID="+partnerId, function(response){
        console.log("inside call");
        for (var x in response) {
            console.log("inside for");
            txt += "<div class='session'> <img src='" + response[x].learner_photo + "' alt='" + response[x].FirstName + " photo' class='image--cover'> <a href='#' class='TPName' id='partnerName' data-learner-id='" + response[x].learnerID + "'>" + response[x].learner_first_name + response[x].learner_last_name + "</a><br>";
            txt += "<h6 class='text2'>" + response[x].RStatus + "</h6>";
            txt+="<div class='button-container'>";
            txt+="<button type='button' class='button1' id='button1' data-partner-id='"+partnerId+"' data-learner-id='"+response[x].learnerID+"' data-req-ID='" +response[x].REQID +"' data-req-sch='" +  response[x].REQSchedule+ "' data-req-dur='"+ response[x].REQsession_Duration + "' >Accept</button>";
            txt+="<button type='button' class='button2' id='button2'  data-partner-id='"+ partnerId+"' data-req-ID='"+response[x].REQID+"' >Decline</button> </div>";
        }
        document.getElementById("site").innerHTML = txt;
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
  <a href="allRequestsPartner.php" class="menu-item" id="all" data-partner-id='<?php echo $row['partnerID']; ?>'>All</a>
  <a href="allRequestsPartner.php" class="menu-item" id="accepted" data-partner-id='<?php echo $row['partnerID']; ?>'>Accepted</a>
  <a href="allRequestsPartner.php" class="menu-item" id="pending" data-partner-id='<?php echo $row['partnerID']; ?>'>Pending</a>
  <a href="allRequestsPartner.php" class="menu-item" id="declined" data-partner-id='<?php echo $row['partnerID']; ?>'>Declined</a>
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
        echo "<button type='button' class='button1' id='button1' data-partner-id='$partner_id'  data-learner-id='{$row['learnerID']}'  data-req-id='{$row['REQID']}'    data-req-sch='{$row['REQSchedule']}'   data-req-dur='{$row['REQsession_Duration']}' >Accept</button>";
        echo "<button type='button' class='button2' id='button2' data-learner-id='{$row['learnerID']}' data-req-id='{$row['REQID']}'>Decline</button>";


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
