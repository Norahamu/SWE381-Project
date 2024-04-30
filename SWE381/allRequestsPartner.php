<?php
DEFINE('DB_USER', 'root');
DEFINE('DB_PSWD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'lingo');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$partner_id = $_GET['partnerID'];





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
 <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:300,300i,400,400i,600,600i,700,700i&family=Jost:300,300i,400,400i,500,500i,600,600i,700,700i&family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i">
 

  <!-- Vendor CSS Files -->

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


  <!-- Main CSS File -->
  <link href="style.css" rel="stylesheet">
  <link href="sessionsStyle.css" rel="stylesheet">
  <link href="buttons.css" rel="stylesheet">
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

  <a href="AllReq.html" class="selected">All</a>
  <a href="accepted.html">Accepted</a>
  <a href="pending.html">Pending</a>
  <a href="declined.html">Declined</a>
</div>
<div id="site">
          <?php
          // Fetch and display session details
         while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='session'>";
    echo "<img src='{$row['learner_photo']}' alt='{$row['learner_first_name']} photo' class='image--cover'>";
    echo "<a href='#' class='TPName' id='partnerName' data-learner-id='{$row['learnerID']}'>{$row['learner_first_name']} {$row['learner_last_name']}</a><br>";    
    echo "<h6 class='text2'>{$row['RStatus']}</h6>"; 
              
    if ($row['RStatus'] == 'Pending') {
    echo '<div class="button-container">';
	echo "<button type='button' class='button1' id='button1' data-partner-id='$partner_id'    data-req-ID='{$row['REQID']}'    data-req-sch='{$row['REQSchedule']}'   data-req-dur='{$row['REQsession_Duration']}' >Accept</button>";
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
  
  
    
    const accButton = document.getElementById('button1');
    accButton.addEventListener("click", AcceptRequest);
    
    const decButton = document.getElementById('button2');
    decButton.addEventListener("click", declineRequest);
    
    const partnerNameElement = document.getElementById('partnerName');
    partnerNameElement.addEventListener("click", redirectToLearnerPage);




    function redirectToLearnerPage(event) {
        event.preventDefault();
        const learnerId = this.getAttribute('data-learner-id');
        const url = `learnerCard.php?id=${learnerId}`;
        window.location.href = url;
    }
    
    
    
    
    
    
function generateSessionID() {
    // Generate a random 9-digit number
    return rand(100000000, 999999999);
}

function AcceptRequest() {
    global $conn;
    const button = event.target;
    const partner_id = button.getAttribute('data-partner-id');
    const req_ID = button.getAttribute('data-req-ID');
    const req_sch = button.getAttribute('data-req-sch');
    const req_dur = button.getAttribute('data-req-dur');
    
    // Check for overlapping sessions before accepting the request
    $query = "SELECT * FROM sessions WHERE partner_id = $partner_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        // Get the session date, time, and duration
        $session_start = strtotime($row['session_date'] . ' ' . $row['session_time']);
        $session_end = $session_start + $row['session_duration'] * 3600; // Convert duration to seconds

        // Convert requested schedule to timestamp
        $requested_start = strtotime($req_sch);
        $requested_end = $requested_start + $req_dur * 3600; // Convert duration to seconds

        // Check for overlap
        if (($requested_start >= $session_start && $requested_start < $session_end) ||
            ($requested_end > $session_start && $requested_end <= $session_end) ||
            ($requested_start < $session_start && $requested_end > $session_end)) {
            // There is an overlap, display an error message
            echo "<script>alert('This request can't be accepted! You have an existing session that overlaps this request preferred time.');</script>";
            return; // Exit the function to prevent accepting the request
        }
    }

    // No overlap found, proceed with accepting the request
    mysqli_begin_transaction($conn);
    $acceptQuery = "UPDATE requests_partner SET Status = 'Accepted' WHERE partnerID = $partner_id AND REQ_ID = $req_ID";
    
    // Generate a random session ID
    $sessionID = generateSessionID();
    
    // Insert the new session
    $insertQuery = "INSERT INTO sessions (session_ID, partner_id, learner_id, session_date, session_time) VALUES ('$sessionID', $partner_id, '2024-05-23', '08:00')";

    // Execute the queries
    $acceptingResult = mysqli_query($conn, $acceptQuery);
    $insertResult = mysqli_query($conn, $insertQuery);

    // Check if any query failed
    if (!$acceptingResult || !$insertResult) {
        mysqli_rollback($conn);
        die("Query failed: " . mysqli_error($conn));
        echo "<script>alert('This request cant be accepted! You have an existing session that overlaps this request preferred time.');</script>";
        return; // Exit the function to prevent further execution
    }

    // If all queries executed successfully, commit the transaction
    mysqli_commit($conn);
    echo "<script>alert('Accepted successfully!');</script>";
}








function declineRequest( ) {
    global $conn;
    const button = event.target;
    const partner_id = button.getAttribute('data-partner-id');
    // Update the status of the request to "Declined"
    $declineQuery = "UPDATE requests_partner SET Status = 'Declined' WHERE partnerID = $partner_id";
    $decliningResult = mysqli_query($conn, $declineQuery);

    if (!$decliningResult) {
        die("Query failed: " . mysqli_error($conn));
    } else {
        echo "<script>alert('The request declined successfully!');</script>";
    }
}
</script>
<php?
          // Free result set
          mysqli_free_result($result);

          // Close connection
          mysqli_close($conn);
          ?>
</body>
</html>