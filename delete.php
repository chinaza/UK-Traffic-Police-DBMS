<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id']) && isset($_GET['return'])) {
    $id = $_GET['id'];
    $return = $_GET['return'];
    switch ($return) {
        case 'drivers.php':
            $q = "DELETE FROM People WHERE people_ID=$id";
            $db->query($q);
            break;
        case 'vehicles.php':
            $q = "DELETE FROM Vehicle WHERE vehicle_ID=$id";
            $db->query("DELETE FROM Ownership WHERE vehicle_ID=$id");
            $db->query($q);
            break;
        case 'incidents.php':
            $q = "DELETE FROM Incident WHERE incident_ID=$id";
            $db->query($q);
            break;
        case 'officers.php':
            $q = "DELETE FROM User_Access WHERE user_ID=$id";
            $db->query($q);
            break;
        case 'fines.php':
            $q = "DELETE FROM Fine WHERE fine_ID=$id";
            $db->query($q);
            break;

        default:
            break;
    }

    header("Location: $return");
    exit();
}
