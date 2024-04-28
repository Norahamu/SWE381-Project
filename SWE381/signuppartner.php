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

    $firstName = $connection->real_escape_string($_POST['fname']);
    $lastName = $connection->real_escape_string($_POST['lname']);
    $email = $connection->real_escape_string($_POST['email']);
    $password = $connection->real_escape_string($_POST['psw']); 
    $age = (int)$_POST['age'];
    $gender = $connection->real_escape_string($_POST['gender']);
    $education = isset($_POST['education']) ? $connection->real_escape_string($_POST['education']) : '';
    $experience = $connection->real_escape_string($_POST['experience']);
    $location = $connection->real_escape_string($_POST['location']);
    $pricePerSession = (int)$_POST['price'];
    $languages = isset($_POST['languages']) ? $_POST['languages'] : array(); 
    $proficiencies = isset($_POST['proficiencies']) ? $_POST['proficiencies'] : array(); 
    $cultural_knowledge = isset($_POST['cultural_knowledge']) ? $connection->real_escape_string($_POST['cultural_knowledge']) : '';

    // Validate age
    if ($age < 18) {
        echo "You must be at least 18 years old to register.";
        exit;
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT email FROM partners WHERE email = ?";
    $stmt = $connection->prepare($checkEmailQuery);
    if (!$stmt) {
        die("MySQL prepare error: " . $connection->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('The email address is already registered. Please use another email.'); window.location.href='signupPartner.html';</script>";
        $stmt->close();
        $connection->close();
        exit;
    }

    // File upload handling
    $target_file = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $target_dir = "assets/img/";
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = $firstName . $lastName . "." . $fileExt;
        $target_file = $target_dir . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Inserting data into partners table
    $insertQuery = "INSERT INTO partners (first_name, last_name, email, password, photo, age, gender, Education, Experience, location, PricePerSession, cultural_knowledge) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($insertQuery);
    if (!$stmt) {
        die("MySQL prepare error: " . $connection->error);
    }
    // Bind parameters correctly, notice that cultural_knowledge typo is corrected based on your schema
    $stmt->bind_param("sssssisssdis", $firstName, $lastName, $email, $password, $target_file, $age, $gender, $education, $experience, $location, $pricePerSession, $cultural_knowledge);

    if ($stmt->execute()) {
        $partnerId = $connection->insert_id;

        $stmtLang = $connection->prepare("INSERT INTO partner_languages (partner_id, language, ProficiencyLevel) VALUES (?, ?, ?)");
if (!$stmtLang) {
    die("MySQL prepare error: " . $connection->error);
}   

        // Insert each language and proficiency
        foreach ($languages as $index => $language) {
            $proficiency = $proficiencies[$index];
            if (!$stmtLang->bind_param("iss", $partnerId, $language, $proficiency)) {
                echo "Bind failed: " . $stmtLang->error;
            }
            if (!$stmtLang->execute()) {
                echo "Execute failed: " . $stmtLang->error;
            }
        }

    // Close statement and connection
    $stmt->close();
    $connection->close();
}
}
?>