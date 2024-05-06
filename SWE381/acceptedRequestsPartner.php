<?php

include 'checkpartner';

DEFINE('DB_USER', 'root');
DEFINE('DB_PSWD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'lingo');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_SESSION['learner_id'])){
    $partner_id = $_SESSION['partner_id'];
    
	$query = "SELECT L.first_name AS learner_first_name, 
                 	L.last_name AS learner_last_name, 
                 	L.photo AS learner_photo, 
                 	PR.Status AS RStatus
          	FROM requests_partner AS PR
          	JOIN learners AS L ON PR.learnerID = L.learner_ID
          	WHERE PR.partnerID = $partner_id AND PR.Status='Accepted'";

	$result = mysqli_query($conn, $query);

	if (!$result) {
    	die("Query failed: " . mysqli_error($conn));
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8"> <!-- character encoding-->
 <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- viewport settings-->
<link rel="stylesheet" type="text/css" href="style.css" media="screen" > 
<title>partner accepted Requests</title>
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
    <li><a class="nav-link scrollto " href="logout.php">Sign out</a></li>
    <li><a class="nav-link scrollto" href="myprofilepartner.php">My profile</a></li>
    <li><a class="nav-link scrollto" href="currentSessionsPartner.php">Sessions</a></li>
    <li><a class="nav-link scrollto" href="allRequestsPartner.php">Language Learning Requests</a></li>
    <li><a class="nav-link scrollto" href="ReviewPartner.php">My reviews and rating</a></li>
    <li><a class="nav-link scrollto" href="PartnersListP.php">Partners List</a></li>
      </ul>

    </nav>
  </header>
  <!-- End Header -->
<section class="section-bg">
 <div class="section-title">
<h2>Language Learning Requests</h2> </div>

<div class="menu">
  <a href="allRequestsPartner.php" >All</a>
  <a href="acceptedRequestsPartner.php" class="selected">Accepted</a>
  <a href="pendingRequestsPartner.php">Pending</a>
  <a href="declinedRequestsPartner.php">Declined</a>
</div>

<div id="site">
          <?php
          
          if (!isset($result) || mysqli_num_rows($result) == 0) {
       			echo "<br> <h3 class='sessions'>No requests available.</h3>";
    	   } else {
          // Fetch and display session details
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<div class='session'>";
              echo "<img src='{$row['learner_photo']}' alt='{$row['learner_first_name']} photo' class='image--cover'>";
              echo "<strong class='TPName'>{$row['learner_first_name']} {$row['learner_last_name']}</strong><br>";           
              echo "<h6 class='text2'>Status: {$row['RStatus']}</h6>";
              echo "</div>";
          }
          }

          if (isset($result)) {
          	mysqli_free_result($result);
          }
          // Close connection
          mysqli_close($conn);
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
             <li><i class="bx bx-chevron-right"></i> <a href="logout.php">Sign out</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="myprofilepartner.php">My profile</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="currentSessionsPartner.php">Sessions</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="AllReq.php">Language Learning Requests</a></li>
			  <li><i class="bx bx-chevron-right"></i> <a href="reviewAndRatingPartner.php">my review and rating </a></li>
                           <li><i class="bx bx-chevron-right"></i><a class="nav-link scrollto" href="PartnersListP.php">Partners List</a></li>
           
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
  
</body>
</html>
