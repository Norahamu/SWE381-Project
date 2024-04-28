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
    $password = $_POST['psw']; // Storing password as plain text
    $city = $connection->real_escape_string($_POST['city']);
    $location = $connection->real_escape_string($_POST['location']);

    // if email already exists
    $checkEmailQuery = "SELECT email FROM learners WHERE email = ?";
    $stmt = $connection->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "The email address is already registered. Please use another email.";
        $stmt->close();
        $connection->close();
        exit;
    }

    $target_file = null;
   // Check if email already exists
   $checkEmailQuery = "SELECT email FROM learners WHERE email = ?";
   $stmt = $connection->prepare($checkEmailQuery);
   $stmt->bind_param("s", $email);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
       echo "<script>alert('The email address is already registered. Please use another email.'); window.location.href='signupLearner.html';</script>";
       $stmt->close();
       $connection->close();
       exit;
   }

    // Insert new user into database
    $insertQuery = "INSERT INTO learners (first_name, last_name, email, password, photo, city, location) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($insertQuery);
    if ($stmt === false) {
        die("MySQL prepare error: " . $connection->error);
    }

    $stmt->bind_param("sssssss", $firstName, $lastName, $email, $password, $target_file, $city, $location);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        header("Location: PartnersList.html");
        exit(); 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>