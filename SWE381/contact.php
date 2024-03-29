<?php

  $receiving_email_address = 'Lingo@project.com';

  if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
    include( $php_email_form );
  } else {
    die( 'Unable to load the "PHP Email Form" Library!');
  }

  $contact = new PHP_Email_Form;
  $contact->ajax = true;
  
  $contact->to = $receiving_email_address;
  $contact->from_name = $_POST['name'];
  $contact->from_email = $_POST['email'];
  $contact->subject = $_POST['subject'];


  $contact->add_message( $_POST['name'], 'From');
  $contact->add_message( $_POST['email'], 'Email');
  $contact->add_message( $_POST['message'], 'Message', 10);

  echo $contact->send();



$fnameErr = $lnameErr = $ageErr = $emailErr = $genderErr = $pswErr = $locErr = $bioErr = $photoErr = $langErr = $timeErr = "";
$fname = $lname = $age = $email = $gender = $psw = $loc = $bio = $photo = $lang = $time = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["fname"])) {
    $fnameErr = " First name is required";
  } else {
    $fname = test_input($_POST["fname"]);
  }
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["lname"])) {
    $lnameErr = " Last name is required";
  } else {
    $lname = test_input($_POST["lname"]);
  }
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["age"])) {
    $ageErr = " Age is required";
  } else {
    $age = test_input($_POST["age"]);
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
  }
    

  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }
  
  if (empty($_POST["psw"])) {
    $pswErr = "Password is required";
  } else {
    $psw = test_input($_POST["psw"]);
  }
  
    if (empty($_POST["loc"])) {
    $locErr = "Location is required";
  } else {
    $loc = test_input($_POST["loc"]);
  }
  
    if (empty($_POST["bio"])) {
    $bioErr = "Bio is required";
  } else {
    $bio = test_input($_POST["bio"]);
  }
  
  if (empty($_POST["photo"])) {
    $photo = "";
  } else {
    $photo = test_input($_POST["photo"]);
  }

  if (empty($_POST["lang"])) {
    $lang = "";
  } else {
    $lang = test_input($_POST["lang"]);
  }
  
if (empty($_POST["time"])) {
    $time = "";
  } else {
    $time = test_input($_POST["time"]);
  }

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>