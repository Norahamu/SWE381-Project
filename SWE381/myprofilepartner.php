<?php
session_start();

// Define variables

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
    $password = $connection->real_escape_string($_POST['password']);
    $location = $connection->real_escape_string($_POST['location']);
    $age = $connection->real_escape_string($_POST['age']);
    $gender = $connection->real_escape_string($_POST['gender']);
    $culturalKnowledge = addslashes($connection->real_escape_string($_POST['cultural_knowledge']));
    $education = addslashes($connection->real_escape_string($_POST['Education']));
    $experience = addslashes($connection->real_escape_string($_POST['Experience']));
    $pricePerSession = $connection->real_escape_string($_POST['PricePerSession']);
    $old_image=$_POST['image_old'];
    $photo=$_FILES['photo']['name'];
    $languages = $_POST['language']; // Array of selected languages
    $proficiencyLevels = $_POST['ProficiencyLevel']; // Array of corresponding proficiency levels

    
    if($photo!=null){
    
      $update_filename=$photo;
    }
    
    else{
      $update_filename=$old_image;
    
    }
    
        // Check if file is uploaded
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $target_dir = "assets/img/";
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = $firstName . $lastName . "." . $fileExt;
            $update_filename = $target_dir . $newFileName;
        
            if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $update_filename)) {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        }
    // Check if the provided email already exists for another user
    $checkEmailQuery = "SELECT * FROM partners WHERE email = '$email' AND partner_id != '{$_SESSION['partner_id']}'";
    $result = $connection->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        // Email address is already registered for another user
        $_SESSION['email_already_registered'] = true;
    } else {

        // Handle form submissions
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['save_changes'])) {

                //UPDATE

              // Check if language and proficiency level arrays are set and not empty
if (!isset($languages) || !isset($proficiencyLevels) || empty($languages) || empty($proficiencyLevels)) {
  echo "<script>alert('Please select at least one language and proficiency level.'); window.location.href='myprofilepartner.php'</script>";
  exit;
}

// Validate that languages and proficiency levels are arrays
if (!is_array($languages) || !is_array($proficiencyLevels)) {
  echo "<script>alert('Invalid data for languages or proficiency levels.'); window.location.href='myprofilepartner.php'</script>";
  exit;
}

// Check if the number of languages matches the number of proficiency levels
if (count($languages) !== count($proficiencyLevels)) {
  echo "<script>alert('Each selected language must have a corresponding proficiency level.'); window.location.href='myprofilepartner.php'</script>";
  exit;
}
else{
                $stmt = $connection->prepare("UPDATE partners SET first_name=?, last_name=?, email=?, password=?, photo=?, location=?, cultural_knowledge=?, Education=?, Experience=?, PricePerSession=?, age=?, gender=? WHERE partner_id=?");
                $stmt->bind_param("ssssssssssssi", $firstName, $lastName, $email, $password, $update_filename, $location, $culturalKnowledge, $education, $experience, $pricePerSession, $age, $gender, $_SESSION['partner_id']);
               // Update languages and proficiency levels in the database
        if (isset($_POST['language']) && isset($_POST['ProficiencyLevel'])) {
          $languages = $_POST['language']; // Array of selected languages
          $proficiencyLevels = $_POST['ProficiencyLevel']; // Array of corresponding proficiency levels

          // First, delete unchecked languages
          $uncheckedLanguages = array_diff(getExistingLanguages(), $languages);
          foreach ($uncheckedLanguages as $uncheckedLanguage) {
              $deleteLanguageQuery = "DELETE FROM partner_languages WHERE partner_id = '{$_SESSION['partner_id']}' AND language = '$uncheckedLanguage'";
              $connection->query($deleteLanguageQuery);
          }

          // Then, update existing languages and insert new ones
          foreach ($languages as $index => $language) {
              $proficiencyLevel = $proficiencyLevels[$index]; // Get proficiency level for this language

              // Check if the language already exists for the user
              $checkLanguageQuery = "SELECT * FROM partner_languages WHERE partner_id = '{$_SESSION['partner_id']}' AND language = '$language'";
              $result = $connection->query($checkLanguageQuery);

              if ($result->num_rows > 0) {
                  // Language already exists, check if proficiency level has changed
                  $row = $result->fetch_assoc();
                  if ($row['ProficiencyLevel'] !== $proficiencyLevel) {
                      // Update proficiency level
                      $updateLanguageQuery = "UPDATE partner_languages SET ProficiencyLevel = '$proficiencyLevel' WHERE partner_id = '{$_SESSION['partner_id']}' AND language = '$language'";
                      $connection->query($updateLanguageQuery);
                  }
              } else {
                  // Language does not exist, insert it into the database
                  $insertLanguageQuery = "INSERT INTO partner_languages (partner_id, language, ProficiencyLevel) VALUES ('{$_SESSION['partner_id']}', '$language', '$proficiencyLevel')";
                  $connection->query($insertLanguageQuery);
              }
          }
      } else {
          // Handle case where no language is chosen
         
          "<script>alert('Please select at least one language and proficiency level.');</script>";
          exit();
      }

                if ($stmt->execute()) {
                    // Store success message in session variable
                    $_SESSION['profile_updated_success'] = true;
                } else {
                    echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
                }
               
                $stmt->close();
                $connection->close();
            }}


            // Check for successful profile update and redirect to this page to clear POST data
            if (isset($_SESSION['profile_updated_success']) && $_SESSION['profile_updated_success']) {
                unset($_SESSION['profile_updated_success']);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } elseif (isset($_POST['delete_account'])) {
                $connection = new mysqli($servername, $username, $dbPassword, $database);
                $stmtDelete = $connection->prepare("DELETE FROM partners WHERE partner_id =?");
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
        }

    }

}
// Function to get existing languages for the current user
function getExistingLanguages() {
  global $connection;
  $existingLanguages = [];
  $query = "SELECT language FROM partner_languages WHERE partner_id = '{$_SESSION['partner_id']}'";
  $result = $connection->query($query);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $existingLanguages[] = $row['language'];
      }
  }
  return $existingLanguages;
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

    // Assign user data to variables for pre-filling the form
    $firstName = $userData['first_name'];
    $lastName = $userData['last_name'];
    $email = $userData['email'];
    $password = $userData['password'];
    $location = $userData['location'];
    $photo = $userData['photo'];
    $age = $userData['age'];
    $gender = $userData['gender'];
    $culturalKnowledge = $userData['cultural_knowledge'];
    $education = $userData['Education'];
    $experience = $userData['Experience'];
    $pricePerSession = $userData['PricePerSession'];
    $photo = $userData['photo'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8"> <!-- character encoding-->
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- viewpoet settings-->
  <link rel="stylesheet" type="text/css" href="style.css" media="screen">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <title>My Profile Partner</title>
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
  <!-- JS Files -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>

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
    <li><a class="nav-link scrollto" href="myprofilepartner.php">My profile</a></li>
    <li><a class="nav-link scrollto" href="currentSessionsPartner.php">Sessions</a></li>
    <li><a class="nav-link scrollto" href="allRequestsPartner.php">Language Learning Requests</a></li>
    <li><a class="nav-link scrollto" href="ReviewPartner.php">My reviews and rating</a></li>
    <li><a class="nav-link scrollto" href="PartnersListP.php">Partners List</a></li>
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
        <?php
echo "<img class = 'personal' src='$photo' width ='90' height= '80' alt='personal'>";
?>
      </div>
      <div class="row">

        <div class="col-lg-12 mt-9 mt-lg-0 d-flex align-items-stretch">




  <form action="#" method="post" class="php-email-form" enctype="multipart/form-data">
          <div class="row">
            <div class="form-group col-md-6">
                   <label class="required">First Name</label>
              <input type="text" value="<?php echo htmlspecialchars($firstName); ?>" name="first_name" class="form-control" id="fname">
            </div>
            <div class="form-group col-md-6">
              <label class="required">Last Name</label>
              <input type="text" value="<?php echo htmlspecialchars($lastName); ?>" name="last_name" class="form-control" id="lname" >
            </div>
            <div class="form-group col-md-6">
              <label class="required">Age</label>
              <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>">
            </div>
            <div class="form-group col-md-6">
  <label class="required">Gender</label>
  <select name="gender" class="form-control">
    <option value="">Select Gender</option>
    <option value="female" <?php if ($gender === 'female') {
    echo 'selected';
}
?>>Female</option>
    <option value="male" <?php if ($gender === 'male') {
    echo 'selected';
}
?>>Male</option>
  </select>
</div>
            <div class="form-group">
              <label class="required">Email</label>
              <input type="email" class="form-control"value="<?php echo htmlspecialchars($email); ?>" name="email" id="email" >
            </div>
            <div class="form-group">
              <label for="psw" class="required">Password</label>
              <div class="input-group">
                  <input type="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>" id="psw" name="password" placeholder="Enter your password" required minlength="8" maxlength="15" pattern="^(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,15}$" title="Password must be 8-15 characters long and include at least one special character.">

              </div>
          </div>


          <div class="form-group">
              <label>Upload Photo</label>
              <input type="file" class="form-control" name="photo" id="photo" >
              <input type=hidden name="image_old" value="<?php echo $photo; ?>">
            </div>


<?php
            
// Fetch available languages and proficiency levels
$availableLanguages = ['Arabic', 'English', 'French', 'Spanish']; // List of available languages in the form
$proficiencyLevels = ['Beginner', 'Intermediate', 'Advanced']; // List of proficiency levels

// Fetch user's language proficiency levels from the database
$sqlLanguages = "SELECT * FROM partner_languages WHERE partner_id = '{$_SESSION['partner_id']}'";
$resultLanguages = $connection->query($sqlLanguages);
$userLanguages = [];
while ($rowLanguage = $resultLanguages->fetch_assoc()) {
    $userLanguages[$rowLanguage['language']] = $rowLanguage['ProficiencyLevel'];
}

// Generate checkboxes and select dropdowns for each language
foreach ($availableLanguages as $language) {
    echo "<div class='language-selection'>";
    echo "<label class='cbx' for='cbx-46-$language'>";
    echo "<input class='inp-cbx' id='cbx-46-$language' type='checkbox' name='language[]' value='$language' " . (isset($userLanguages[$language]) ? 'checked' : '') . " />";
    echo "<span>$language</span>";
    echo "</label>";
    echo "<select name='ProficiencyLevel[]' class='form-control' " . (isset($userLanguages[$language]) ? '' : 'disabled') . ">";
    echo "<option value=''>Select proficiency</option>";
    foreach ($proficiencyLevels as $level) {
        echo "<option value='$level' " . ($userLanguages[$language] === $level ? 'selected' : '') . ">$level</option>";
    }
    echo "</select>";
    echo "</div>";
}
?>
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
          <textarea class="form-control"  name="cultural_knowledge" id="cultural_knowledge" rows="5"><?php echo htmlspecialchars($culturalKnowledge); ?></textarea>       </div>
        <div class="form-group">
          <label class="required">Education</label>
          <textarea class="form-control"name="Education" id="Education" rows="5" ><?php echo htmlspecialchars($education); ?></textarea>
        </div>
        <div class="form-group">
          <label class="required">Experience</label>
          <textarea class="form-control"name="Experience" id="Experience" rows="5" ><?php echo htmlspecialchars($experience); ?></textarea>
        </div>
        <div class="form-group">
          <label for="location" class="required">Location</label>
          <input type="text" name="location" class="form-control" id="location" value="<?php echo htmlspecialchars($location); ?>">
        </div>
        <div class="form-group col-md-6">
<label class="required">Price per session</label>
<input type="number" class="form-control" value="<?php echo htmlspecialchars($pricePerSession); ?>" name="PricePerSession" id="PricePerSession" min="50" step="1" >
        </div>
       <div class="text-center" style="display: flex; justify-content: space-between;">
       <form id="save-changes-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="save_changes" value="1">
              <button type="submit" id="save-changes-btn" style="margin-right: auto;">Save Changes</button>
              </form>
              <form id="delete-account-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              <input type="hidden" name="delete_account" value="1">
              <button type="button" id="delete-account-btn" name="delete_account" onclick="confirmDelete()" style="background-color: red; border: 0; padding: 12px 34px; color: #fff; transition: 0.4s; border-radius: 50px;">Delete my account</button>
              </form>

              <script>
        // Function to confirm account deletion
        function confirmDelete() {
            if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
                // If user confirms, submit the form
                document.getElementById("delete-account-form").submit();
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
            <ul>  <li><i class="bx bx-chevron-right"></i> <a href="logout.php">Sign out</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="myprofilepartner.php">My profile</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="currentSessionsPartner.php">Sessions</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="AllReq.php">Language Learning Requests</a></li>
			  <li><i class="bx bx-chevron-right"></i> <a href="reviewAndRatingPartner.php">my review and rating </a></li>
                           <li><i class="bx bx-chevron-right"></i><a class="nav-link scrollto" href="PartnersListP.php">Partners List</a></li>
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

// Add this JavaScript code to handle the email already registered message
<?php
if (isset($_SESSION['email_already_registered']) && $_SESSION['email_already_registered']) {
    echo "var emailAlreadyRegistered = true;";
    unset($_SESSION['email_already_registered']); // Unset session variable after handling
} else {
    echo "var emailAlreadyRegistered = false;";
}
?>

// Add this JavaScript code to handle the success message
if (profileUpdatedSuccess) {
    alert('Profile updated successfully!');
}

// Add this JavaScript code to handle the email already registered message
if (emailAlreadyRegistered) {
    alert('The email address is already registered. Please use another email.');
}
</script>
</body>

</html>
