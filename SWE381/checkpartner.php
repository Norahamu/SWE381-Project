<?php
session_start();

if (!isset($_SESSION['partner_id']) || !isset($_SESSION['email'])) {
    header("Location: loginpartner.html");
    exit;
}
?>