<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "lingo";

$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (isset($_POST['email']) && isset($_POST['psw'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['psw']);

    $stmt = $connection->prepare("SELECT learner_id, email, password FROM learners WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($password === $row['password']) {
            $_SESSION['learner_id'] = $row['learner_id'];
            $_SESSION['email'] = $row['email'];

            header('Location: PartnersList.html'); 
            exit;
        } else {
            echo '<script>alert("Invalid email or password."); window.location.href="loginlearner.html";</script>';
            exit; 
        }
    } else {
        echo '<script>alert("Invalid email or password."); window.location.href="loginlearner.html";</script>';
        exit; 
    }
    $stmt->close();
} else {
    echo '<script>alert("Database error."); window.location.href="loginlearner.html";</script>';
    exit; 
}

$connection->close();
?>