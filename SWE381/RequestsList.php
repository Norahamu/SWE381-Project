<?php
DEFINE('DB_USER', 'root');
DEFINE('DB_PSWD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'lingo');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PSWD);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Establish database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data from requests_learner table
$sql_learner = "SELECT rl.RequestID, rl.Status, p.first_name, p.last_name, p.photo 
                FROM requests_learner rl 
                INNER JOIN partners p ON rl.PartnerID = p.partner_id";
$result_learner = mysqli_query($conn, $sql_learner);


// Close connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Requests list</title>
    <!-- icon -->
    <link href="assets/img/Lingoblue.png" rel="icon">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:300,300i,400,400i,600,600i,700,700i&family=Jost:300,300i,400,400i,500,500i,600,600i,700,700i&family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="buttons.css">
    <link href="sessionsStyle.css" rel="stylesheet">
</head>
<body>

<header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center">
        <a href="index.html" class="logo me-auto"><img src="assets/img/Lingowhite.png" alt="Lingo logo" class="img-fluid"></a>
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

<section class="section-bg">
    <div class="section-title">
        <h2>Requests List</h2>
    </div>
    
    <div id="site">
        <div class="sessions">
            <?php
            if (mysqli_num_rows($result_learner) > 0) {
                while ($row_learner = mysqli_fetch_assoc($result_learner)) {
                    echo '<div class="session">';
                    echo '<img src="' . $row_learner['photo'] . '" alt="partner photo" class="userBlue">';

                    echo '<strong class="TPName">' . $row_learner['first_name'] . ' ' . $row_learner['last_name'] . '</strong><br>';
                    echo '<strong class=text2>Status:</strong> <span class="text2">' . $row_learner['Status'] . '</span><br>';

                    if ($row_learner['Status'] == 'Pending') {
                        echo '<div class="button-container">';
                        echo '<button type="button" onclick="editRequest(' . $row_learner['RequestID'] . ')" class="button1">edit</button>';
                        echo '<button type="button" onclick="cancelRequest(' . $row_learner['RequestID'] . ')" class="button2">cancel</button>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            } else {
                echo "No records found in requests_learner";
            }
            ?>
        </div>
    </div>
</section>

<script>
    function editRequest(requestId) {
        // Redirect to the edit request page with the request ID
        window.location.href = 'editrequest.php?request_id=' + requestId;
    }

  
      function cancelRequest(requestId) {
        console.log("Request ID:", requestId);
        // Display a confirmation dialog
        var confirmation = confirm('Are you sure you want to cancel this request?');

        // If user confirms, proceed with cancellation
        if (confirmation) {
            // Use AJAX to send a request to cancel the request
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'cancel_request.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Optionally, you can update the UI to reflect the canceled request
                            alert('Request canceled successfully');
                            // Optionally, you can reload the page or update the UI
                            // window.location.reload();
                        } else {
                            alert('Failed to cancel request');
                        }
                    } else {
                        alert('Failed to cancel request');
                    }
                }
            };
            // Send the request with the request ID
            xhr.send('request_id=' + requestId);
        }
    }
</script>

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
