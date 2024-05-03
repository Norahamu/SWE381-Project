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

$requestID = "";

// Check if request ID is set
if (isset($_GET['request_id'])) {
    $requestID = $_GET['request_id'];

    // Fetch data from requests_learner table based on request ID
    $sql = "SELECT Language, ProficiencyLevel, SessionDuration, preferred_schedule, PartnerID FROM requests_learner WHERE RequestID = ?";
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
        $preferredSchedule = $row['preferred_schedule'];
        $partnerID = $row['PartnerID'];

        // Fetch partner's languages from the database
        $partnerLanguagesQuery = "SELECT language FROM partner_languages WHERE partner_id = ?";
        $stmtPartnerLanguages = mysqli_prepare($conn, $partnerLanguagesQuery);
        mysqli_stmt_bind_param($stmtPartnerLanguages, "i", $partnerID);
        mysqli_stmt_execute($stmtPartnerLanguages);
        $partnerLanguagesResult = mysqli_stmt_get_result($stmtPartnerLanguages);

        // Initialize an array to store languages
        $languages = array();

        // Fetch languages and store them in the array
        while ($lang_row = mysqli_fetch_assoc($partnerLanguagesResult)) {
            $languages[] = $lang_row['language'];
        }
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
    $preferredSchedule = $_POST['preferred_schedule'];
   
    // Update data in the requests_learner table
    $updateSqlLearner = "UPDATE requests_learner SET Language = ?, ProficiencyLevel = ?, SessionDuration = ?, preferred_schedule = ? WHERE RequestID = ?";
    $stmtLearner = mysqli_prepare($conn, $updateSqlLearner);
    mysqli_stmt_bind_param($stmtLearner, "ssssi", $language, $proficiencyLevel, $sessionDuration, $preferredSchedule, $requestID);
    
    // Update data in the requests_partner table
    $updateSqlPartner = "UPDATE requests_partner SET Language = ?, ProficiencyLevel = ?, SessionDuration = ?, preferred_schedule = ? WHERE RequestID = ?";
    $stmtPartner = mysqli_prepare($conn, $updateSqlPartner);
    mysqli_stmt_bind_param($stmtPartner, "ssssi", $language, $proficiencyLevel, $sessionDuration, $preferredSchedule, $requestID);
    
    // Execute both update queries
    $learnerUpdateResult = mysqli_stmt_execute($stmtLearner);
    $partnerUpdateResult = mysqli_stmt_execute($stmtPartner);
    
    if ($learnerUpdateResult && $partnerUpdateResult) {
        // Redirect to RequestsList.php after successful update
        header("Location: RequestsList.php");
        exit(); // Ensure script execution stops after redirection
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
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
  <script>
function validateForm() {
    const language = document.getElementById("lang").value;
    const level = document.getElementById("level").value;
    const sessionDuration = document.getElementById("duration").value;
    const requestDateTime = document.getElementById("request_date").value;

    // Regular expression to match the format YYYY-MM-DDTHH:MM
    const dateTimeRegex = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;

    if (language === "" || level === "" || sessionDuration === "" || requestDateTime === "") {
        alert("Please fill out all fields.");
        return false;
    }

    if (!dateTimeRegex.test(requestDateTime)) {
        alert("Invalid Date and Time Format. Please enter the date and time in the format YYYY-MM-DDTHH:MM.");
        return false;
    }

    // Additional validation for the requestDateTime
    const dateParts = requestDateTime.split("T")[0].split("-");
    const timeParts = requestDateTime.split("T")[1].split(":");
    const year = parseInt(dateParts[0], 10);
    const month = parseInt(dateParts[1], 10);
    const day = parseInt(dateParts[2], 10);
    const hour = parseInt(timeParts[0], 10);
    const minute = parseInt(timeParts[1], 10);

    // Detailed check for valid date ranges
    const dayObj = new Date(year, month - 1, day, hour, minute);

    if (dayObj.getFullYear() !== year || dayObj.getMonth() + 1 !== month || dayObj.getDate() !== day ||
        dayObj.getHours() !== hour || dayObj.getMinutes() !== minute) {
        alert("Invalid Date or Time range detected. Please correct and submit again.");
        return false;
    }

    return true;
}
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
    <li><a class="nav-link scrollto " href="logout.php">Sign out</a></li>
                    <li><a class="nav-link scrollto" href="myprofilelearner.php">My profile</a></li>
                    <li><a class="nav-link scrollto" href="currentSessionsLearner.php">Sessions</a></li>
                    <li><a class="nav-link scrollto" href="RequestsList.php">Manage Language Learning Request</a></li>
                    <li><a class="nav-link scrollto" href="PartnerList.php">Partners List</a></li>
                    <li><a class="nav-link scrollto" href="ReviewLearner.php">Review my Partner</a></li>
                
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
        <label for="language">Language:</label>
                            <select class="form-control" id="lang" name="language">
                                <?php foreach ($languages as $lang) : ?>
                                    <option value="<?php echo $lang; ?>" <?php if ($language === $lang) echo "selected"; ?>><?php echo $lang; ?></option>
                                <?php endforeach; ?>
                            </select>
        </div>
        <div class="form-group">
            <label for="level">Proficiency Level</label>
            <select class="form-control" name="level" id="level" required>
                <option value="<?php echo htmlspecialchars($proficiencyLevel); ?>"><?php echo htmlspecialchars($proficiencyLevel); ?></option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select>
        </div>
        <div class="form-group">
                            <label for="duration">Session Duration</label>
                            <select class="form-control" name="duration" id="duration" required>
                                <option value="<?php echo htmlspecialchars($sessionDuration); ?>"><?php echo htmlspecialchars($sessionDuration); ?> hours</option>
                                <option value="1">1 hour</option>
                                <option value="2">2 hours</option>
                                <option value="3">3 hours</option>
                                <option value="4">4 hours</option>
                                <option value="5">5 hours</option>
                                <option value="6">6 hours</option>
                            </select>
                        </div>
        <div class="form-group">
            <label for="preferred_schedule">Preferred Schedule</label>
            <input type="datetime-local" class="form-control" name="preferred_schedule" id="preferred_schedule" value="<?php echo htmlspecialchars($preferredSchedule); ?>" required>
        </div>
    </div>
    <div class="text-center">
        <button type="submit">Confirm Edit</button>
    </div>
</form>
...

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
			  <li><i class="bx bx-chevron-right"></i> <a href="logout.php">Sign out</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="myprofilelearner.php">My profile</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="currentSessionsLearner.php">Sessions</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="RequestsList.php">Language Learning Requests</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="PartnerList.php">Partner List</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="ReviewLearner.php">Review my partner</a></li>
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
