<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}

$status = array("msg" => "", "state" => false);

// If form submitted
if (isset($_POST["password"])) {
    $id = $_SESSION['userId'];
    $password = $_POST['password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        $status = array("msg" => "Password mismatch", "state" => false);
    } else {
        $query = "UPDATE User_Access SET password='$new_password' WHERE user_ID=$id AND password='$password'";
        $result = $db->query($query);

        if ($result == 1) {
            $status = array("msg" => "Password change successful", "state" => true);
        } else {
            $status = array("msg" => "Password mismatch", "state" => false);
        }
    }
}
?>
<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>UK Traffic Police Department | Drivers</title>
    <meta name="description" content="Database Coursework 2" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/addform.css" />
</head>

<body>
    <div>
        <nav class="container-raised-4">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <div class="p-30 text-center">
            <img src="img/driver.svg" class="dash-img" alt="icon" />
            <h2 class="m-0">Change Password</h2>
        </div>
        <div class="form form-edit">
            <form class="login-form" action="password.php" method="post">
                <?php if ($status['msg'] !== ""): ?>
                <div class="<?=$status["state"] ? "green" : "danger"?> text-center"><?=$status["msg"]?></div>
                <br/>
                <?php endif?>
                <label>Current Password</label>
                <input type="password" name="password" placeholder="Password" required />
                <label>New Password</label>
                <input type="password" name="new_password" placeholder="New Password" required />
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required />
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>