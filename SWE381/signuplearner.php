<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $city = $_POST['city'];
    $location = $_POST['location'];
    $photo = '';  

    if(empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($city) || empty($location)) {
        echo "<div class='error-message'>Error: All fields are required.</div>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error-message'>Error: Invalid email format.</div>";
        exit;
    }

    if (strlen($password) < 8 || strlen($password) > 15) {
        echo "<div class='error-message'>Error: Password must be between 8 and 15 characters long.</div>";
        exit;
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed_ext = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $file_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

        if (!array_key_exists($file_ext, $allowed_ext)) {
            echo "<div class='error-message'>Error: Please select a valid file format for the photo.</div>";
            exit;
        }

        $photo = 'uploads/' . time() . $_FILES['photo']['name']; 
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo)) {
            echo "<div class='error-message'>Error: There was an issue uploading your photo.</div>";
            exit;
        }
    } else {
        echo "<div class='error-message'>Error: Photo is required.</div>";
        exit;
    }

    $conn = mysqli_connect("localhost", "root", "root", "lingo");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO learners (first_name, last_name, email, password, photo, city, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $firstName, $lastName, $email, $password, $photo, $city, $location);

    if ($stmt->execute()) {
        echo "<div class='success-message'>Learner registered successfully!</div>";
    } else {
        echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="style.css" media="screen">
  <title>Sign up as a learner Page</title>
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
</head>
<body>
<!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center">
      <a href="index.html" class="logo me-auto"><img src="assets/img/Lingowhite.png" alt="Lingo logo" class="img-fluid"></a>
    </div>
    <nav id="navbar" class="navbar">
      <ul>
        <li class="dropdown">
          <a class="getstarted scrollto" href="#"><span>Get Started</span> <i class="bi bi-chevron-down"></i></a>
          <ul>
            <li class="dropdown">
              <a href="#"><span>Sign Up</span> <i class="bi bi-chevron-right"></i></a>
              <ul>
                <li><a href="signuppartner.html">Sign Up as a language partner</a></li>
                <li><a href="signuplearner.html">Sign Up as a learner</a></li>
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
        <li><a class="nav-link scrollto" href="HomePage.html">Home</a></li>
        <li><a class="nav-link scrollto" href="HomePage.html">About</a></li>
        <li><a class="nav-link scrollto" href="HomePage.html">How it works?</a></li>
        <li><a class="nav-link scrollto" href="HomePage.html">Find a language partner</a></li>
        <li><a class="nav-link scrollto" href="HomePage.html">Become a language partner</a></li>
      </ul>
      <i class="bi bi-list mobile-nav-toggle"></i>
    </nav>
	<!-- .navbar -->
  </header>
  <!-- End Header -->
  <!-- ======= Sign up as a language learner Section ======= -->
  <section id="signuplearner" class="signuplearner section-bg">
    <div class="container aos-init aos-animate" data-aos="fade-up">

      <div class="section-title">
        <h2>Sign up as a learner</h2>
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
          <form method="post" class="php-email-form" action="signuplearner.php">
            <div class="row">
              <div class="form-group col-md-6">
                <label class="required">First Name</label>
                <input type="text" placeholder="Enter your first name" name="First name" class="form-control" id="fname" required>
              </div>
              <div class="form-group col-md-6">
                <label class="required">Last Name</label>
                <input type="text" placeholder="Enter your last name" name="Last name" class="form-control" id="lname" required>
              </div>
              <div class="form-group">
                <label class="required">Email</label>
                <input type="email" class="form-control" placeholder="Enter your email" name="email" id="email" required>
              </div>
            </div>
            <div class="form-group">
              <label class="required">Password</label>
              <input type="password" class="form-control" placeholder="Enter your password" name="Password" id="psw" required>
            </div>
            <div class="form-group">
              <label>Upload Photo</label>
              <input type="file" class="form-control" name="photo" id="photo">
            </div>
            <div class="form-group">
              <label class="required">City</label>
              <input type="text" class="form-control" placeholder="Enter your city" name="city" id="city" required>
            </div>
            <div class="form-group">
              <label class="required">Location</label>
              <input type="text" class="form-control" placeholder="Enter your location" name="location" id="location" required>
            </div>
            <div class="my-3">
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">You have been signed up!</div>
            </div>
            <div class="text-center"><button type="submit">Sign up</button></div>
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
