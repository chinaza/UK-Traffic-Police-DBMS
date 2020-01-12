<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}

$id = "";
$username = "";
$role = "";
$password = "";
$status = array("msg" => "", "state" => false);

// Check if id passed, indicating an edit op
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Retrieve existing record
    $query = "SELECT * FROM User_Access WHERE user_ID = $id";
    $user = $db->query($query, true);
    $username = $user['username'];
    $role = $user['role'];
    $password = $user['password'];
}

// If form submitted
if (isset($_POST["username"])) {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $id = $_POST['id'] ?? '';

    $query;
    if ($id == '') {
        //Run create query if in creation mode
        $query = "INSERT INTO User_Access (username, role, password) VALUES ('$username','$role','$password')";

    } else {
        //Run edit query if in edit mode
        $query = "UPDATE User_Access
        SET username = '$username', role = '$role', password = '$password'
        WHERE user_ID = $id";
    }
    $user = $db->query($query, true);
    if ($user) {
        $status = "Data successfully created";
        $status = array("msg" => "Data successfully created", "state" => $user);
    } else {
        $status = array("msg" => "An error occured creating data", "state" => $user);
    }
}
?>
<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>UK Traffic Police Department | Officers</title>
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
            <img src="img/officer.svg" class="dash-img" alt="icon" />
            <h2 class="m-0">Manage Officers</h2>
            <?php if ($id != ""): ?>
            <h4 class="danger">Editing <?=$username?>...</h4>
            <?php endif?>
        </div>
        <div class="form form-edit">
            <form class="login-form" action="edit_officer.php" method="post">
                <?php if ($status['msg'] !== ""): ?>
                <div class="<?=$status["state"] ? "green" : "danger"?> text-center"><?=$status["msg"]?></div>
                <br/>
                <?php endif?>
                <?php if ($id != ""): ?>
                    <input name="id" value="<?=$id?>" type="hidden" />
                <?php endif?>
                <label>Username</label>
                <input type="text" name="username" placeholder="Username" value="<?=$username?>" required />
                <label>Role</label>
                <select name="role" id="role" value="<?=$role?>" required>
                    <option value="officer">Officer</option>
                    <option value="admin">Admin</option>
                </select>
                <label>Password</label>
                <input type="password" name="password" placeholder="Password" value="<?=$password?>" required />
                <button type="submit">Submit</button>
            </form>
        </div>
        <div class="text-center">
            <a href="officers.php" class="back-btn"><< List Officers</a>
        </div>
    </div>

    <script>
        <?php if (isset($id)): ?>
            document.getElementById('role').value = "<?=$role?>";
        <?php endif?>
    </script>
</body>

</html>