<?php
session_start();

if (!isset($_SESSION['learner_id']) || !isset($_SESSION['email'])) {
    header("Location: loginlearner.html");
    exit;
}
?>