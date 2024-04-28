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
    $password = $_POST['psw'];
    $age = (int)$_POST['age'];
    $gender = $connection->real_escape_string($_POST['gender']);
    $education = isset($_POST['education']) ? $connection->real_escape_string($_POST['education']) : '';
    $experience = $connection->real_escape_string($_POST['experience']);
    $pricePerSession = (int)$_POST['price'];
    $languages = isset($_POST['languages']) ? $_POST['languages'] : array(); 
    $proficiencies = isset($_POST['proficiencies']) ? $_POST['proficiencies'] : array(); 
    $location = $connection->real_escape_string($_POST['location']);

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
    } else {
        $target_file = null; 
    }

    // Inserting data into partners table
    $insertQuery = "INSERT INTO partners (first_name, last_name, email, password, photo, age, gender, education, experience, location, PricePerSession) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($insertQuery);
    if (!$stmt) {
        die("MySQL prepare error: " . $connection->error);
    }
    $stmt->bind_param("ssssisissss", $firstName, $lastName, $email, $password, $target_file, $age, $gender, $education, $experience, $location, $pricePerSession);

    if ($stmt->execute()) {
        $partnerId = $connection->insert_id;  // Get the last inserted ID

        // Prepare the statement for inserting languages
        $stmtLang = $connection->prepare("INSERT INTO partner_languages (partner_id, language, proficiency_level) VALUES (?, ?, ?)");
        if (!$stmtLang) {
            die("MySQL prepare error: " . $connection->error);
        }

        // Insert each language and proficiency
        foreach ($languages as $index => $language) {
            $proficiency = $proficiencies[$index];
            $stmtLang->bind_param("iss", $partnerId, $language, $proficiency);
            $stmtLang->execute();
        }
        $stmtLang->close();
        
        echo "<script>alert('Registration successful! Welcome to Lingo.');window.location.href='login.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $connection->close();
} else {
   echo "Invalid request method.";
}
?>