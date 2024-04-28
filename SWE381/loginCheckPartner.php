<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "lingo";

// Create a new database connection
$connection = new mysqli($servername, $username, $password, $database);

// Check the connection status
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if email and password POST data is available
if (isset($_POST['email']) && isset($_POST['psw'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['psw']);

    $stmt = $connection->prepare("SELECT partner_id, email, password FROM partners WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            $_SESSION['partner_id'] = $row['partner_id'];
            $_SESSION['email'] = $row['email'];

            header('Location: AllReq.html'); 
            exit;
        } else {
            echo '<script>alert("Invalid email or password."); window.location.href="loginpartner.html";</script>';
            exit; 
        }
    } else {
        echo '<script>alert("Invalid email or password."); window.location.href="loginpartner.html";</script>';
        exit; 
    }
    $stmt->close();
} else {
    echo '<script>alert("Please provide both email and password."); window.location.href="loginpartner.html";</script>';
    exit; 
}

$connection->close();
?>