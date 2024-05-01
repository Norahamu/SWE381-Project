<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $dbPassword = "";
    $database = "lingo";
    
      $dsn = "mysql:host=$servername;dbname=$database;charset=utf8mb4";
      $options = [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES => false,
      ];
      try {
          $pdo = new PDO($dsn, $username, $dbPassword, $options);
      } catch (PDOException $e) {
          die("Connection failed: " . $e->getMessage());
      }
    $firstName = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'psw', FILTER_SANITIZE_STRING);
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $culturalKnowledge = filter_input(INPUT_POST, 'cultural_knowledge', FILTER_SANITIZE_STRING);
    $education = filter_input(INPUT_POST, 'experience', FILTER_SANITIZE_STRING);
    $experience = filter_input(INPUT_POST, 'experience', FILTER_SANITIZE_STRING);
    $pricePerSession = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT);

   
    $target_file = "assets/img/OIP.jpg";
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

    try {
        document.querySelector('.php-email-form').addEventListener('submit', function(event) {
            const checkboxes = document.querySelectorAll('.inp-cbx');
            const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
          
            if (!isChecked) {
              alert('Please select at least one language.');
              event.preventDefault(); 
            }
          });
        $sql = "INSERT INTO partners (first_name, last_name, email, password, photo, location, cultural_knowledge, Education, Experience, PricePerSession, age, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$firstName, $lastName, $email, $password, $target_file, $location, $culturalKnowledge, $education, $experience, $pricePerSession, $age, $gender]);

        if (!empty($_POST['languages']) && !empty($_POST['proficiency_levels'])) {
            $languages = $_POST['languages'];
            $proficiencyLevels = $_POST['proficiency_levels'];

            $sqlLang = "INSERT INTO partner_languages (partner_id, language, ProficiencyLevel) VALUES (?, ?, ?)";
            $stmtLang = $pdo->prepare($sqlLang);

            foreach ($languages as $index => $language) {
                if (isset($proficiencyLevels[$index]) && !empty($proficiencyLevels[$index])) {
                    $stmtLang->execute([$pdo->lastInsertId(), $language, $proficiencyLevels[$index]]);
                }
            }
        }

        echo "<script>alert('Registration successful!'); window.location.href='loginpartner.html';</script>";
    } catch (PDOException $e) {
        die("Could not connect to the database :" . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}
?>