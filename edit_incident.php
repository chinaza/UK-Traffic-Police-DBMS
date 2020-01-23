<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}

// Retrieve people
$personQ = "SELECT * FROM People";
$people = $db->query($personQ);

// Retrieve all vehicles
$vehicleQ = "SELECT * FROM Vehicle";
$vehicles = $db->query($vehicleQ);

// Retrieve offences
$offenceQ = "SELECT * FROM Offence";
$offences = $db->query($offenceQ);

$people_ID = "";
$vehicle_ID = "";
$incident_date = "";
$incident_report = "";
$offence_ID = "";
$id = '';
// Check if id passed, indicating an edit op
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Retrieve existing record
    $query = "SELECT * FROM Incident WHERE incident_ID = $id";
    $incident = $db->query($query, true);
    $people_ID = $incident['people_ID'];
    $vehicle_ID = $incident['vehicle_ID'];
    $incident_date = $incident['incident_date'];
    $incident_report = $incident['incident_report'];
    $offence_ID = $incident['offence_ID'];
}

$status = array("msg" => "", "state" => false);
// On submission of form
if (isset($_POST['people_ID'])) {
    $people_ID = $_POST['people_ID'];
    $vehicle_ID = $_POST['vehicle_ID'];
    $incident_date = $_POST['incident_date'];
    $incident_report = $_POST['incident_report'];
    $offence_ID = $_POST['offence_ID'];
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    $insertQ;
    // store data in db
    if ($id != '') {
        // Update mode
        $insertQ = "UPDATE Incident
        SET vehicle_ID=$vehicle_ID, people_ID=$people_ID, incident_date='$incident_date', incident_report='$incident_report', offence_ID=$offence_ID
        WHERE incident_ID=$id";
    } else {
        // Create mode
        $insertQ = "INSERT INTO Incident(vehicle_ID, people_ID, incident_date, incident_report, offence_ID)
    VALUES ($vehicle_ID, $people_ID, '$incident_date', '$incident_report', $offence_ID)";
    }

    $incident = $db->query($insertQ);

    if ($incident) {
        $status = "Data successfully created";
        $status = array("msg" => "Data successfully created", "state" => $incident);
    } else {
        $status = array("msg" => "An error occured creating data", "state" => $incident);
    }
}
?>
<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>UK Traffic Police Department | Incidents</title>
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
            <img src="img/report.svg" class="dash-img" alt="icon" />
            <h2 class="m-0">Manage Incidents</h2>
            <?php if ($id != ""): ?>
            <h4 class="danger">Editing Incident...</h4>
            <?php endif?>
        </div>
        <div class="form form-edit">
            <form class="login-form" action="edit_incident.php" method="post">
                <?php if ($status['msg'] !== ""): ?>
                <div class="<?=$status["state"] ? "green" : "danger"?> text-center"><?=$status["msg"]?></div>
                <br/>
                <?php endif?>

                <?php if ($id != ""): ?>
                    <input name="id" value="<?=$id?>" type="hidden" />
                <?php endif?>

                <div>
                    <label>Incident Date</label>
                    <input type="date" name="incident_date" value="<?=$incident_date?>" required />
                </div>
                <div>
                    <label>Driver's Licence</label>
                    <input list="persons" name="person" id="person" onchange="setPerson(this)" required />
                    <input type="hidden" name="people_ID" id="people_ID" />
                    <datalist id="persons">
                        <?php foreach ($people as $person): ?>
                            <option value="<?=$person["people_licence"]?>">
                        <?php endforeach?>
                    </datalist>
                    <span id="personName" class="danger"></span>
                    <a href="edit_driver.php?return=edit_incident.php" class="">Add New >></a>
                </div>
                <br/>
                <div>
                    <label>Vehicle's Licence</label>
                    <input list="vehicles" name="vehicle" id="vehicle" onchange="setVehicle(this)" required />
                    <datalist id="vehicles">
                        <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?=$vehicle["vehicle_licence"]?>">
                        <?php endforeach?>
                    </datalist>
                    <input type="hidden" name="vehicle_ID" id="vehicle_ID" />
                    <span id="vehicleName" class="danger"></span>
                    <a href="edit_vehicle.php?return=edit_incident.php" class="">Add New >></a>
                </div>
                <br/>
                <div>
                    <label>Incident Report</label>
                    <textarea rows="3" name="incident_report"  value="<?=$incident_report?>" required><?=$incident_report?></textarea>
                </div>
                <div>
                    <label>Offence</label>
                    <select name="offence_ID" id="offence_ID" value="<?=$offence_ID?>" required>
                        <?php foreach ($offences as $offence): ?>
                        <option value="<?=$offence['offence_ID']?>"><?=$offence['offence_description']?></option>
                        <?php endforeach?>
                    </select>
                </div>
                <button type="submit">Submit</button>
            </form>
        </div>
        <div class="text-center">
            <a href="incidents.php" class="back-btn"><< List Incidents</a>
        </div>
    </div>
    <script>
        const people = JSON.parse('<?=json_encode($people)?>');
        const vehicles = JSON.parse('<?=json_encode($vehicles)?>');

        <?php if (isset($id)): ?>
        document.getElementById('offence_ID').value="<?=$offence_ID?>";

        document.getElementById('people_ID').value="<?=$people_ID?>";
        const person = people.filter(p=>{
            return String(p['people_ID']) === "<?=$people_ID?>";
        });
        document.getElementById('person').value = person.length > 0? person[0]['people_licence']: "";

        document.getElementById('vehicle_ID').value="<?=$vehicle_ID?>";
        const vehicle = vehicles.filter(v=>{
            return String(v['vehicle_ID']) === "<?=$vehicle_ID?>";
        });
        document.getElementById('vehicle').value= vehicle.length > 0? vehicle[0]['vehicle_licence']: "";
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
        const setVehicle = function(e) {
            const vehicle = vehicles.filter(v=>{
                return v['vehicle_licence'] === e.value;
            });
            if (vehicle.length == 0) return;

            const name = `${vehicle[0]['vehicle_colour']} ${vehicle[0]['vehicle_type']}`;
            setElValue('vehicleName', name);
            document.getElementById('vehicle_ID').value = vehicle[0]['vehicle_ID']
        };
    </script>
</body>

</html>