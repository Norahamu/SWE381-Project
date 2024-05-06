<?php 
session_start(); 
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
  $servername = "localhost"; 
  $username = "root"; 
  $dbPassword = ""; 
  $database = "lingo"; 
  $connection = new mysqli($servername, $username, $dbPassword, $database); 
 
  if ($connection->connect_error) { 
    die("Connection failed: " . $connection->connect_error); 
  } 
 
  $firstName = $connection->real_escape_string($_POST['first_name']); 
  $lastName = $connection->real_escape_string($_POST['last_name']); 
  $email = $connection->real_escape_string($_POST['email']); 
  $password = $connection->real_escape_string($_POST['password']); 
  $city = $connection->real_escape_string($_POST['city']); 
  $location = $connection->real_escape_string($_POST['location']); 
  
$old_image=$_POST['image_old'];
$photo=$_FILES['photo']['name'];


if($photo!=null){

  $update_filename=$photo;
}

else{
  $update_filename=$old_image;

}
 
 
 
// Check if the provided email already exists for another user
$checkEmailQuery = "SELECT * FROM learners WHERE email = '$email' AND learner_id != '{$_SESSION['learner_id']}'";
$result = $connection->query($checkEmailQuery);

if ($result->num_rows > 0) {
  // Email address is already registered for another user
  $_SESSION['email_already_registered'] = true;
} else {

  
     //UPDATE
  $stmt = $connection->prepare("UPDATE learners SET first_name=?, last_name=?, email=?, password=?, photo=?, city=?, location=? WHERE learner_id=?"); 
  $stmt->bind_param("sssssssi", $firstName, $lastName, $email, $password, $update_filename, $city, $location, $_SESSION['learner_id']); 
 
  if ($stmt->execute()) {
    // Store success message in session variable
    $_SESSION['profile_updated_success'] = true;
} else {
    echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
}

$stmt->close();

$connection->close(); 
} 
}


// Fetch user data for pre-filling the profile form 
$servername = "localhost"; 
$username = "root"; 
$dbPassword = ""; 
$database = "lingo"; 
$connection = new mysqli($servername, $username, $dbPassword, $database); 
 
$stmtFetch = $connection->prepare("SELECT * FROM learners WHERE learner_id = ?"); 
$stmtFetch->bind_param("i", $_SESSION['learner_id']); 
$stmtFetch->execute(); 
$resultFetch = $stmtFetch->get_result(); 
 
// Check if user exists 
if ($resultFetch->num_rows > 0) { 
  // Fetch user data 
  $userData = $resultFetch->fetch_assoc(); 
 
  // Assign user data to variables for pre-filling the form 
  $firstName = $userData['first_name']; 
  $lastName = $userData['last_name']; 
  $email = $userData['email']; 
  $password = $userData['password']; // Assuming the password is stored in the database 
  $city = $userData['city']; 
  $location = $userData['location']; 
  $photo = $userData['photo']; 
} else { 
  // User not found, handle the error (e.g., redirect to an error page) 
  // die("User not found."); 
} 
 
// Close the prepared statement and database connection 
$stmtFetch->close(); 
$connection->close(); 
 
/// Handle form submission (delete profile) 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) { 
  $connection = new mysqli($servername, $username, $dbPassword, $database); 
  $stmtDelete = $connection->prepare("DELETE FROM learners WHERE learner_id = ?");
  $stmtDelete->bind_param("i", $_SESSION['learner_id']); 
  if ($stmtDelete->execute()) { 
    header("Location: signuplearner.html"); 
    exit(); 
  } else { 
    echo "<div class='error-message'>Error: " .
      $stmtDelete->error . "</div>"; 
  } 
  $stmtDelete->close(); 
  $connection->close(); 
} 

 
?> 
 
 <!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8"> <!-- character encoding-->
 <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- viewport settings-->
<link rel="stylesheet" type="text/css" href="style.css" media="screen" > 
<title>Previous Sessions Learner</title>
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
  <link href="style.css" rel="stylesheet">
  <!-- JS Files -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
 
</head> 
 
<body> 
  <!-- ======= Header ======= --> 
  <header id="header" class="fixed-top header-inner-pages"> 
  <div class="container d-flex align-items-center"> 
    <a href="index.html" class="logo me-auto">
    
    </a> 
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
  <script>
$(document).ready(function() {
    $("#togglePassword").click(function() {
        var password = $("#password");
        var type = password.attr("type") === "password" ? "text" : "password";
        password.attr("type", type);
        
        // toggle the eye / eye-slash icon
        $(this).toggleClass("fa-eye fa-eye-slash");
    });
});
</script>
  <section id="signuplearner" class="signuplearner section-bg"> 
    <div class="container aos-init aos-animate" data-aos="fade-up"> 
      <div class="section-title"> 
        <h2>My Profile</h2> 
       
      </div> 
      <div class="row"> 
 
        <div class="col-lg-12 mt-9 mt-lg-0 d-flex align-items-stretch"> 
          <form action="#" method="post" class="php-email-form" enctype="multipart/form-data"> 
            <div class="row"> 
              <div class="form-group col-md-6"> 
 
                <label class="required">First Name</label> 
                <input type="text" name="first_name" class="form-control" id="first_name" 
                  value="<?php echo htmlspecialchars($firstName); ?>" required> 
 
              </div> 
              <div class="form-group col-md-6"> 
                <label class="required">Last Name</label> 
                <input type="text" name="last_name" class="form-control" id="last_name" 
                  value="<?php echo htmlspecialchars($lastName); ?>" required> 
 
              </div> 
              <div class="form-group"> 
                <label class="required">Email</label> 
                <input type="email" name="email" class="form-control" id="email" 
                  value="<?php echo htmlspecialchars($email); ?>"required> 
              </div> 
            </div> 
            
            <div class="form-group">
              <label for="psw" class="required">Password</label>
              <div class="input-group">
                  <input type="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>" id="psw" name="password" placeholder="Enter your password" required minlength="8" maxlength="15" pattern="^(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,15}$" title="Password must be 8-15 characters long and include at least one special character."required>
                  
              </div>
          </div>  


            <div class="form-group"> 
              <label>Upload Photo</label> 
              <input type="file" class="form-control" name="photo" id="photo" > 
              <input type=hidden name="image_old" value="<?php echo $photo;?>">
            </div> 
            <div class="form-group"> 
              <label class="required">City</label> 
              <input type="text" class="form-control"  value="<?php echo htmlspecialchars($city); ?>"name="city" id="city" required>
              </div> 
            <div class="form-group"> 
              <label class="required">Location</label> 
              <input type="text" class="form-control"  value="<?php echo htmlspecialchars($location); ?>" name="location" id="location" required> 
  
            </div> 
 
            <div class="text-center" style="display: flex; justify-content: space-between;"> 
              <button type="submit" id="save-changes-btn" style="margin-right: auto;">Save Changes</button> 
 
              <button type="button" id="delete-account-btn" name="delete_account" onclick="confirmDelete()" style="background-color: red; border: 0; padding: 12px 34px; color: #fff; transition: 0.4s; border-radius: 50px;">Delete my account</button>

       
       <script>
    function confirmDelete() {
        if (confirm("Are you sure you want to delete your account?")) {
            window.location.href = "delete_account.php";
        }
    }
</script> 
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
  <script>
    // Variable to track changes
    var changesMade = false;

    // Function to handle input and selection change
    function handleInputChange() {
      changesMade = true;
    }

    // Add event listeners to input fields
    var inputFields = document.querySelectorAll('input, textarea, select');
    inputFields.forEach(function (input) {
      input.addEventListener('input', handleInputChange);
      input.addEventListener('change', handleInputChange); // Adding change event listener
    });

    // Add event listener to the "Save Changes" button
    var saveChangesBtn = document.getElementById('save-changes-btn');
    saveChangesBtn.addEventListener('click', function (event) {
      // Check if changes have been made
      if (!changesMade) {
        event.preventDefault(); // Prevent form submission if no changes
        alert('No changes made.');
      }
    });

    <?php
    if (isset($_SESSION['profile_updated_success']) && $_SESSION['profile_updated_success']) {
        echo "var profileUpdatedSuccess = true;";
        unset($_SESSION['profile_updated_success']); // Unset session variable after handling
    } else {
        echo "var profileUpdatedSuccess = false;";
    }
    ?>

    // Add this JavaScript code to handle the success message
    if (profileUpdatedSuccess) {
        alert('Profile updated successfully!');
    }

    <?php
    if (isset($_SESSION['email_already_registered']) && $_SESSION['email_already_registered']) {
        echo "var emailAlreadyRegistered = true;";
        unset($_SESSION['email_already_registered']); // Unset session variable after handling
    } else {
        echo "var emailAlreadyRegistered = false;";
    }
    ?>

    // Add this JavaScript code to handle the email already registered message
    if (emailAlreadyRegistered) {
        alert('The email address is already registered. Please use another email.');
    }
</script> 
</body> 
 
</html>
