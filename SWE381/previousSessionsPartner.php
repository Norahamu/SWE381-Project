<?php
session_start();

DEFINE('DB_USER', 'root');
DEFINE('DB_PSWD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'lingo');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_SESSION['partner_id'])){
    $partner_id = $_SESSION['partner_id'];

	// Update session status to 'Previous' for sessions that have ended
	$update_query = "UPDATE learner_sessions ls
                	JOIN sessions s ON ls.session_id = s.session_id
                	SET ls.session_status = 'Previous'
                	WHERE NOW() >= DATE_ADD(CONCAT(s.session_date, ' ', s.session_time), INTERVAL s.duration HOUR)";

	$update_query .= ";";

	$update_query .= "UPDATE partner_sessions ps
                    	JOIN sessions s ON ps.session_id = s.session_id
                    	SET ps.session_status = 'Previous'
                    	WHERE NOW() >= DATE_ADD(CONCAT(s.session_date, ' ', s.session_time), INTERVAL s.duration HOUR)";

	$update_query .= ";";

	if (!mysqli_multi_query($conn, $update_query)) {
    	echo "Error updating session status: " . mysqli_error($conn);
	}

	// Free the result set after multi-query execution
	while (mysqli_next_result($conn)) {
    	if (!$result = mysqli_store_result($conn)) {
        	if (mysqli_errno($conn)) {
            	echo "Query execution error: " . mysqli_error($conn);
        	}
    	} else {
        	mysqli_free_result($result);
    	}
	}


	$query = "SELECT L.first_name AS learner_first_name, L.last_name AS learner_last_name, L.photo AS learner_photo, S.session_date, S.session_time
          	FROM sessions AS S
          	JOIN learners AS L ON S.learner_id = L.learner_id
          	JOIN partner_sessions PS ON S.session_id = PS.session_id
          	WHERE PS.partner_id = $partner_id AND PS.session_status = 'previous'";



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
<title>Previous Sessions Partner</title>
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
<div id="site">
    <section class="section-bg">
      <div class="section-title">
        <h2>sessions</h2>
      </div>
      <div class="menu">
        <a href="currentSessionsPartner.php" class="selected">Current</a>
        <a href="previousSessionsPartner.php">Previous</a>
      </div>
      <div class="sessions">
          <?php
          
          if (mysqli_num_rows($result) == 0) {
       			echo "<br> <h3 class='sessions'>No sessions available.</h3>";
    	   } else {
          // Fetch and display session details
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<div class='session'>";
              echo "<img src='{$row['learner_photo']}' alt='user icon' class='userBlue'>";             
              echo "<strong class='TPName'>{$row['learner_first_name']} {$row['learner_last_name']}</strong><br>";
              echo "<h6 class='time'>{$row['session_date']} {$row['session_time']}</h6>";
              echo "<i class='fa fa-clock-o' style='font-size:24px'></i>";
              echo "</div>";
          }
          }

          // Free result set
          mysqli_free_result($result);

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
              <li><i class="bx bx-chevron-right"></i> <a href="allRequestsPartner.php">Language Learning Requests</a></li>
     <li><i class="bx bx-chevron-right"></i> <a href="ReviewPartner.php">my review and rating </a></li>
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
        ©️ Copyright <strong><span>Lingo</span></strong>. All Rights Reserved
      </div>
      <div class="credits"></div>
    </div>
  </footer>
</body>
</html>
