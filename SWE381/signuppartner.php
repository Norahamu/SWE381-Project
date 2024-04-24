<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $location = $_POST['location'];
    $cultural_knowledge = $_POST['cultural_knowledge'];  // Corrected variable name typo
    $education = $_POST['education'];
    $experience = $_POST['experience'];
    $pricePerSession = $_POST['price_per_session'];
    $age = $_POST['age'];
    $languages = $_POST['languages']; // Assume this is an array of languages
    $proficiencyLevels = $_POST['proficiency_levels']; // This should be an array of proficiency levels

    if(empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty(gender) || empty($location) || empty($cultural_knowledge) || empty($education) || empty($experience) || empty($pricePerSession) || empty($age)) {
        echo "<div class='error-message'>Error: All fields are required.</div>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error-message'>Error: Invalid email format.</div>";
        exit;
    }

    $passwordRegex = '/^(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/';
    if (!preg_match($passwordRegex, $password)) {
        echo "<div class='error-message'>Error: Password must be at least 8 characters long and contain at least one special character (!@#$%^&*)</div>";
        exit;
    }

    if ($age < 18) {
        echo "<div class='error-message'>Error: Your age must be greater than or equal to 18.</div>";
        exit;
    }

    $conn = mysqli_connect("localhost", "root", "root", "lingo");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmtPartner = $conn->prepare("INSERT INTO partner (first_name, last_name, email, password, gender, location, cultural_knowledge, education, experience, price_per_session, age) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtPartner->bind_param("ssssssssiii", $firstName, $lastName, $email, $password, $gender, $location, $cultural_knowledge, $education, $experience, $pricePerSession, $age);

    if ($stmtPartner->execute()) {
        $partnerId = $stmtPartner->insert_id;

        $stmtLanguage = $conn->prepare("INSERT INTO partner_languages (partner_id, language, proficiency_level) VALUES (?, ?, ?)");

        foreach ($languages as $index => $language) {
            $proficiencyLevel = $proficiencyLevels[$index];
            $stmtLanguage->bind_param("iss", $partnerId, $language, $proficiencyLevel);
            $stmtLanguage->execute();
        }

        echo "<div class='sent-message'>You have been signed up!</div>";
    } else {
        echo "<div class='error-message'>Error: " . $stmtPartner->error . "</div>";
    }

    $stmtPartner->close();
    $stmtLanguage->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Sign up as a language partner Page</title>

 <!-- icon -->
 <link rel="icon" href="assets/img/Lingoblue.png">
  
 <!-- Google Fonts -->
 <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:300,300i,400,400i,600,600i,700,700i&family=Jost:300,300i,400,400i,500,500i,600,600i,700,700i&family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i">
 
 <!-- Vendor CSS Files -->
 <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
 <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
 <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
 <!-- Main CSS File -->
 <link href="style.css" rel="stylesheet">
  <link href="radio.css" rel="stylesheet">
  <!-- JS main File-->
  <script src="validate.js"></script>
</head>
<body>
 
<!-- ======= Header ======= -->
<header id="header" class="fixed-top header-inner-pages">
 <div class="container d-flex align-items-center">

<!-- logo-->
<a href="index.html" class="logo me-auto"><img src="assets/img/Lingowhite.png" alt="Lingo logo" class="img-fluid"> </a>
</div>
  
<nav id="navbar" class="navbar">
<ul>
 <li class="dropdown">
   <a class="getstarted scrollto" href="#"><span>Get Started</span> <i class="bi bi-chevron-down"></i></a>
   <ul>
    <li class="dropdown">
     <a href="#"><span>Sign Up</span> <i class="bi bi-chevron-right"></i></a>
     <ul>
      <li><a href="signuppartner.html">Sign Up as a language partner </a></li>
      <li><a href="signuplearner.html"> Sign Up as a learner</a></li>
     </ul>
    </li>
           <li class="dropdown">
          <a href="#"><span>Log in</span> <i class="bi bi-chevron-right"></i></a>
		  <ul>
            <li><a  href="loginpartner.html">Log in as a language partner </a></li>
            <li><a  href="loginlearner.html">Log in as a learner</a></li>
		</ul>
		</li>
   </ul>
 </li>
 <li><a class="nav-link scrollto " href="HomePage.html">Home</a></li>
 <li><a class="nav-link scrollto" href="HomePage.html">About</a></li>
 <li><a class="nav-link scrollto" href="HomePage.html"> How it works? </a></li>
 <li><a class="nav-link scrollto" href="HomePage.html"> Find a language partner</a></li>
 <li><a class="nav-link scrollto" href="HomePage.html"> Become a language partner</a></li>
</ul>
<i class="bi bi-list mobile-nav-toggle"></i>
</nav>
<!-- .navbar -->
</header>
<!-- End Header -->
<!-- ======= Sign up as a language partner Section ======= -->
<section id="signuppartner" class="signuppartner section-bg">
  <div class="container aos-init aos-animate" data-aos="fade-up">
    <div class="section-title">
      <h2>Sign up as a language partner</h2>
    </div>
    <div class="row">
      <div class="col-lg-5 d-flex align-items-stretch">
        <div class="info">
          <div class="login">
            <h4>If you already have an account, log in:</h4>
            <div class="text-center"><a href="loginpartner.html" class="btn-sign">Log in as a language partner</a></div>
            <div class="text-center"><a href="loginlearner.html" class="btn-sign">Log in as a learner</a></div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
        <form action="forms/signuplearner.php" method="post" class="php-email-form">
          <div class="row">
            <div class="form-group col-md-6">
              <label class="required">First Name</label>
              <input type="text" placeholder="Enter your first name" name="First name" class="form-control" id="fname" required>
            </div>
            <div class="form-group col-md-6">
              <label class="required">Last Name</label>
              <input type="text" placeholder="Enter your last name" name="Last name" class="form-control" id="lname" required>
            </div>
            <div class="form-group col-md-6">
              <label class="required">Age</label>
              <input type="number" class="form-control" placeholder="Enter your age" name="age" id="age" required>
            </div>
            <div class="containergender">
    <label class="required">Gender</label>
    <select name="gender" class="form-control">
        <option value="">Select Gender</option>
        <option value="female">Female</option>
        <option value="male">Male</option>
    </select>
</div>
            <div class="form-group">
              <label class="required">Email</label>
              <input type="email" class="form-control" placeholder="Enter your email" name="email" id="email" required>
            </div>
            <div class="form-group">
              <label class="required">Password</label>
              <input type="password" class="form-control" placeholder="Enter your password" name="Password" id="psw" required>
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
      <input class="inp-cbx" id="cbx-46-arabic" type="checkbox" name="languages[]" value="Arabic" />
      <span>Arabic</span>
    </label>
    <select name="proficiency_levels[]" class="form-control" disabled>
      <option value="">Select proficiency</option>
      <option value="Beginner">Beginner</option>
      <option value="Intermediate">Intermediate</option>
      <option value="Advanced">Advanced</option>
    </select>
  </div>
  <div class="language-selection">
    <label class="cbx" for="cbx-46-english">
      <input class="inp-cbx" id="cbx-46-english" type="checkbox" name="languages[]" value="English" />
      <span>English</span>
    </label>
    <select name="proficiency_levels[]" class="form-control" disabled>
      <option value="">Select proficiency</option>
      <option value="Beginner">Beginner</option>
      <option value="Intermediate">Intermediate</option>
      <option value="Advanced">Advanced</option>
    </select>
  </div>
  <div class="language-selection">
    <label class="cbx" for="cbx-46-french">
      <input class="inp-cbx" id="cbx-46-french" type="checkbox" name="languages[]" value="Français" />
      <span>Français</span>
    </label>
    <select name="proficiency_levels[]" class="form-control" disabled>
      <option value="">Select proficiency</option>
      <option value="Beginner">Beginner</option>
      <option value="Intermediate">Intermediate</option>
      <option value="Advanced">Advanced</option>
    </select>
  </div>
  <div class="language-selection">
    <label class="cbx" for="cbx-46-spanish">
      <input class="inp-cbx" id="cbx-46-spanish" type="checkbox" name="languages[]" value="Español" />
      <span>Español</span>
    </label>
    <select name="proficiency_levels[]" class="form-control" disabled>
      <option value="">Select proficiency</option>
      <option value="Beginner">Beginner</option>
      <option value="Intermediate">Intermediate</option>
      <option value="Advanced">Advanced</option>
    </select>
  </div>
</div>
<script>
document.getElementById('language-form').addEventListener('change', function(event) {
  let target = event.target;
  if (target.type === 'checkbox') {
    let selectElement = target.parentNode.nextElementSibling;
    selectElement.disabled = !target.checked;
    if (!target.checked) {
      selectElement.value = ''; 
    }
  }
});
</script>
        <div class="form-group">
          <label class="required">Cultural Knowledge</label>
          <textarea class="form-control" placeholder="Enter your cultural knowledge" name="cultural-knowledge" id="cultural-knowledge" rows="5" required></textarea>
        </div>
        <div class="form-group">
          <label class="required">Education</label>
          <textarea class="form-control" placeholder="Enter your experience" name="experience" id="experience" rows="5" required></textarea>
        </div>
        <div class="form-group">
          <label class="required">Experience</label>
          <textarea class="form-control" placeholder="Enter your experience" name="experience" id="experience" rows="5" required></textarea>
        </div>
        <div class="text-center">
          <button type="submit" class="btn-sign">Sign up</button>
        </div>
      </div>
    </div>
    </form>
  </div>
  </div>
</section>
 
<!-- ======= Footer ======= -->
<footer id="footer">
 <div class="footer-top">
   <div class="container">
    <div class="row">
     <div class="col-lg-3 col-md-6 footer-contact">
      <a href="index.html" class="logo me-auto"><img src="assets/img/Lingoblue.png" alt="Lingo logo" class="img-fluid"></a>
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
       <li><i class="bx bx-chevron-right"></i> <a href="HomePage.html">Home</a></li>
       <li><i class="bx bx-chevron-right"></i> <a href="HomePage.html">About us</a></li>
       <li><i class="bx bx-chevron-right"></i> <a href="HomePage.html">How it works?</a></li>
       <li><i class="bx bx-chevron-right"></i> <a href="HomePage.html">Find a language partner</a></li>
       <li><i class="bx bx-chevron-right"></i> <a href="HomePage.html">Become a language partner</a></li>
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