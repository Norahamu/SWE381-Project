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

function generateRequestID($length = 9) {
    // Generate a random number with 9 digits
    $random_number = mt_rand(100000000, 999999999);
    return $random_number;
}

$requestID = generateRequestID();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $language = $_POST['language'];
    $level = $_POST['level'];
    $sessionDuration = $_POST['sessionDuration'];
    $preferredSchedule = $_POST['preferred_schedule']; // Replaced 'RequestDate' with 'preferred_schedule'
    $status = 'Pending'; // Status could be set to 'Pending' by default, adjust as needed

    // For simplicity, let's assume LearnerID is provided via session or some other means
    $learnerID = 1; // Replace with the actual learner's ID

    // Fetch partner_id from the partners table
    $partnerIDQuery = "SELECT partner_id FROM partners LIMIT 1"; // Assuming you want the first partner_id
    $result = mysqli_query($conn, $partnerIDQuery);
    $row = mysqli_fetch_assoc($result);
    $partnerID = $row['partner_id'];

    // Insert data into requests_partner table
    $sql_partner = "INSERT INTO requests_partner (RequestID, PartnerID, LearnerID, Language, ProficiencyLevel, SessionDuration, preferred_schedule, Status)
    VALUES ('$requestID','$partnerID', '$learnerID', '$language', '$level', '$sessionDuration', '$preferredSchedule', '$status')";

    if ($conn->query($sql_partner) === TRUE) {
        echo "New record created successfully for partner";
    } else {
        echo "Error: " . $sql_partner . "<br>" . $conn->error;
    }

    // Insert data into requests_learner table
    $sql_learner = "INSERT INTO requests_learner (RequestID, LearnerID, PartnerID, Language, ProficiencyLevel, SessionDuration, preferred_schedule, Status)
    VALUES ('$requestID','$learnerID', '$partnerID', '$language', '$level', '$sessionDuration', '$preferredSchedule', '$status')";

    if ($conn->query($sql_learner) === TRUE) {
        echo "New record created successfully for learner";
    } else {
        echo "Error: " . $sql_learner . "<br>" . $conn->error;
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
<header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center">
        <a href="index.html" class="logo me-auto"><img src="assets/img/Lingowhite.png" alt="Lingo logo"
                                                      class="img-fluid"></a>
    </div>
    <nav id="navbar" class="navbar">
        <ul>
            <li><a class="nav-link scrollto" href="HomePage.html">Sign out</a></li>
            <li><a class="nav-link scrollto" href="myprofilelearner.html">My profile</a></li>
            <li><a class="nav-link scrollto" href="currentSessionsLearner.html">Sessions</a></li>
            <li><a class="nav-link scrollto" href="RequestsList.html">Manage Language Learning Request</a></li>
            <li><a class="nav-link scrollto" href="PartnersList.html">Partners List</a></li>
            <li><a class="nav-link scrollto" href="ReviewLearner.html">Review my Partner</a></li>
        </ul>
    </nav>
</header>
<br>
<br>
<br>
<br>
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
                                <option value="Arabic">Arabic</option>
                                <option value="English">English</option>
                                <option value="Français">Français</option>
                                <option value="Español">Español</option>
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
                         <div class="form-group">
                                <label for="preferred_schedule">Preferred Schedule</label> <!-- Changed label text to 'Preferred Schedule' -->
                                <input type="datetime-local" class="form-control" name="preferred_schedule" id="preferred_schedule" placeholder="Preferred Schedule" required> <!-- Changed id and placeholder -->
                        </div>

                   

                    <div class="text-center">
                        <button id="submitButton" type="submit">Post</button>
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
                    <a href="index.html" class="logo me-auto"><img src="assets/img/Lingoblue.png" alt=""
                                                                   class="img-fluid"></a>
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
                        <li><i class="bx bx-chevron-right"></i> <a href="currentSessionsLearner.html">Sessions</a>
                        </li>
                        <li><i class="bx bx-chevron-right"></i> <a href="RequestsList.html">Language Learning
                                Requests</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="PartnersList.html">Partner List</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="ReviewLearner.html">Review my partner</a>
                        </li>
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


