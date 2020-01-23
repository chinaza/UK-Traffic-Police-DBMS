<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}

// Retrieve people
$personQ = "SELECT * FROM People";
$people = $db->query($personQ);

$id = "";
$people_ID = "";
$type = "";
$colour = "";
$licence = "";
$status = array("msg" => "", "state" => false);

// Check if id passed, indicating an edit op
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Retrieve existing record
    $query = "SELECT * FROM Vehicle WHERE vehicle_ID = $id";
    $vehicle = $db->query($query, true);

    // Retrieve owner
    $ownerQ = "SELECT * FROM Ownership WHERE vehicle_ID = $id";
    $queryRes = $db->query($ownerQ, true);
    $people_ID = $queryRes ? $queryRes['people_ID'] : '';

    $type = $vehicle['vehicle_type'];
    $colour = $vehicle['vehicle_colour'];
    $licence = $vehicle['vehicle_licence'];
}

//Return to previous page if return parameter passed
if (isset($_GET['return'])) {
    $_SESSION['return'] = $_GET['return'];
}

// If form submitted
if (isset($_POST["type"])) {
    $type = $_POST['type'];
    $colour = $_POST['colour'];
    $people_ID = $_POST['people_ID'];
    $licence = $_POST['licence'];
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    $ownerUpdateQ;
    $veh = $db->query("SELECT vehicle_ID FROM Vehicle WHERE vehicle_licence='$licence'", true);
    $vehicle_ID = $veh['vehicle_ID'];
    if ($id == '') {
        //Run create query if in creation mode
        $ownerUpdateQ = "INSERT INTO Ownership VALUES($people_ID, $vehicle_ID)";
        $query = "INSERT INTO Vehicle (vehicle_type, vehicle_colour, vehicle_licence) VALUES ('$type','$colour','$licence')";
        $vehicle = $db->query($query, true);
        if ($vehicle) {
            $status = "Data successfully created";
            $status = array("msg" => "Data successfully created", "state" => $vehicle);
        } else {
            $status = array("msg" => "An error occured creating data", "state" => $vehicle);
        }
        //Return to previous page if return parameter passed
        if ($vehicle && isset($_SESSION['return'])) {
            $return = $_SESSION['return'];
            unset($_SESSION['return']);
            header("Location: $return");
            exit();
        }
    } else {
        //Run edit query if in edit mode
        $ownerUpdateQ = "UPDATE Ownership SET people_ID = $people_ID WHERE vehicle_ID=$vehicle_ID";
        $query = "UPDATE Vehicle
        SET vehicle_type = '$type', vehicle_colour = '$colour', vehicle_licence = '$licence'
        WHERE vehicle_ID = $id";
        $vehicle = $db->query($query, true);
        if ($vehicle) {
            $status = array("msg" => "Data successfully updated", "state" => $vehicle);
        } else {
            $status = array("msg" => "An error occured updating data", "state" => $vehicle);
        }
    }

    // Update ownership if driver specified
    if ($people_ID != '') {
        $res = $db->query($ownerUpdateQ);

        if (!$res) {
            $status = array("msg" => "An error occured updating data", "state" => $vehicle);
        }
    }
}
?>
<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>UK Traffic Police Department | Vehicles</title>
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
            <img src="img/car.svg" class="dash-img" alt="icon" />
            <h2 class="m-0">Manage Vehicles</h2>
            <?php if ($id != ""): ?>
            <h4 class="danger">Editing <?=$licence?>...</h4>
            <?php endif?>
        </div>
        <div class="form form-edit">
            <form class="login-form" action="edit_vehicle.php" method="post">
                <?php if ($status['msg'] !== ""): ?>
                <div class="<?=$status["state"] ? "green" : "danger"?> text-center"><?=$status["msg"]?></div>
                <br/>
                <?php endif?>
                <?php if ($id != ""): ?>
                    <input name="id" value="<?=$id?>" type="hidden" />
                <?php endif?>
                <div>
                    <label>Driver's Licence</label>
                    <input list="persons" name="person" id="person" onchange="setPerson(this)"/>
                    <input type="hidden" name="people_ID" id="people_ID" />
                    <datalist id="persons">
                        <?php foreach ($people as $person): ?>
                            <option value="<?=$person["people_licence"]?>">
                        <?php endforeach?>
                    </datalist>
                    <span id="personName" class="danger"></span>
                    <a href="edit_driver.php?return=edit_vehicle.php" class="">Add New >></a>
                </div>
                <br/>
                <label>Type</label>
                <input type="text" name="type" placeholder="Type" value="<?=$type?>" required />
                <label>Colour</label>
                <input type="text" name="colour" placeholder="Colour" value="<?=$colour?>" required />
                <label>Licence</label>
                <input type="text" name="licence" placeholder="Licence" pattern="[\w\d]+" value="<?=$licence?>" required />
                <button type="submit">Submit</button>
            </form>
        </div>
        <div class="text-center">
            <a href="vehicles.php" class="back-btn"><< List Vehicles</a>
        </div>
    </div>
    <script>
        const people = JSON.parse('<?=json_encode($people)?>');
        <?php if (isset($id)): ?>
        document.getElementById('people_ID').value="<?=$people_ID?>";
        const person = people.filter(p=>{
            return String(p['people_ID']) === "<?=$people_ID?>";
        });
        document.getElementById('person').value=person.length > 0? person[0]['people_licence']: "";
        <?php endif?>

        const setElValue = (id, value)=>{
            document.getElementById(id).innerHTML = value;
        };
        const setPerson = function(e) {
            const person = people.filter(p=>{
                return p['people_licence'] === e.value;
            });
            if (person.length == 0) return;

            const name = person[0]['people_name'];
            setElValue('personName', name);
            document.getElementById('people_ID').value = person[0]['people_ID']
        };
    </script>
</body>

</html>