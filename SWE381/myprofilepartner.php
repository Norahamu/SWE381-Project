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

  // Retrieve and sanitize form data
  $firstName = $connection->real_escape_string($_POST['first_name']); 
  $lastName = $connection->real_escape_string($_POST['last_name']); 
  $email = $connection->real_escape_string($_POST['email']); 
  $password = $_POST['password']; // Assuming the password is not hashed for simplicity 
  $city = $connection->real_escape_string($_POST['city']); 
  $location = $connection->real_escape_string($_POST['location']); 
  $age = $connection->real_escape_string($_POST['age']); 
  $gender = $connection->real_escape_string($_POST['gender']); 
  $culturalKnowledge = $connection->real_escape_string($_POST['cultural_Knowledge']); 
  $education = $connection->real_escape_string($_POST['Education']); 
  $experience = $connection->real_escape_string($_POST['Experience']); 
  $pricePerSession = $connection->real_escape_string($_POST['PricePerSession']); 

 // Process proficiency levels for languages
 if (isset($_POST['languages']) && isset($_POST['ProficiencyLevel'])) {
  $languages = $_POST['languages'];
  $ProficiencyLevel = $_POST['ProficiencyLevel'];

  // Update proficiency levels in the database
  foreach ($languages as $index => $language) {
    $ProficiencyLevel = $connection->real_escape_string($ProficiencyLevel[$index]);
    
    // Perform SQL update for each language's proficiency level
    $updateProficiencyQuery = "UPDATE partner_languages SET ProficiencyLevel = '$ProficiencyLevel' WHERE partner_id = '{$_SESSION['partner_id']}' AND language = '$language'";
    $connection->query($updateProficiencyQuery);
    }
  }

 

  // Handle photo upload
  $target_file = null;
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

  // Update partner profile
  $updateQuery = "UPDATE partners SET first_name='$firstName', last_name='$lastName', email='$email', password='$password', photo='$target_file', location='$location', cultural_knowledge='$culturalKnowledge', education='$education', experience='$experience', PricePerSession='$pricePerSession', age='$age', gender='$gender' WHERE partner_id='{$_SESSION['[partner_id']}'";
  if ($connection->query($updateQuery) === TRUE) { 
    echo "<div class='success-message'>Profile updated successfully!</div>"; 
  } else { 
    echo "<div class='error-message'>Error: " . $connection->error . "</div>"; 
  } 

  $connection->close(); 
} 

// Fetch user data for pre-filling the profile form 
$servername = "localhost"; 
$username = "root"; 
$dbPassword = ""; 
$database = "lingo"; 
$connection = new mysqli($servername, $username, $dbPassword, $database); 

$stmtFetch = $connection->prepare("SELECT * FROM partners WHERE partner_id = ?");
$stmtFetch->bind_param("i", $_SESSION['partner_id']); 
$stmtFetch->execute(); 
$resultFetch = $stmtFetch->get_result(); 

// Fetch user data
if ($resultFetch->num_rows > 0) { 
  $userData = $resultFetch->fetch_assoc(); 

  
  // Fetch languages and proficiency levels for the partner
  $stmtLanguages = $connection->prepare("SELECT language, ProficiencyLevel FROM partner_languages WHERE partner_id = ?");
  $stmtLanguages->bind_param("i", $_SESSION['partner_id']); 
  $stmtLanguages->execute(); 
  $resultLanguages = $stmtLanguages->get_result(); 

  $languages = array();
  $ProficiencyLevel= array();

  // Fetch languages and proficiency levels
  while ($row = $resultLanguages->fetch_assoc()) {
    $languages[] = $row['language'];
    $ProficiencyLevel[] = $row['ProficiencyLevel'];
  }

  // Assign user data to variables for pre-filling the form 
  $firstName = $userData['first_name']; 
  $lastName = $userData['last_name']; 
  $email = $userData['email']; 
  $password = $userData['password']; 
  $location = $userData['location']; 
  $photo = $userData['photo']; 
  $age = $userData['age']; 
  $gender = $userData['gender']; 
  $culturalKnowledge = $userData['cultural_Knowledge']; 
  $education = $userData['Education']; 
  $experience = $userData['Experience']; 
  $pricePerSession = $userData['PricePerSession']; 
} else { 
  // User not found, handle the error
} 

$stmtFetch->close(); 
$connection->close(); 

// Handle form submission (delete profile) 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) { 
  $connection = new mysqli($servername, $username, $dbPassword, $database); 
  $stmtDelete = $connection->prepare("DELETE FROM partners WHERE partner_id = ?");
  $stmtDelete->bind_param("i", $_SESSION['partner_id']); 
  if ($stmtDelete->execute()) { 
    header("Location: signuppartner.html"); 
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
      



  <form action="signupPartner.php" method="post" class="php-email-form">
          <div class="row">
            <div class="form-group col-md-6">
                   <label class="required">First Name</label>
              <input type="text" value="<?php echo htmlspecialchars($firstName); ?>" name="fname" class="form-control" id="fname">
            </div>
            <div class="form-group col-md-6">
              <label class="required">Last Name</label>
              <input type="text" value="<?php echo htmlspecialchars($lastName); ?>" name="lname" class="form-control" id="lname" >
            </div>
            <div class="form-group col-md-6">
              <label class="required">Age</label>
              <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>">
            </div>
            <div class="form-group col-md-6">
  <label class="required">Gender</label>
  <select name="gender" class="form-control">
    <option value="">Select Gender</option>
    <option value="female" <?php if ($gender === 'female') echo 'selected'; ?>>Female</option>
    <option value="male" <?php if ($gender === 'male') echo 'selected'; ?>>Male</option>
  </select>
</div>
            <div class="form-group">
              <label class="required">Email</label>
              <input type="email" class="form-control"value="<?php echo htmlspecialchars($email); ?>" name="email" id="email" >
            </div>
            <div class="form-group">
              <label for="psw" class="required">Password</label>
              <div class="input-group">
                  <input type="password" class="form-control" id="psw" value="<?php echo htmlspecialchars($password); ?>"minlength="8" maxlength="15" pattern="^(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,15}$" >
                  <div class="input-group-append">
                      <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                          <i class="fas fa-eye"></i>
                      </button>
                  </div>
              </div>
          </div>  
            <div class="form-group">
              <label>Upload Photo</label>
              <input type="file" class="form-control" name="photo" id="photo">
            </div>
 <div class="checkbox-wrapper-46">
  <div class="checkbox-wrapper-46" id="language-form">
    <label class="required">Click on the languages you want to teach and select your proficiency:</label>
    <div class="language-selection">
      <label class="cbx" for="cbx-46-arabic">
        <input class="inp-cbx" id="cbx-46-arabic" type="checkbox" name="languages[]" value="Arabic" <?php if (in_array('Arabic', $languages)) echo 'checked'; ?>/>
        <span>Arabic</span>
      </label>
      <select name="proficiency_levels[]" class="form-control" <?php if (!in_array('Arabic', $languages)) echo 'disabled'; ?>>
        <option value="">Select proficiency</option>
        <option value="Beginner" <?php if ($proficiency_levels['Arabic'] === 'Beginner') echo 'selected'; ?>>Beginner</option>
        <option value="Intermediate" <?php if ($proficiency_levels['Arabic'] === 'Intermediate') echo 'selected'; ?>>Intermediate</option>
        <option value="Advanced" <?php if ($proficiency_levels['Arabic'] === 'Advanced') echo 'selected'; ?>>Advanced</option>
      </select>
    </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.inp-cbx').forEach(function(checkbox) {
      checkbox.addEventListener('change', function() {
        let selectElement = checkbox.closest('.language-selection').querySelector('.form-control');
        selectElement.disabled = !checkbox.checked;
        if (!checkbox.checked) {
          selectElement.value = '';
        }
      });
    });
  });
</script>
        <div class="form-group"></div>
          <label class="required">Cultural Knowledge</label>
          <textarea class="form-control"value="<?php echo htmlspecialchars($culturalKnowledge); ?>" name="culturalknowledge" id="cultural_knowledge" rows="5"></textarea>       </div>
        <div class="form-group">
          <label class="required">Education</label>
          <textarea class="form-control"value="<?php echo htmlspecialchars($education); ?>"name="experience" id="eduaction" rows="5" ></textarea>
        </div>
        <div class="form-group">
          <label class="required">Experience</label>
          <textarea class="form-control"value="<?php echo htmlspecialchars($experience); ?>"name="experience" id="experience" rows="5" ></textarea>
        </div>
        <div class="form-group">
          <label for="location" class="required">Location</label>
          <input type="text" name="location" class="form-control" id="location" value="<?php echo htmlspecialchars($location); ?>">
        </div>
        <div class="form-group col-md-6">
<label class="required">Price per session</label>
<input type="number" class="form-control" value="<?php echo htmlspecialchars($pricePerSession); ?>" name="price" id="price" min="50" step="1" >
        </div>
       <div class="text-center" style="display: flex; justify-content: space-between;">
        <button type="submit" id="save-changes-btn" style="margin-right: auto;">Save Changes</button>
        <button type="submit" name="delete_account" style="background-color: red;">Delete my account</button>
    </div>
      </div>
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
