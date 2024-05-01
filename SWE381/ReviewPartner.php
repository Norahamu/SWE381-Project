<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8"> <!-- character encoding-->
 <meta name="viewport" content="width=device-width, initial-scale=1"> 
 <!-- viewpoet settings-->
<link rel="stylesheet" type="text/css" href="style.css" media="screen" > 
<title>My reviews</title>
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
   <link href="Style.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="ratingpartnerstyle.css">
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
    <li><a class="nav-link scrollto" href="myprofilepartner.html">My profile</a></li>
    <li><a class="nav-link scrollto" href="currentSessionsPartner.html">Sessions</a></li>
    <li><a class="nav-link scrollto" href="AllReq.html">Language Learning Requests</a></li>
    <li><a class="nav-link scrollto" href="reviewAndRatingPartner.html">My reviews and rating</a></li>
      </ul>

    </nav>
  </header>
  <!-- End Header -->
   <br>
  <br>
  <br>
<br>
 
 <div id="site">
 
	  <div class="section-title">
	 <h2> Reviews and Ratings</h2> </div>
			
		 <?php
             session_start();    
if (isset($_SESSION['userr_id'])){
   $partnerID = $_SESSION['user_id'];
}

                //$partnerID = 123456789;
                $connection = mysqli_connect('localhost', 'root', '', 'lingo');
                if (!$connection) {
                    die("Connection failed: " . mysqli_connect_error());
                }
              else{
                echo" 
                 <div class='review'>

                        <div class='heading'>";
                    $sql4 = "SELECT * FROM review_and_rating WHERE partner_id = '$partnerID'";
                    $result4 = mysqli_query($connection, $sql4);
                    while ($row = mysqli_fetch_assoc($result4)) {
                        $learnerid = $row['learner_id'];
                        $star = $row['Rating'];
                        $review = $row['Review'];
                        $sql5 = "SELECT * FROM learners WHERE learner_id = '$learnerid'";
                        $result5 = mysqli_query($connection, $sql5);
                        while ($row = mysqli_fetch_assoc($result5)) {
                            $img = $row['photo'];
                            $fn = $row['first_name'];
                            $ln = $row['last_name'];
                            echo " <img src='assets/img/" . $img . "' alt='user icon' class='userBlue'>
                            <strong>" . $fn . " " . $ln . "</strong> <br>";
                        }
                        echo "<div class='stars'>";
                        for ($x = 0; $x < $star; $x++) {
                            echo "<span class='fa fa-star checked'></span> ";
                        }
                        echo "   </div> <!--stars-->";

                        echo "
                           <br/ >    <h6>june, 2023</h6>
                        </div> <!--heading-->";
                        
                       
                       echo" <div class='learner-review'>
                            <p class ='Aleen'>".$review."</p>
                        </div> <!--learner review-->
                    </div> <!-- review1-->";
                    }}
                ?>	 
        </div> <!-- site-->
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
              <li><i class="bx bx-chevron-right"></i> <a href="myprofilepartner.html">My profile</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="currentSessionsPartner.html">Sessions</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="AllReq.html">Language Learning Requests</a></li>
			  <li><i class="bx bx-chevron-right"></i> <a href="reviewAndRatingPartner.html">my review and rating </a></li>
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