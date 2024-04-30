<?php
DEFINE('DB_USER', 'root');
DEFINE('DB_PSWD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'lingo');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$learner_id = $_GET['id']; // Replace with the actual learner ID

$query = "SELECT L.first_name AS learner_first_name, 
                 L.last_name AS learner_last_name, 
                 L.photo AS learner_photo, 
                 L.city AS learner_city,
                 L.location AS learner_location,
                 PR.Language AS learning_language,
                 PR.ProficiencyLevel AS Proficiency_Level,
                 PR.SessionDuration AS Session_Duration,
                 PR.preferred_schedule AS preferred_schedule
          FROM requests_partner AS PR
          JOIN learners AS L ON L.learner_ID = PR.LearnerID
          WHERE L.learner_ID = $learner_id";


$result = mysqli_query($conn, $query);

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
<title>Learner Card</title>
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
  <link rel="stylesheet" href="cards.css">
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
          
  <div id="drag" class="cv instaFade wrap">
    <div class="mainDetails">
      <div id="headshot" class="">
          
          
          <?php
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<img src={$row['learner_photo']}' alt='{$row['learner_first_name']} photo'>";
              echo "</div>";
              echo "<div id='name'>";
              echo "<h1 class='quickFade delayTwo'>{$row['learner_first_name']} {$row['learner_last_name']}</h1>";
              echo "<div class='tooltip-container'>";
              echo "<a href='mailto:{$row['learner_first_name']}@gmail.com'>@</a>";
              echo "</div>";
              echo "</div>";
              echo "<div class='clear'></div>";
              echo "</div>";
              echo "<div id='mainArea' class='quickFade delayFive'>";
              echo "<section>";
              echo "<article>";
              echo "<div class='sectionTitle'>";
              echo "<h1>Personal Profile</h1>";
              echo "</div>";
              echo "<div class='sectionContent'>";
              echo "<p> City: {$row['learner_city']} </p>";
              echo "<p> Location: {$row['learner_location']} </p>";
              echo "</div>";
              echo "</article>";
              echo "<div class='clear'></div>";
              echo "</section>";
              echo "<div class='clear'></div>";
              echo "<section>";
              echo "<div class='sectionTitle'>";
              echo "<h1>Request Details</h1>";
              echo "</div>";
              echo "<div class='sectionContent'>";
              echo "<p>Language: {$row['learning_language']}.<br>";
              echo "Proficiency Level: {$row['Proficiency_Level']}.<br>";
              echo "Session Duration: {$row['Session_Duration']} hour.<br>";
              $datetime = '2024-05-23T08:00';
              $formatted_datetime = date('Y-m-d \a\t H:i', strtotime($datetime));
              echo "Preferred Schedule: ".$formatted_datetime;
              echo "<button type='button' class='button1'>Accept</button>";
              echo "<button type='button' class='button2'>Decline</button><br><br>";              echo "</div>";
              echo "</section>";

              echo "</div>";
          }
          ?>
          </div>
        </section>
      </div>
    </div>
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
</body>
</html>