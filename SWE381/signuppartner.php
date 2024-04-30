<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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

    $requiredFields = ['fname', 'lname', 'email', 'psw', 'age', 'gender', 'education', 'experience', 'price', 'location', 'cultural-knowledge'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            echo "Error: Missing $field";
            exit;
        }
    }
if (empty($_POST['languages']) || !is_array($_POST['languages']) || empty($_POST['proficiency_levels']) || !is_array($_POST['proficiency_levels'])) {
    echo "Error: Missing or invalid language and proficiency data";
    exit;
}


    $firstName = $connection->real_escape_string($_POST['fname']);
    $lastName = $connection->real_escape_string($_POST['lname']);
    $email = $connection->real_escape_string($_POST['email']);
    $password = $_POST['psw'];
    $age = (int)$_POST['age'];
    $gender = $connection->real_escape_string($_POST['gender']);
    $education = $connection->real_escape_string($_POST['education']);
    $experience = $connection->real_escape_string($_POST['experience']);
    $pricePerSession = (int)$_POST['price'];
    $location = $connection->real_escape_string($_POST['location']);
    $cultural_knowledge = $connection->real_escape_string($_POST['cultural-knowledge']);

    // Validate email 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Validate email 
    $checkEmailQuery = "SELECT email FROM partners WHERE email = ?";
    if ($stmt = $connection->prepare($checkEmailQuery)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            echo "The email address is already registered. Please use another email.";
            exit;
        }
    }
    // Validate age
if ($age < 18) {
    echo "You must be at least 18 years old to register.";
    exit;
}

    // Insert to partners 
    $insertQuery = "INSERT INTO partners (first_name, last_name, email, password, photo, location, cultural_knowledge, age, gender, Education, Experience, PricePerSession) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $connection->prepare($insertQuery)) {
        $stmt->bind_param("sssssssisdis", $firstName, $lastName, $email, $password, $target_file, $location, $cultural_knowledge, $age, $gender, $education, $experience, $pricePerSession);
        $stmt->execute();
        $partner_id = $stmt->insert_id; 
        $stmt->close();
        
        // Insert to partner_languages
$insertLanguageQuery = "INSERT INTO partner_languages (partner_id, language, proficiency_level) VALUES (?, ?, ?)";
foreach ($_POST['languages'] as $index => $language) {
    $proficiency = $_POST['proficiency_levels'][$index];
    if ($stmt = $connection->prepare($insertLanguageQuery)) {
        $stmt->bind_param("iss", $partner_id, $language, $proficiency);
        $stmt->execute();
        $stmt->close();
    } else {
        die("MySQL prepare error: " . $connection->error);
    }
}

     echo "<script>alert('Registration successful!'); window.location.href='loginpartner.html';</script>";
    } else {
        die("MySQL prepare error: " . $connection->error);
    }

    $connection->close();
}
?>