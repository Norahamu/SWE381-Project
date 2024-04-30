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

    // Insert new user
    $insertQuery = "INSERT INTO learners (first_name, last_name, email, password, photo, city, location) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($insertQuery);
    if ($stmt === false) {
        die("MySQL prepare error: " . $connection->error);
    }

    $stmt->bind_param("sssssss", $firstName, $lastName, $email, $password, $target_file, $city, $location);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        exit(); 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
    echo "<script>alert('Registration successful!'); window.location.href='loginlearner.html';</script>";
}
?>