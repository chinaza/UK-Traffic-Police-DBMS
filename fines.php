<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}

// On submission of form
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $fine_amount = $_POST['fine_amount'];
    $fine_points = $_POST['fine_points'];

    $finesQ = "INSERT INTO FINES(fine_amount, fine_points, incident_ID) VALUES($fine_amount, $fine_points, $id)";
    $db->query($finesQ);
}

$query = "SELECT People.people_name AS owner, People.people_licence AS owner_licence,
Vehicle.vehicle_licence AS vehicle_licence, Incident.incident_report AS incident_report,
Incident.incident_ID AS incident_ID, Incident.incident_date AS incident_date, Fines.fine_amount AS fine_amount, Fines.fine_points AS fine_points
FROM Incident
JOIN People ON People.people_ID = Incident.people_ID
JOIN Vehicle ON Vehicle.vehicle_ID = Incident.vehicle_ID
LEFT JOIN Fines ON Fines.incident_ID = Incident.incident_ID
WHERE fine_amount IS NULL OR fine_points IS NULL
";

// Append to query if query parameter passed
$q = isset($_GET['q']) ? $_GET['q'] : "";
if ($q != "") {
    $query = $query . " WHERE Vehicle.vehicle_licence LIKE '%$q%' OR People.people_licence LIKE '%$q%'";
}

$incidents = $db->query($query);

$incidents = $incidents ? $incidents : [];
?>
<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>UK Traffic Police Department | Fines</title>
    <meta name="description" content="Database Coursework 2" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/table.css" />
</head>

<body>
    <div class="tbl-page">
        <nav class="container-raised-4">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <div class="p-20 text-center">
            <img src="img/fine.svg" class="dash-img" alt="icon" />
            <h2 class="m-0">Fines</h2>
            <form action="reports.php" method="get" class="form-search">
                <input type="search" name="q" placeholder="Search by driver's or vehicle's license" class="form-input search-bar" />
                <button type="submit" class="btn container-raised-1">Search</button>
            </form>
            <div class="tbl-container container-raised-2">
                <table>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Owner</th>
                                <th>Owner Licence</th>
                                <th>Vehicle Licence</th>
                                <th>Incident Report</th>
                                <th>Fine Amount</th>
                                <th>Fine Points</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incidents as $incident): ?>
                            <tr>
                                <form action="fines.php" method="post">
                                    <td><?php echo $incident['incident_date']; ?></td>
                                    <td><?php echo $incident['owner']; ?></td>
                                    <td><a href="drivers.php?q=<?=$incident['owner_licence']?>"><?php echo $incident['owner_licence']; ?></a></td>
                                    <td><a href="incidents.php?q=<?=$incident['vehicle_licence']?>"><?php echo $incident['vehicle_licence']; ?></a></td>
                                    <td><?php echo $incident['incident_report']; ?></td>
                                    <td>
                                        <input type="hidden" name="id" value="<?=$incident['incident_ID']?>"/>
                                        <input type="number" name="fine_amount" placeholder="Amount" required />
                                    </td>
                                    <td><input type="number" name="fine_points" placeholder="Points" required /></td>
                                    <td><button type="submit" class="btn add-btn">Update</button></td>
                                </form>
                            </tr>
                            <?php endforeach;?>
                            <?php if (count($incidents) == 0): ?>
                                <span class="danger f-18">No Results found</span>
                            <?php endif?>
                        </tbody>
                    </table>
                </table>
            </div>
        </div>
    </div>
</body>

</html>