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
    $location = $connection->real_escape_string($_POST['location']);
    $age = $connection->real_escape_string($_POST['age']);
    $gender = $connection->real_escape_string($_POST['gender']);
    $culturalKnowledge = addslashes($connection->real_escape_string($_POST['cultural_knowledge']));
    $education = addslashes($connection->real_escape_string($_POST['Education']));
    $experience = addslashes($connection->real_escape_string($_POST['Experience']));
    $pricePerSession = $connection->real_escape_string($_POST['PricePerSession']);
    $languages = $connection->real_escape_string($_POST['languages']) ? $_POST['languages'] : [];
    $proficiencyLevels = $connection->real_escape_string($_POST['proficiency_levels']) ? $_POST['proficiency_levels'] : [];
    $old_image=$_POST['image_old'];
    $photo=$_FILES['photo']['name'];

   $sqlFetch = "SELECT language, ProficiencyLevel FROM partner_languages WHERE partner_id = ?";
   $stmtFetch = $pdo->prepare($sqlFetch);
   $stmtFetch->execute([$_SESSION['partner_id']]);
   $languages = $stmtFetch->fetchAll(PDO::FETCH_ASSOC);

    
    if($photo!=null){
    
      $update_filename=$photo;
    }
    
    else{
      $update_filename=$old_image;
    
    }
     
        $languages = $_POST['languages'];
        $proficiencyLevels = $_POST['proficiencyLevels'];

        if (empty($languages) || empty($proficiencyLevels)) {
            throw new Exception("Languages and proficiency levels cannot be empty.");
        }

        if (count($languages) !== count($proficiencyLevels)) {
            throw new Exception("Each language must have a corresponding proficiency level.");
        }

        // Delete existing languages
        $sqlDelete = "DELETE FROM partner_languages WHERE partner_id = ?";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->execute([$_SESSION['partner_id']]);

        // Insert updated languages and proficiency levels
        $sqlInsert = "INSERT INTO partner_languages (partner_id, language, ProficiencyLevel) VALUES (?, ?, ?)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        foreach ($languages as $index => $language) {
            $stmtInsert->execute([$_SESSION['partner_id'], $language, $proficiencyLevels[$index]]);
        }

    
    // Check if the provided email already exists for another user
$checkEmailQuery = "SELECT * FROM partners WHERE email = '$email' AND partner_id != '{$_SESSION['partner_id']}'";
$result = $connection->query($checkEmailQuery);

if ($result->num_rows > 0) {
    // Email address is already registered for another user
    $_SESSION['email_already_registered'] = true;
} else {
    //UPDATE
   
        // New photo uploaded, update the photo field in the database
        $stmt = $connection->prepare("UPDATE partners SET first_name=?, last_name=?, email=?, password=?, photo=?, location=?, cultural_knowledge=?, Education=?, Experience=?, PricePerSession=?, age=?, gender=? WHERE partner_id=?");
        $stmt->bind_param("ssssssssssssi", $firstName, $lastName, $email, $password, $update_filename, $location, $culturalKnowledge, $education, $experience, $pricePerSession, $age, $gender, $_SESSION['partner_id']);
   

    if ($stmt->execute()) {
        // Store success message in session variable
        $_SESSION['profile_updated_success'] = true;
    } else {
        echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
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

// Fetch languages and proficiency levels for the partner
$stmtLanguages = $connection->prepare("SELECT language, ProficiencyLevel FROM partner_languages WHERE partner_id = ?");
$stmtLanguages->bind_param("i", $_SESSION['partner_id']);
$stmtLanguages->execute();
$resultLanguages = $stmtLanguages->get_result();

$languages = array();
$ProficiencyLevel = array();

// Fetch languages and proficiency levels
while ($row = $resultLanguages->fetch_assoc()) {
    $languages[] = $row['language'];
    $ProficiencyLevel[] = $row['ProficiencyLevel'];
}

// Check if a language is selected for the user
function isLanguageSelected($language, $userLanguages)
{
    return in_array($language, $userLanguages);
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
        if ($photo == null) {
                            echo "<img class = 'personal' src='assets/img/OIP.jpg' width ='90' height= '80' alt='personal'>";
                        } else {
                            echo "<img class = 'personal' src='assets/img/$photo' width ='90' height= '80' alt='personal'>";
                        }
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
                  <input type="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>" id="psw" name="password" placeholder="Enter your password" required minlength="8" maxlength="15" pattern="^(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,15}$" title="Password must be 8-15 characters long and include at least one special character.">
                
              </div>
          </div>  

           
          <div class="form-group"> 
              <label>Upload Photo</label> 
              <input type="file" class="form-control" name="photo" id="photo" > 
              <input type=hidden name="image_old" value="<?php echo $photo;?>">
            </div> 

            <div class="checkbox-wrapper-46">
            <div class="checkbox-wrapper-46" id="language-form">
<!-- Pre-fill language checkboxes and select dropdowns -->
<label class="required">Click on the languages you want to teach and select your proficiency:</label>
<div class="language-selection">
    <label class="cbx" for="cbx-46-arabic">
        <input class="inp-cbx" id="cbx-46-arabic" type="checkbox" name="languages[]" value="Arabic" <?php if (isLanguageSelected("Arabic", $languages)) echo "checked"; ?> />
        <span>Arabic</span>
    </label>
    <select name="proficiency_levels[Arabic]" class="form-control" <?php if (isLanguageSelected("Arabic", $languages)) echo ""; else echo "disabled"; ?>>
        <option value="">Select proficiency</option>
        <option value="Beginner" <?php if (isset($proficiencyLevels["Arabic"]) && $proficiencyLevels["Arabic"] === 'Beginner') echo 'selected'; ?>>Beginner</option>
        <option value="Intermediate" <?php if (isset($proficiencyLevels["Arabic"]) && $proficiencyLevels["Arabic"] === 'Intermediate') echo 'selected'; ?>>Intermediate</option>
        <option value="Advanced" <?php if (isset($proficiencyLevels["Arabic"]) && $proficiencyLevels["Arabic"] === 'Advanced') echo 'selected'; ?>>Advanced</option>
    </select>
</div>
<!-- Repeat similar code for other languages -->


<div class="language-selection">
    <label class="cbx" for="cbx-46-english">
        <input class="inp-cbx" id="cbx-46-english" type="checkbox" name="languages[]" value="English" <?php if (isLanguageSelected("English", $languages)) echo "checked"; ?> />
        <span>English</span>
    </label>
    <select name="proficiency_levels[English]" class="form-control" <?php if (isLanguageSelected("English", $languages)) echo ""; else echo "disabled"; ?>>
        <option value="">Select proficiency</option>
        <option value="Beginner" <?php if (isset($proficiencyLevels["English"]) && $proficiencyLevels["English"] === 'Beginner') echo 'selected'; ?>>Beginner</option>
        <option value="Intermediate" <?php if (isset($proficiencyLevels["English"]) && $proficiencyLevels["English"] === 'Intermediate') echo 'selected'; ?>>Intermediate</option>
        <option value="Advanced" <?php if (isset($proficiencyLevels["English"]) && $proficiencyLevels["English"] === 'Advanced') echo 'selected'; ?>>Advanced</option>
    </select>
</div>



<div class="language-selection">
    <label class="cbx" for="cbx-46-french">
        <input class="inp-cbx" id="cbx-46-french" type="checkbox" name="languages[]" value="French" <?php if (isLanguageSelected("French", $languages)) echo "checked"; ?> />
        <span>French</span>
    </label>
    <select name="proficiency_levels[French]" class="form-control" <?php if (isLanguageSelected("French", $languages)) echo ""; else echo "disabled"; ?>>
        <option value="">Select proficiency</option>
        <option value="Beginner" <?php if (isset($proficiencyLevels["French"]) && $proficiencyLevels["French"] === 'Beginner') echo 'selected'; ?>>Beginner</option>
        <option value="Intermediate" <?php if (isset($proficiencyLevels["French"]) && $proficiencyLevels["French"] === 'Intermediate') echo 'selected'; ?>>Intermediate</option>
        <option value="Advanced" <?php if (isset($proficiencyLevels["French"]) && $proficiencyLevels["French"] === 'Advanced') echo 'selected'; ?>>Advanced</option>
    </select>
</div>

  
<div class="language-selection">
    <label class="cbx" for="cbx-46-spanish">
        <input class="inp-cbx" id="cbx-46-spanish" type="checkbox" name="languages[]" value="Spanish" <?php if (isLanguageSelected("Spanish", $languages)) echo "checked"; ?> />
        <span>Spanish</span>
    </label>
    <select name="proficiency_levels[Spanish]" class="form-control" <?php if (isLanguageSelected("Spanish", $languages)) echo ""; else echo "disabled"; ?>>
        <option value="">Select proficiency</option>
        <option value="Beginner" <?php if (isset($proficiencyLevels["Spanish"]) && $proficiencyLevels["Spanish"] === 'Beginner') echo 'selected'; ?>>Beginner</option>
        <option value="Intermediate" <?php if (isset($proficiencyLevels["Spanish"]) && $proficiencyLevels["Spanish"] === 'Intermediate') echo 'selected'; ?>>Intermediate</option>
        <option value="Advanced" <?php if (isset($proficiencyLevels["Spanish"]) && $proficiencyLevels["Spanish"] === 'Advanced') echo 'selected'; ?>>Advanced</option>
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

      // Check initial state of checkboxes
      let selectElement = checkbox.closest('.language-selection').querySelector('.form-control');
      selectElement.disabled = !checkbox.checked;
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
    var inputFields = document.querySelectorAll('input, textarea, select, input[type="checkbox"]');
    inputFields.forEach(function(input) {
        input.addEventListener('input', handleInputChange);
        input.addEventListener('change', handleInputChange); // Adding change event listener
    });

    // Add event listener to the "Save Changes" button
    var saveChangesBtn = document.getElementById('save-changes-btn');
    saveChangesBtn.addEventListener('click', function(event) {
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
