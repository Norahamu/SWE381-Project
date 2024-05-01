<?php

DEFINE('DB_USER', 'root');
DEFINE('DB_PSWD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'lingo2');

// Establish database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $language = $_POST['language'];
    $level = $_POST['level'];
    $sessionDuration = $_POST['sessionDuration'];
    $requestDate = date('Y-m-d H:i:s'); // Get the current date and time
    $status = 'Pending'; // Status could be set to 'Pending' by default, adjust as needed

    // For simplicity, let's assume LearnerID and PartnerID are provided via session or some other means
    $learnerID = 1; // Replace with the actual learner's ID
    $partnerID = 1; // Replace with the actual partner's ID

    // Insert data into requests_learner table
    $sql = "INSERT INTO requests_learner (LearnerID, PartnerID, Language, ProficiencyLevel, SessionDuration, RequestDate, Status)
    VALUES ('$learnerID', '$partnerID', '$language', '$level', '$sessionDuration', '$requestDate', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully for learner";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Insert data into requests_partner table
    $sql = "INSERT INTO requests_partner (PartnerID, LearnerID, Language, ProficiencyLevel, SessionDuration, RequestDate, Status)
    VALUES ('$partnerID', '$learnerID', '$language', '$level', '$sessionDuration', '$requestDate', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully for partner";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
        document.addEventListener("DOMContentLoaded", function() {
            // Find the form and submit button
            const form = document.getElementById("languageForm");
            const submitButton = document.getElementById("submitButton");

            // Add event listener to the submit button
            submitButton.addEventListener("click", function(event) {
                // Prevent default form submission
                event.preventDefault();

                // Serialize form data
                const formData = new FormData(form);

                // Submit the form data using fetch API
                fetch("submit_request.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Redirect to RequestsList.php after successful submission
                        window.location.href = "RequestsList.php";
                    } else {
                        console.error("Error submitting form:", response.statusText);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            });
        });
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
                            <input type="text" class="form-control" placeholder="Enter the language" name="language"
                                   id="lang" required="">
                        </div>
                        <div class="form-group">
                            <label for="level">Proficiency Level</label>
                            <input type="text" class="form-control" placeholder="Enter your proficiency level"
                                   name="level" id="level" required="">
                        </div>
                        <div class="form-group">
                            <label for="sessionDuration">Session Duration</label>
                            <input type="text" class="form-control" placeholder="Enter the session duration"
                                   name="sessionDuration" id="sessionDuration" required="">
                        </div>
                    </div>
                    <p>Preferred Schedule</p>
                    <input type="datetime-local" name="preferredSchedule">
                    <div class="text-center">
                        <button id="submitButton" type="button">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

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