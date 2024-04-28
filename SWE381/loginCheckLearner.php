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


if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['psw'];

    $stmt = $connection->prepare("SELECT learner_id, email, password FROM learners WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['learner_id'] = $row['learner_id'];
            $_SESSION['email'] = $row['email'];

            header('Location: HomePage.html'); 
            exit;
        }  else {
            // Invalid password
            echo '<script>alert("Invalid email or password."); window.location.href="loginlearner.html";</script>';
            exit; 
        }
    } else {
        // No user found
        echo '<script>alert("Invalid email or password."); window.location.href="loginlearner.html";</script>';
        exit; 
    }
    $stmt->close();
} else {
    // Error in the SQL statement
    echo '<script>alert("Database error."); window.location.href="loginlearner.html";</script>';
    exit; 
}
$connection->close();
} else {
// Not a POST request
header('Location: loginlearner.html'); 
exit; 
}

$stmt->close();
$connection->close();
?>
