<?php
session_start();
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

function generateRequestID($length = 9) {
    // Generate a random number with 9 digits
    $random_number = mt_rand(100000000, 999999999);
    return $random_number;
}

$requestID = generateRequestID();

// Check if partnerID is provided via query parameter
if(isset($_GET['partnerID'])) {
    $partnerID = $_GET['partnerID'];

    // Fetch partner's languages from the database
    $partnerLanguagesQuery = "SELECT language FROM partner_languages WHERE partner_id = '$partnerID'";
    $partnerLanguagesResult = mysqli_query($conn, $partnerLanguagesQuery);

    // Initialize an array to store languages
    $languages = array();

    // Fetch languages and store them in the array
    while ($row = mysqli_fetch_assoc($partnerLanguagesResult)) {
        $languages[] = $row['language'];
    }
} else {
    // Default to an empty array if partnerID is not provided
    $languages = array();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $language = $_POST['language'];
    $level = $_POST['level'];
    $sessionDuration = $_POST['sessionDuration'];
    $preferredSchedule = $_POST['preferred_schedule'];
    $status = 'Pending';
    if(isset($_SESSION['learner_id'])){
        $learnerID = $_SESSION['learner_id'];
    }
   // Assuming the learner ID is retrieved from session or elsewhere

    // Retrieve partner ID from the query parameter
    if(isset($_GET['partnerID'])) {
        $partnerID = $_GET['partnerID'];

        // Check if the partner ID exists in the database
        $partnerCheckQuery = "SELECT COUNT(*) AS count FROM partners WHERE partner_id = '$partnerID'";
        $partnerCheckResult = mysqli_query($conn, $partnerCheckQuery);
        $partnerCheckRow = mysqli_fetch_assoc($partnerCheckResult);
        $partnerCount = $partnerCheckRow['count'];

        if ($partnerCount == 1) {
                        // Insert data into requests_partner table
                $sql_partner = "INSERT INTO requests_partner (RequestID, PartnerID, LearnerID, Language, ProficiencyLevel, SessionDuration, preferred_schedule, Status, request_date)
                VALUES ('$requestID', '$partnerID', '$learnerID', '$language', '$level', '$sessionDuration', '$preferredSchedule', '$status', NOW())";

                // Insert data into requests_learner table
                $sql_learner = "INSERT INTO requests_learner (RequestID, LearnerID, PartnerID, Language, ProficiencyLevel, SessionDuration, preferred_schedule, Status, request_date)
                VALUES ('$requestID', '$learnerID', '$partnerID', '$language', '$level', '$sessionDuration', '$preferredSchedule', '$status', NOW())";

            // Execute both queries
            if ($conn->query($sql_partner) === TRUE && $conn->query($sql_learner) === TRUE) {
                // Both queries executed successfully
                // Redirect to RequestsList.php
                header("Location: RequestsList.php");
                exit(); // Ensure script execution stops after redirection
            } else {
                // Error occurred in query execution
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Error: Invalid partner ID";
        }
    } else {
        echo "Error: Partner ID not provided";
    }
}

// Close connection
mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css" media="screen">
    <title>Post Language Learning Request</title>
    <link href="assets/img/Lingoblue.png" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:300,300i,400,400i,600,600i,700,700i&family=Jost:300,300i,400,400i,500,500i,600,600i,700,700i&family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    
    <script>
    function validateForm() {
    const language = document.getElementById("lang").value;
    const level = document.getElementById("level").value;
    const sessionDuration = document.getElementById("sessionDuration").value;
    const preferredSchedule = document.getElementById("preferred_schedule").value;


    // Regular expression to match the format YYYY-MM-DDTHH:MM
    const dateTimeRegex = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;


    if (language === "" || level === "" || sessionDuration === "" || preferredSchedule === "") {
        alert("Please fill out all fields.");
        return false;
    }


    if (!dateTimeRegex.test(preferredSchedule)) {
        alert("Invalid Date and Time Format. Please enter the date and time in the format YYYY-MM-DDTHH:MM.");
        return false;
    }


    // Additional validation for the preferredSchedule
    const dateParts = preferredSchedule.split("T")[0].split("-");
    const timeParts = preferredSchedule.split("T")[1].split(":");
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
      </ul>
    </nav>
  </header>
  <!-- End Header -->

<section id="req" class="req section-bg">
    <div class="container aos-init aos-animate" data-aos="fade-up">
        <div class="section-title">
            <h2>Post a Language Learning Request</h2></div>
        <div class="row">
            <div class="col-lg-12 mt-5 mt-lg-0 d-flex align-items-stretch">
            <form action="" method="post" class="php-email-form" id="languageForm">
    <div class="row">
        <div class="form-group">
            <label for="lang">Language</label>
            <select class="form-control" name="language" id="lang" required="">
                                <option value="" disabled selected>Select Language</option>
                                <?php foreach($languages as $language): ?>
                                    <option value="<?php echo $language; ?>"><?php echo $language; ?></option>
                                <?php endforeach; ?>
                            </select>
        </div>


        <div class="form-group">
            <label for="level">Proficiency Level</label>
            <select class="form-control" name="level" id="level" required="">
                <option value="" disabled selected>Select Proficiency Level</option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select>
        </div>


        <div class="form-group">
            <label for="sessionDuration">Session Duration</label>
            <select class="form-control" name="sessionDuration" id="sessionDuration" required="">
                <option value="" disabled selected>Select Session Duration</option>
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
            <input type="datetime-local" class="form-control" name="preferred_schedule" id="preferred_schedule" placeholder="Preferred Schedule" required>
        </div>


        <!-- Hidden input field for partner ID -->
        <input type="hidden" name="partnerID" id="partnerID" value="1"> <!-- Replace '1' with the actual partner ID -->
    </div>


    <div class="text-center">
        <button id="submitButton" class="btn-sign"type="submit">Post</button>
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
              <li><i class="bx bx-chevron-right"></i> <a href="logout.php">Sign out</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="myprofilelearner.php">My profile</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="currentSessionsLearner.php">Sessions</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="RequestsList.php">Language Learning Requests</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="PartnerList.php">Partner List</a></li>
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