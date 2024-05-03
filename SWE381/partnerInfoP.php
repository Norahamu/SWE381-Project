<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"> <!-- character encoding-->
        <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- viewpoet settings-->
        <link rel="stylesheet" type="text/css" href="style.css" media="screen" > 
        <title>Partner Information</title>
        <!-- icon -->
        <link href="assets/img/Lingoblue.png" rel="icon" >

        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:300,300i,400,400i,600,600i,700,700i&family=Jost:300,300i,400,400i,500,500i,600,600i,700,700i&family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i">

        <style>
        </style>
        <!-- Vendor CSS Files -->

        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">


        <!-- Main CSS File -->
        <link href="style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="cssPartnerInfo.css">
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
    <li><a class="nav-link scrollto" href="myprofilepartner.php">My profile</a></li>
    <li><a class="nav-link scrollto" href="currentSessionsPartner.php">Sessions</a></li>
    <li><a class="nav-link scrollto" href="AllReq.php">Language Learning Requests</a></li>
    <li><a class="nav-link scrollto" href="reviewAndRatingPartner.php">My reviews and rating</a></li>
    <li><a class="nav-link scrollto" href="PartnersListP.php">Partners List</a></li>
      </ul>

    </nav>
  </header>
  <!-- End Header -->



        <section class="section-bg">   
            <div class="section-title">

                <h2> Partner Information </h2></div>

            <div class = " insideHeader">
                <?php
                $partnerID = $_GET['partnerID'];

                $connection = mysqli_connect('localhost', 'root', 'root', 'lingo');
                if (!$connection) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                $sql = "SELECT * FROM partners WHERE partner_id = '$partnerID'";
                $result = mysqli_query($connection, $sql);
                while ($row = mysqli_fetch_assoc($result)) {

                    $fname = $row['first_name'];
                    $lname = $row['last_name'];
                    $photo = $row['photo'];
                    $Cknowldge = $row['cultural_knowledge'];
                    $mail = $row['email'];
                    $education = $row['Education'];
                    $experience = $row['Experience'];
                    $price = $row['PricePerSession'];
                    $partnerID = $row['partner_id'];

                    if ($photo == null) {
                        echo "<img class = 'personal' src='assets/img/OIP.jpg' width ='90' height= '80' alt='personal'>";
                    } else {
                        echo "<img class = 'personal' src='" . $photo . "' width ='90' height= '80' alt='personal'>";
                    }
                    echo "<div class = ' RightPartOfInsideHeader'>
                <div >
                    <img class='Icon ' src='assets/img/dolarr.png' alt='small icon' >";
                    echo "<p class='test'>" . $price . "SAR/hour </p>";
                    echo "</div>
                <div>
                    <img class='Icon' src='assets/img/like_dislike.png' alt='small icon' >
                    <div class='rating test'>";
                    $sql2 = "SELECT * FROM review_and_rating WHERE partner_id = '$partnerID'";
                    $result2 = mysqli_query($connection, $sql2);
                    $rate = 0;
                    $counter = 0;
                    while ($row = mysqli_fetch_assoc($result2)) {
                        $counter++;
                        $rate += $row['Rating'];
                    }

                    if ($counter != 0) {
                        $avr = $rate / $counter;
                        
                        for ($x = 1; $x <= $avr; $x++) {

                            echo " <input value='5' name= 'rate' id='star5' type='radio'> <label title='text' for='star5'></label>";
                        }
                    }
                    echo "    </div>
                </div>
                
                
                    
                <div>
                  <div style='display: flex; flex-direction: column; align-items: center; text-align: center;'>
                    <div class='tooltip-container test'>";
                    echo "  <span class='text'><a href='mailto:" . $mail . "'>@</a> </span>";

                    echo"     </div>
                    <p class='test1'>Contact me <br>for initial Discussion </p>
					</div>
                </div>
            </div>
        </div>

        ";

                    echo "<h1 class = 'name'>" . $fname . " " . $lname . "</h1>
                             <hr>";

                    echo " <div ><!-- superdiv -->
            <div class='SmDiv'>
                <img class='SmallIcon' src='assets/img/personal-information.png' alt='small icon' > 
                <h2 class='toPutInLine'> About me</h2>";
                    echo " <p>" . $Cknowldge . "</p>";
                    $sql3 = "SELECT * FROM partner_languages WHERE partner_id  = '$partnerID'";
                    $result3 = mysqli_query($connection, $sql3);
                    echo " my proficiency level: ";
                    while ($row2 = mysqli_fetch_assoc($result3)) {
                        $lang = $row2['language'];
                        $ProficiencyLevel = $row2['ProficiencyLevel'];
                        echo " " . $ProficiencyLevel . " in " . $lang . ",";
                    }
                    echo "    
            </div><!-- end child1 div -->
            


            <div class='SmDiv'> 
                <img class='SmallIcon' src='assets/img/WorkExperience.png' alt='small icon' > 
                <h2 class='toPutInLine'> Work Experience </h2>
                <p>" . $experience . "</p> ";

                    echo "    
            </div><!-- end child2 div -->

            
            <div class='SmDiv'>
                <img class='SmallIcon' src='assets/img/education.png' alt='small icon' > 
                <h2 class='toPutInLine'> Education</h2>
                <p>" . $education . "</p>";

                    echo "
            </div><!-- end child3 div -->
            


            <div class='SmDiv'>
                <img class='SmallIcon' src='assets/img/comments.png' alt='small icon' > 
                <h2 class='toPutInLine'> Comment</h2>
                <div id='site'> ";

                    $sql4 = "SELECT * FROM review_and_rating WHERE partner_id = '$partnerID'";
                    $result4 = mysqli_query($connection, $sql4);
                    if ($result4->num_rows === 0) {
                        echo "<p id='noReviews'> there is no Reviews <p>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result4)) {
                            $learnerid = $row['learner_id'];
                            $star = $row['Rating'];
                            $review = $row['Review'];
                            $sql5 = "SELECT * FROM learners WHERE learner_id = '$learnerid'";
                            $result5 = mysqli_query($connection, $sql5);
                            
                             if ($review!= null && $review!= ""){
                            echo" 
                 <div class='review'>


                        <div class='heading'>";

                           
                            while ($row = mysqli_fetch_assoc($result5)) {
                                $img = $row['photo'];
                                $fn = $row['first_name'];
                                $ln = $row['last_name'];

                                echo " <img src='" . $img . "' alt='user icon' class='userBlue'>
                            <strong>" . $fn . " " . $ln . "</strong> <br>";
                            }
                            echo "<div class='stars'>";
                            for ($x = 0; $x < $rate; $x++) {
                                echo "<span class='fa fa-star checked'></span> ";
                            }
                            echo "   </div> <!--stars-->";

                            echo "
                           
                        </div> <!--heading-->";

                            echo" <div class='learner-review'>
                            <p class ='Aleen'>" . $review . "</p>
                        </div> <!--learner review-->
                        </div> <!-- review1-->";}
                        }
                    }
                }
                ?>
            </div> <!-- site-->



        </div><!-- end child4 div -->
        <br>
        <br>




        </div><!-- end superdiv -->
        <br>
        <br>
        <br>
        <br>
        <br>
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
</body>
</html>
