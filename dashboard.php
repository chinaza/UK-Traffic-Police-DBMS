<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>UK Traffic Police Department | Dashboard</title>
    <meta name="description" content="Database Coursework 2" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/dashboard.css" />
</head>

<body>
    <a href="logout.php" class="logout-btn container-raised-1">Logout</a>
    <div class="dashboard-page">
        <div class="centered-container" style="width:50%; min-width:320px">
            <div>
                <h3 class="text-center">
                    WELCOME TO UK POLICE DRIVER INCIDENT DATABASE
                </h3>
                <br />
                <div class="grid-container">
                    <a href="drivers.php" class="menu-btn container-raised-2">
                        <img src="img/driver.svg" class="menu-img" alt="icon" />
                        <p>Query Drivers</p>
                    </a>
                    <a href="vehicles.php" class="menu-btn container-raised-2">
                        <img src="img/car.svg" class="menu-img" alt="icon" />
                        Query Vehicles
                    </a>
                    <a href="incidents.php" class="menu-btn container-raised-2">
                        <img src="img/report.svg" class="menu-img" alt="icon" />
                        Query Incidents
                    </a>
                    <a href="password.php" class="menu-btn container-raised-2">
                        <img src="img/password.svg" class="menu-img" alt="icon" />
                        Change Password
                    </a>

                    <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === "true"): ?>
                    <a href="officers.php" class="menu-btn container-raised-2">
                        <img src="img/officer.svg" class="menu-img" alt="icon" />
                        Manage Police Officers
                    </a>
                    <a href="fines.php" class="menu-btn container-raised-2">
                        <img src="img/fine.svg" class="menu-img" alt="icon" />
                        Manage Fines
                    </a>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>