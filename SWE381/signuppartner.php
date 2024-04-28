<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $dbPassword = "";
    $database = "lingo";
    $connection = new mysqli($servername, $username, $dbPassword, $database);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $firstName = $connection->real_escape_string($_POST['fname']);
    $lastName = $connection->real_escape_string($_POST['lname']);
    $email = $connection->real_escape_string($_POST['email']);
    $password = $_POST['psw']; 
    $age = (int)$_POST['age'];
    $gender = $connection->real_escape_string($_POST['gender']);
    $culturalKnowledge = $connection->real_escape_string($_POST['cultural_knowledge']);
    $education = $connection->real_escape_string($_POST['education']);
    $experience = $connection->real_escape_string($_POST['experience']);
    $pricePerSession = (int)$_POST['price'];
    $languages = $_POST['languages']; // array
    $proficiencies = $_POST['proficiencies']; // array

    // Validate age
    if ($age < 18) {
        echo "You must be at least 18 years old to register.";
        exit;
    }


    // Check if email already exists
    $checkEmailQuery = "SELECT email FROM partners WHERE email = ?";
    $stmt = $connection->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('The email address is already registered. Please use another email.'); window.location.href='signupPartner.html';</script>";
        $stmt->close();
        $connection->close();
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
 // Insert partner table
    $insertQuery = "INSERT INTO partners (first_name, last_name, email, password, photo, age, gender, cultural_knowledge, education, experience, price_per_session) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($insertQuery);
    if ($stmt === false) {
        die("MySQL prepare error: " . $connection->error);
    }

    $stmt->bind_param("ssssisssssi", $firstName, $lastName, $email, $password, $target_file, $age, $gender, $culturalKnowledge, $education, $experience, $pricePerSession);
    if ($stmt->execute()) {
        $partnerId = $stmt->insert_id;

        // Insert languages and proficiencies
        $insertLangQuery = "INSERT INTO partner_languages (partner_id, language, proficiency_level) VALUES (?, ?, ?)";
        $stmtLang = $connection->prepare($insertLangQuery);
        foreach ($languages as $index => $language) {
            $proficiency = $proficiencies[$index];
            $stmtLang->bind_param("iss", $partnerId, $language, $proficiency);
            $stmtLang->execute();
        }

        $_SESSION['user_id'] = $partnerId;
        header("Location: reviewAndRatingPartner.html");
        exit(); 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $formData = sanitizeInputData();

    // Validate input
    if (!validateInput($formData)) {
        exit;
    }

    // Hash the password
    $formData['password'] = password_hash($formData['password'], PASSWORD_DEFAULT);

    // Establish database connection
    $conn = getDatabaseConnection();

    // Insert the partner record
    if (insertPartner($conn, $formData)) {
        echo "<div class='sent-message'>You have been signed up!</div>";
    } else {
        echo "<div class='error-message'>Error: Unable to register partner.</div>";
    }

    // Close the database connection
    $conn->close();
}

function sanitizeInputData() {
    return [
        'first_name' => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING),
        'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING),
        'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
        'password' => $_POST['password'], // Consider sanitizing if password rules allow
        'gender' => filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING),
        'location' => filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING),
        'cultural_knowledge' => filter_input(INPUT_POST, 'cultural_knowledge', FILTER_SANITIZE_STRING),
        'education' => filter_input(INPUT_POST, 'education', FILTER_SANITIZE_STRING),
        'experience' => filter_input(INPUT_POST, 'experience', FILTER_SANITIZE_STRING),
        'price_per_session' => filter_input(INPUT_POST, 'price_per_session', FILTER_SANITIZE_NUMBER_INT),
        'age' => filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT)
    ];
}

function validateInput($data) {
    foreach ($data as $key => $value) {
        if (empty($value)) {
            echo "<div class='error-message'>Error: All fields are required. Missing: $key</div>";
            return false;
        }
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error-message'>Error: Invalid email format.</div>";
        return false;
    }

    $passwordRegex = '/^(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/';
    if (!preg_match($passwordRegex, $data['password'])) {
        echo "<div class='error-message'>Error: Password must be at least 8 characters long and contain at least one special character (!@#$%^&*).</div>";
        return false;
    }

    if ($data['age'] < 18) {
        echo "<div class='error-message'>Error: Your age must be greater than or equal to 18.</div>";
        return false;
    }

    if ($data['price_per_session'] < 50) {  
      echo "<div class='error-message'>Error: Price per session must be at least 50.</div>";
      return false;
  }

    return true;
}

function getDatabaseConnection() {
    $conn = mysqli_connect("localhost", "root", "root", "lingo");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function insertPartner($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO partner (first_name, last_name, email, password, gender, location, cultural_knowledge, education, experience, price_per_session, age) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssiii", $data['first_name'], $data['last_name'], $data['email'], $data['password'], $data['gender'], $data['location'], $data['cultural_knowledge'], $data['education'], $data['experience'], $data['price_per_session'], $data['age']);
    
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
