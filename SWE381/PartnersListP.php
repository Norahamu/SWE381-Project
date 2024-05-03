<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8"> <!-- character encoding-->
 <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- viewpoet settings-->
<link rel="stylesheet" type="text/css" href="style.css" media="screen" > 
<title>Partners List</title>
   <!-- icon -->
  <link href="assets/img/Lingoblue.png" rel="icon" >

  <!-- Google Fonts -->
 <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:300,300i,400,400i,600,600i,700,700i&family=Jost:300,300i,400,400i,500,500i,600,600i,700,700i&family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i">
 

  <!-- Vendor CSS Files -->

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">


  <!-- Main CSS File -->
  <link href="style.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="cssPartnerListP.css">

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
 <br>
  <div class = "navigation">
            <ul>
                <li><a href="#ArabicLanguage"> Arabic</a></li>
                <li><a href="#EnglishLanguage"> English</a></li>
                <li><a href="#FrenshLanguage"> French</a></li>
                <li><a href="#EspanishLanguage"> Spanish</a></li>

            </ul>
        </div>
        <h2> PARTNER LIST </h2></div>     
        <h2 id ="ArabicLanguage" class =" LanguageTitle">Arabic </h2>
       
        
       
        <div class ="superlanguage">
        <?php
         $connection = mysqli_connect('localhost', 'root', '', 'lingo');
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
     $sql = "SELECT partner_id FROM partner_languages WHERE language = 'Arabic'";
    $result = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        	$pID= $row['partner_id'];
                
        $sql2 = "SELECT * FROM partners WHERE partner_id  = '$pID'";
         $result2 = mysqli_query($connection, $sql2);
        while ($row = mysqli_fetch_assoc($result2)){
          $fname= $row['first_name'];
           $lname= $row['last_name'];
           $photo= $row['photo'];
           $Cknowldge= $row['cultural_knowledge'];
           $mail= $row['email'];
           $partnerID= $row['partner_id'];
         echo "<div class = 'content'>";
         if ($photo == null) {
            echo "<img class = 'personal' src='assets/img/OIP.jpg' width ='90' height= '80' alt='personal'>";
        } else {
            echo "<img class = 'personal' src='" . $photo . "' width ='90' height= '80' alt='personal'>";
        }
        
         echo  "<h1 class = 'name'>" .$fname. " ".$lname." </h1>";
         echo "<p class = 'bio' >".$Cknowldge."</p>";
         
         $sql3 = "SELECT language FROM partner_languages WHERE partner_id = '$pID'";
$result3 = mysqli_query($connection, $sql3);
$languages = [];while ($row2 = mysqli_fetch_assoc($result3)) {
    $languages[] = $row2['language'];
}
$language_list = implode(", ", $languages);
echo "Languages I teach: " . $language_list;
         echo  "<a class = 'chatMe' href='mailto:".$mail."'> for initial discussion </a>";
         echo  "<a  class = 'knowMoreAboutme' href='partnerInfoP.php?partnerID=".$partnerID."'> read more!</a>";
       
 
         echo "   </div><!-- small div -->";
            }
    }
            ?>
            



                


        </div><!-- big div -->


        <h2 id="EnglishLanguage" class =" LanguageTitle" >English </h2>
        <div class ="superlanguage">

             <?php
         $connection = mysqli_connect('localhost', 'root', '', 'lingo');
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT partner_id FROM partner_languages WHERE language = 'English'";
    $result = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        	$pID= $row['partner_id'];
                
        $sql2 = "SELECT * FROM partners WHERE partner_id  = '$pID'";
                
       
         $result2 = mysqli_query($connection, $sql2);
        while ($row = mysqli_fetch_assoc($result2)){
          $fname= $row['first_name'];
           $lname= $row['last_name'];
           $photo= $row['photo'];
           $Cknowldge= $row['cultural_knowledge'];
           $mail= $row['email'];
           $partnerID= $row['partner_id'];
         echo "<div class = 'content'>";
         if ($photo == null) {
            echo "<img class = 'personal' src='assets/img/OIP.jpg' width ='90' height= '80' alt='personal'>";
        } else {
            echo "<img class = 'personal' src='" . $photo . "' width ='90' height= '80' alt='personal'>";
        }
         
         echo  "<h1 class = 'name'>" .$fname. " ".$lname." </h1>";
         echo "<p class = 'bio' >".$Cknowldge."</p>";
         
        $sql3 = "SELECT language FROM partner_languages WHERE partner_id = '$pID'";
$result3 = mysqli_query($connection, $sql3);
$languages = [];while ($row2 = mysqli_fetch_assoc($result3)) {
    $languages[] = $row2['language'];
}
$language_list = implode(", ", $languages);
echo "Languages I teach: " . $language_list;
           
         echo  "<a class = 'chatMe' href='mailto:".$mail."'> for initial discussion </a>";
         echo  "<a  class = 'knowMoreAboutme' href='partnerInfoP.php?partnerID=".$partnerID."'> read more!</a>";
       
 
         echo "   </div><!-- small div -->";
            }
    }
            ?>
        </div><!-- big div -->
<h2 id ="FrenshLanguage" class =" LanguageTitle">French </h2>
        <div class ="superlanguage">
  <?php
         $connection = mysqli_connect('localhost', 'root', '', 'lingo');
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
     $sql = "SELECT partner_id FROM partner_languages WHERE language = 'French'";
    $result = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        	$pID= $row['partner_id'];
                
        $sql2 = "SELECT * FROM partners WHERE partner_id  = '$pID'";
                
       
         $result2 = mysqli_query($connection, $sql2);
        while ($row = mysqli_fetch_assoc($result2)){
          $fname= $row['first_name'];
           $lname= $row['last_name'];
           $photo= $row['photo'];
           $Cknowldge= $row['cultural_knowledge'];
           $mail= $row['email'];
           $partnerID= $row['partner_id'];
         echo "<div class = 'content'>";
         if ($photo == null) {
            echo "<img class = 'personal' src='assets/img/OIP.jpg' width ='90' height= '80' alt='personal'>";
        } else {
            echo "<img class = 'personal' src='" . $photo . "' width ='90' height= '80' alt='personal'>";
        }
        
         echo  "<h1 class = 'name'>" .$fname. " ".$lname." </h1>";
         echo "<p class = 'bio' >".$Cknowldge."</p>";
         
         $sql3 = "SELECT language FROM partner_languages WHERE partner_id = '$pID'";
$result3 = mysqli_query($connection, $sql3);
$languages = [];while ($row2 = mysqli_fetch_assoc($result3)) {
    $languages[] = $row2['language'];
}
$language_list = implode(", ", $languages);
echo "Languages I teach: " . $language_list;
         echo  "<a class = 'chatMe' href='mailto:".$mail."'> for initial discussion </a>";
         echo  "<a  class = 'knowMoreAboutme' href='partnerInfoP.php?partnerID=".$partnerID."'> read more!</a>";
       
 
         echo "   </div><!-- small div -->";
            }
    }
            ?>

</div><!-- big div -->
        <h2 id ="EspanishLanguage" class =" LanguageTitle" >Spanish </h2>
        <div class ="superlanguage">

            <?php
         $connection = mysqli_connect('localhost', 'root', '', 'lingo');
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT partner_id FROM partner_languages WHERE language = 'Spanish'";
    $result = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        	$pID= $row['partner_id'];
                
        $sql2 = "SELECT * FROM partners WHERE partner_id  = '$pID'";
                
       
         $result2 = mysqli_query($connection, $sql2);
        while ($row = mysqli_fetch_assoc($result2)){
          $fname= $row['first_name'];
           $lname= $row['last_name'];
           $photo= $row['photo'];
           $Cknowldge= $row['cultural_knowledge'];
           $mail= $row['email'];
           $partnerID= $row['partner_id'];
         echo "<div class = 'content'>";
         if ($photo == null) {
            echo "<img class = 'personal' src='assets/img/OIP.jpg' width ='90' height= '80' alt='personal'>";
        } else {
            echo "<img class = 'personal' src='" . $photo . "' width ='90' height= '80' alt='personal'>";
        }
        echo  "<h1 class = 'name'>" .$fname. " ".$lname." </h1>";
         echo "<p class = 'bio' >".$Cknowldge."</p>";
         
         $sql3 = "SELECT language FROM partner_languages WHERE partner_id = '$pID'";
$result3 = mysqli_query($connection, $sql3);
$languages = [];while ($row2 = mysqli_fetch_assoc($result3)) {
    $languages[] = $row2['language'];
}
$language_list = implode(", ", $languages);
echo "Languages I teach: " . $language_list;
         echo  "<a class = 'chatMe' href='mailto:".$mail."'> for initial discussion </a>";
         echo  "<a  class = 'knowMoreAboutme' href='partnerInfoP.php?partnerID=".$partnerID."'> read more!</a>";
       
 
         echo "   </div><!-- small div -->";
            }
    }
            ?>


        </div><!-- big div -->

        

</section>
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
