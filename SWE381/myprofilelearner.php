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
  $password = $_POST['password']; // Assuming the password is not hashed for simplicity 
  $city = $connection->real_escape_string($_POST['city']); 
  $location = $connection->real_escape_string($_POST['location']); 
 
  // If email already exists 
  $checkEmailQuery = "SELECT email FROM learners WHERE email = ?"; 
  $stmt = $connection->prepare($checkEmailQuery); 
  $stmt->bind_param("s", $email); 
  $stmt->execute(); 
  $result = $stmt->get_result(); 
  $stmt->close(); 
  if ($result->num_rows > 0) { 
    echo "<script>alert('The email address is already registered. Please use another email.'); window.location.href='signuplearner.html';</script>"; 
    exit; 
  } 
 
  $target_file = null; 
  // Check if file is uploaded 
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) { 
    $fileTmpPath = $_FILES['photo']['tmp_name']; 
    $fileName = $_FILES['photo']['name']; 
    $target_dir = "assets/img/"; 
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION); 
    $newFileName = $firstName . $lastName . "." . $fileExt; 
    $target_file = $target_dir . $newFileName; 
 
    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) { 
      echo "Sorry, there was an error uploading your file."; 
      exit; 
    } 
  } 
 
  $stmt = $connection->prepare("UPDATE learners SET first_name=?, last_name=?, email=?, password=?, photo=?, city=?, location=? WHERE learner_id=?"); 
  $stmt->bind_param("sssssssi", $firstName, $lastName, $email, $password, $target_file, $city, $location, $_SESSION['user_id']); 
 
  if ($stmt->execute()) { 
    echo "<div class='success-message'>Profile updated successfully!</div>"; 
  } else { 
    echo "<div class='error-message'>Error: " . $stmt->error . "</div>"; 
  } 
 
  $stmt->close(); 
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
 
// Handle form submission (delete profile) 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) { 
  // Delete user from database 
  $connection = new mysqli($servername, $username, $dbPassword, $database); 
  $stmtDelete = $connection->prepare("DELETE FROM learners WHERE learner_id = ?"); 
  $stmtDelete->bind_param("i", $_SESSION['user_id']); 
  if ($stmtDelete->execute()) { 
    // User deleted successfully, redirect to sign out or any other page 
    // For example: 
    header("Location: signuplearner.php"); 
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
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- viewpoet settings--> 
  <link rel="stylesheet" type="text/css" href="style.css" media="screen"> 
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
 
  <title>My Profile Learner</title> 
  <!-- icon --> 
  <link href="assets/img/Lingoblue.png" rel="icon"> 
 
  <!-- Google Fonts --> 
  <link rel="stylesheet" 
    href="https://fonts.googleapis.com/css2?family=Open+Sans:300,300i,400,400i,600,600i,700,700i&family=Jost:300,300i,400,400i,500,500i,600,600i,700,700i&family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"> 
 
 
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
      <a href="index.html" class="logo me-auto"><img src="assets/img/Lingowhite.png" alt="Lingo logo" 
          class="img-fluid"></a> 
    </div> 
    <nav id="navbar" class="navbar"> 
      <ul> 
        <li><a class="nav-link scrollto " href="HomePage.html">Sign out</a></li> 
        <li><a class="nav-link scrollto" href="myprofilelearner.php">My profile</a></li> 
        <li><a class="nav-link scrollto" href="currentSessionsLearner.html">Sessions</a></li> 
        <li><a class="nav-link scrollto" href="RequestsList.html">Manage Language Learning Request</a></li> 
        <li><a class="nav-link scrollto" href="PartnersList.html">Partners List</a></li> 
        <li><a class="nav-link scrollto" href="ReviewLearner.html">Review my Partner</a></li> 
      </ul> 
 
    </nav> 
  </header> 
  <!-- End Header --> 
  \ 
  <section id="signuplearner" class="signuplearner section-bg"> 
    <div class="container aos-init aos-animate" data-aos="fade-up"> 
      <div class="section-title"> 
        <h2>My Profile</h2> 
      </div> 
      <div class="row"> 
 
        <div class="col-lg-12 mt-9 mt-lg-0 d-flex align-items-stretch"> 
          <form action="#" method="post" class="php-email-form"> 
            <div class="row"> 
              <div class="form-group col-md-6"> 
 
                <label class="required">First Name</label> 
                <input type="text" name="first_name" class="form-control" id="fname" 
                  value="<?php echo htmlspecialchars($firstName); ?>" > 
 
              </div> 
              <div class="form-group col-md-6"> 
                <label class="required">Last Name</label> 
                <input type="text" name="last_name" class="form-control" id="lname" 
                  value="<?php echo htmlspecialchars($lastName); ?>" > 
 
              </div> 
              <div class="form-group"> 
                <label class="required">Email</label> 
                <input type="email" name="email" class="form-control" id="email" 
                  value="<?php echo htmlspecialchars($email); ?>"> 
              </div> 
            </div> 
            <div class="form-group"> 
              <label class="required">Password</label> 
              <input type="password" class="form-control"  value="<?php echo htmlspecialchars($password); ?>" name="Password" id="psw" > 
            </div> 
            <div class="form-group"> 
              <label>Upload Photo</label> 
              <input type="file" class="form-control" name="photo" id="photo"> 
            </div> 
            <div class="form-group"> 
              <label class="required">City</label> 
              <input type="text" class="form-control"  value="<?php echo htmlspecialchars($city); ?>"name="city" id="city" required>
              </div> 
            <div class="form-group"> 
              <label class="required">Location</label> 
              <input type="text" class="form-control"  value="<?php echo htmlspecialchars($location); ?>" name="location" id="location" 
                required=""> 
            </div> 
 
            <div class="text-center" style="display: flex; justify-content: space-between;"> 
              <button type="submit" id="save-changes-btn" style="margin-right: auto;">Save Changes</button> 
 
              <button type="submit" name="delete_account" style="background-color: red;">Delete my account</button> 
 
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
              <!-- Links here --> 
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
 
    // Function to handle input change 
    function handleInputChange() { 
      changesMade = true; 
    } 
 
    // Add event listeners to input fields 
    var inputFields = document.querySelectorAll('input, textarea'); 
    inputFields.forEach(function (input) { 
      input.addEventListener('input', handleInputChange); 
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
  </script> 
 
</body> 
 
</html>
