<?php
require './config.php';

// Prevent access to dashboard if not logged in
if ($_SESSION['isLoggedIn'] === true) {
    header('Location: dashboard.php');
    exit();
} else {
    header('Location: login.php');
    exit();
}



$result = $db->query("SELECT * FROM User_Access");

echo json_encode($result);
