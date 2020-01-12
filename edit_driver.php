<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}

$id = "";
$name = "";
$address = "";
$licence = "";
$status = array("msg" => "", "state" => false);

// Check if id passed, indicating an edit op
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Retrieve existing record
    $query = "SELECT * FROM People WHERE people_ID = $id";
    $driver = $db->query($query, true);
    $name = $driver['people_name'];
    $address = $driver['people_address'];
    $licence = $driver['people_licence'];
}

//Return to previous page if return parameter passed
if (isset($_GET['return'])) {
    $_SESSION['return'] = $_GET['return'];
}

// If form submitted
if (isset($_POST["name"])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $licence = $_POST['licence'];
    $id = $_POST['id'] ?? '';

    if ($id == '') {
        //Run create query if in creation mode
        $query = "INSERT INTO People (people_name, people_address, people_licence) VALUES ('$name','$address','$licence')";
        $driver = $db->query($query, true);
        if ($driver) {
            $status = "Data successfully created";
            $status = array("msg" => "Data successfully created", "state" => $driver);
        } else {
            $status = array("msg" => "An error occured creating data", "state" => $driver);
        }
        //Return to previous page if return parameter passed
        if ($driver && isset($_SESSION['return'])) {
            $return = $_SESSION['return'];
            unset($_SESSION['return']);
            header("Location: $return");
            exit();
        }
    } else {
        //Run edit query if in edit mode
        $query = "UPDATE People
        SET people_name = '$name', people_address = '$address', people_licence = '$licence'
        WHERE people_ID = $id";
        $driver = $db->query($query, true);
        if ($driver) {
            $status = array("msg" => "Data successfully updated", "state" => $driver);
        } else {
            $status = array("msg" => "An error occured creating data", "state" => $driver);
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
            <h2 class="m-0">Manage Drivers</h2>
            <?php if ($id != ""): ?>
            <h4 class="danger">Editing <?=$name?>...</h4>
            <?php endif?>
        </div>
        <div class="form form-edit">
            <form class="login-form" action="edit_driver.php" method="post">
                <?php if ($status['msg'] !== ""): ?>
                <div class="<?=$status["state"] ? "green" : "danger"?> text-center"><?=$status["msg"]?></div>
                <br/>
                <?php endif?>

                <?php if ($id != ""): ?>
                    <input name="id" value="<?=$id?>" type="hidden" />
                <?php endif?>

                <label>Name</label>
                <input type="text" name="name" placeholder="Name" value="<?=$name?>" required />
                <label>Address</label>
                <input type="text" name="address" placeholder="Address" value="<?=$address?>" required />
                <label>Licence</label>
                <input type="text" name="licence" placeholder="Licence" pattern="[\w\d]+" value="<?=$licence?>" required />
                <button type="submit">Submit</button>
            </form>
        </div>
        <div class="text-center">
            <a href="drivers.php" class="back-btn"><< List Drivers</a>
        </div>
    </div>
</body>

</html>