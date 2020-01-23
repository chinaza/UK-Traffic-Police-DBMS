<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "true") {
    header('Location: dashboard.php');
    exit();
}

$errorMsg = "";

// If form submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $db->query("SELECT * FROM User_Access WHERE username=\"$username\" AND password=\"$password\" LIMIT 1", true);

    if ($result) {
        // Update loggedin variable in session
        $_SESSION['isLoggedIn'] = "true";
        if ($result['role'] === 'admin') {
            $_SESSION['isAdmin'] = "true";
        }
        $_SESSION['userId'] = $result['user_ID'];

        // Redirect to dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        $errorMsg = "Invalid username or password";
    }
}

?>
<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>UK Traffic Police Department | Login</title>
    <meta name="description" content="Database Coursework 2" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/login.css" />
</head>

<body>
    <div class="login-page">
        <div>
            <div class="text-center">
                <img src="img/driver.svg" class="login-img" alt="icon" />
                <h3 style="font-weight: bolder;" class="col-white">
                    UK POLICE VEHICLE DEPARTMENT
                </h3>
            </div>
        </div>
        <div class="form">
            <form class="login-form" method="post" action="login.php">
                <?=$errorMsg ? "<span class=\"danger\">$errorMsg</span><br/><br/>" : ""?>
                <label>Username</label>
                <input type="text" name="username" placeholder="username" required />
                <label>Password</label>
                <input type="password" name="password" placeholder="password" required />
                <button>login</button>
            </form>
        </div>
    </div>
</body>

</html>