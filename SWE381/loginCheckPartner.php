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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['psw']; 

    $sql = "SELECT partner_id, email, password FROM partners WHERE email = ?";
    if ($stmt = $connection->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['partner_id'] = $row['partner_id'];
                $_SESSION['email'] = $row['email'];
                header('Location: HomePage.html'); 
                exit();
            } else {
                // Invalid password
                echo '<script>alert("Invalid email or password."); window.location.href="logipartner.html";</script>';
                exit; 
            }
        } else {
            // No user found
            echo '<script>alert("Invalid email or password."); window.location.href="loginpartner.html";</script>';
            exit; 
        }
        $stmt->close();
    } else {
        // Error in the SQL statement
        echo '<script>alert("Database error."); window.location.href="loginpartner.html";</script>';
        exit; 
    }
    $connection->close();
} else {
    // Not a POST request
    header('Location: loginpartner.html'); 
    exit; 
}
?>