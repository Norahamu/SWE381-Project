<?php
session_start();

// Initialize message storage
if (!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = array();
}

$connection = mysqli_connect("localhost", "root", "", "lingo");
if (mysqli_connect_error()) {
    $_SESSION['messages'][] = '<p>Connection failed</p>';
    exit();
}

  $learnerID = $_SESSION['user_id'];



if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
            //$learnerID =204993711;
            $infarr=$_POST['allInfo'];
            $rate=$_POST['rate'];
            $feed=$_POST['Feedback'];
           $result_explode = explode(',', $infarr);
           $pid=$result_explode[0];
           $date=$result_explode[1];
           $time=$result_explode[2];
            $sql4= "SELECT * FROM sessions WHERE partner_id='$pid' AND learner_id ='$learnerID' AND session_date='$date' AND session_time ='$time'"; 
            $result4 = mysqli_query($connection, $sql4);
           while($row= mysqli_fetch_assoc($result4)){
               $sID=$row['session_id'];  
            $sql5="INSERT INTO review_and_rating (Review, Rating, partner_id , learner_id,session_id ) VALUES ('$feed', '$rate', '$pid', '$learnerID', '$sID')";
            $result5= mysqli_query($connection, $sql5);
           }  
           
    /////
        
        // Validate category existence
   
        

        // File upload handling
 header("Location: partnerInfo.php");
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8"> <!-- character encoding-->
 <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- viewpoet settings-->
<link rel="stylesheet" type="text/css" href="style.css" media="screen" > 
<title>Partner Evaluation</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

   <!-- icon -->
  <link href="img/Lingoblue.png" rel="icon" >
  <!-- Google Fonts -->
 <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:300,300i,400,400i,600,600i,700,700i&family=Jost:300,300i,400,400i,500,500i,600,600i,700,700i&family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i">
 

  <!-- Vendor CSS Files -->

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">


  <!-- Main CSS File -->
  <link href="style.css" rel="stylesheet">
  <link href="2css.css" rel="stylesheet">
  
  <style>
.checked {
  color: orange;
}
</style>

</head>
<body>
<!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center">
      <a href="index.html" class="logo me-auto"><img src="img/Lingowhite.png" alt="Lingo logo" class="img-fluid"></a>
    </div>
    <nav id="navbar" class="navbar">
      <ul> 
    <li><a class="nav-link scrollto " href="logout.php">Sign out</a></li>
    <li><a class="nav-link scrollto" href="myprofilelearner.html">My profile</a></li>
    <li><a class="nav-link scrollto" href="currentSessionsLearner.html">Sessions</a></li>
    <li><a class="nav-link scrollto" href="RequestsList.html">Manage Language Learning Request</a></li>
    <li><a class="nav-link scrollto" href="PartnersList.php">Partners List</a></li>
    <li><a class="nav-link scrollto" href="ReviewLearner.php ">Review my Partner</a></li>
      </ul>

    </nav>
  </header>
  <!-- End Header -->

   <br>
  <br>
  <br>
<br>

<section id="signuppartner" class="signuppartner section-bg">
    <div class="container aos-init aos-animate" data-aos="fade-up">
      <div class="section-title">
        <h2>Partner Evaluation</h2>
      </div>
      <form action="ReviewLearner.php" method="POST">
        <div class="evaluation">
          <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
              <div class="info">
                <div>
                 <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <div class="item1" style="display: flex; align-items: center;">
        <h4 style="margin-right: 10px;">Choose a partner:*</h4>
         <select id="sel" name="allInfo" required>
        <?php 
          
           $sql= "SELECT * FROM learner_sessions WHERE session_status='Previous' AND learner_id ='$learnerID'";            
           $result = mysqli_query($connection, $sql);
           while($row= mysqli_fetch_assoc($result)){
               $sID=$row['session_id'];
           $sql2= "SELECT * FROM sessions WHERE session_id ='$sID'";
            $result2 = mysqli_query($connection, $sql2);
           while($row= mysqli_fetch_assoc($result2)){
               $pID=$row['partner_id'];
               $date=$row['session_date'];
               $time= $row['session_time'];
          $sql3= "SELECT * FROM partners WHERE partner_id ='$pID'";
            $result3 = mysqli_query($connection, $sql3);
           while($row= mysqli_fetch_assoc($result3)){
            echo "<option value='".$pID.",".$date.",".$time."'>".$row['first_name']." ".$row['last_name']."</option>";
           } }} 
           ?>
        </select>
    </div>
<div style="display: flex; align-items: center; gap:0;">
  <h4 style=" margin: 0px;">Rate:</h4>
  <div class="rating test" style="display: flex;">
    <input value="5" name="rate" id="star5" type="radio" >
    <label title="text" for="star5" style="display: inline-block;"></label>
    <input value="4" name="rate" id="star4" type="radio" >
    <label title="text" for="star4" style="display: inline-block;"></label>
    <input value="3" name="rate" id="star3" type="radio" checked="">
    <label title="text" for="star3" style="display: inline-block;"></label>
    <input value="2" name="rate" id="star2" type="radio" >
    <label title="text" for="star2" style="display: inline-block;"></label>
    <input value="1" name="rate" id="star1" type="radio">
    <label title="text" for="star1" style="display: inline-block;"></label>
  </div>
</div>
    <div class="evaluationfeedback">
        <h4>Feedback:</h4>
        <textarea name="Feedback" rows="10" cols="40" placeholder="Write your feedback here"></textarea>
    </div>
</div>
                    <div class="text-center"><input type="submit" class="btn-log" style="margin-left:680px"></div>
              </div>
            </div>
          </div>
        </div>
        </div>
      </form>
    </div>
  </section>

      <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-6 footer-contact">
            <a href="index.html" class="logo me-auto"><img src="img/Lingoblue.png" alt="" class="img-fluid"></a>
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
