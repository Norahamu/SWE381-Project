<?php

DEFINE('DB_USER', 'root');
DEFINE('DB_PSWD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'lingo');

// Establish database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$language = "";
$proficiencyLevel = "";
$sessionDuration = "";
$requestDate = "";
$requestID = ""; // Initialize $requestID

// Check if request ID is set
if(isset($_GET['request_id'])) {
    $requestID = $_GET['request_id'];
    
    // Fetch data from requests_learner table based on request ID
    $sql = "SELECT Language, ProficiencyLevel, SessionDuration, RequestDate FROM requests_learner WHERE RequestID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $requestID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
     
    if (mysqli_num_rows($result) > 0) {
        // Fetch the data
        $row = mysqli_fetch_assoc($result);
        
        // Assign fetched data to variables
        $language = $row['Language'];
        $proficiencyLevel = $row['ProficiencyLevel'];
        $sessionDuration = $row['SessionDuration'];
        $requestDate = $row['RequestDate'];
    } else {
        echo "No records found";
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $language = $_POST['language'];
    $proficiencyLevel = $_POST['level'];
    $sessionDuration = $_POST['duration'];
    $requestDate = $_POST['request_date'];
   
    // Update data in the database
    $updateSql = "UPDATE requests_learner SET Language = ?, ProficiencyLevel = ?, SessionDuration = ?, RequestDate = ? WHERE RequestID = ?";
    $stmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($stmt, "ssssi", $language, $proficiencyLevel, $sessionDuration, $requestDate, $requestID);
    
    if (mysqli_stmt_execute($stmt)) {
        // Redirect to RequestsList.php after successful update
        header("Location: RequestsList.php");
        exit(); // Ensure script execution stops after redirection
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8"> <!-- character encoding-->
 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- viewpoet settings-->
<link rel="stylesheet" type="text/css" href="style.css" media="screen" > 
<link rel="stylesheet" type="text/css" href="buttons.css" > 

<title>Edit Language Learning Request</title>
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
  <section id="req" class="req section-bg">
        <div class="container aos-init aos-animate" data-aos="fade-up">
            <div class="section-title">
                <h2>Edit Language Learning Request</h2>
            </div>
            <div class="row">
                <div class="col-lg-12 mt-5 mt-lg-0 d-flex align-items-stretch">
                <form action="editrequest.php?request_id=<?php echo $requestID; ?>" method="post" class="php-email-form">

                        <div class="row">
                            <div class="form-group">
                                <label for="lang">Language</label>
                                <input type="text" class="form-control" name="language" id="lang" value="<?php echo htmlspecialchars($language); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="level">Proficiency Level</label>

                                <input type="text" class="form-control" name="level" id="level" value="<?php echo htmlspecialchars($proficiencyLevel); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="duration">Session Duration</label>
                                <input type="text"  class="form-control" name="duration" id="duration" value="<?php echo htmlspecialchars($sessionDuration); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="requestDate">Request Date</label>
                                <input type="text" class="form-control" name="request_date" id="request_date" value="<?php echo htmlspecialchars($requestDate); ?>" required>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit">Confirm Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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