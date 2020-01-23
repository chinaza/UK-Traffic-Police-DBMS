<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}

$query = "SELECT People.people_name AS owner, People.people_licence AS owner_licence,
Vehicle.vehicle_licence AS vehicle_licence, Incident.incident_report AS incident_report,
Incident.incident_ID AS incident_ID, Incident.incident_date AS incident_date, Fines.fine_amount AS fine_amount, Fines.fine_points AS fine_points
FROM Incident
JOIN People ON People.people_ID = Incident.people_ID
JOIN Vehicle ON Vehicle.vehicle_ID = Incident.vehicle_ID
LEFT JOIN Fines ON Fines.incident_ID = Incident.incident_ID
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
    <title>UK Traffic Police Department | Incidents</title>
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
            <img src="img/report.svg" class="dash-img" alt="icon" />
            <h2 class="m-0">Incidents</h2>
            <form action="reports.php" method="get" class="form-search">
                <input type="search" name="q" placeholder="Search by driver's or vehicle's license" class="form-input search-bar" />
                <button type="submit" class="btn container-raised-1">Search</button>
            </form>
            <div class="add-div"><a href="edit_incident.php" class="btn add-btn">Log Incident</a></div>
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
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incidents as $incident): ?>
                            <tr>
                                <td><?php echo $incident['incident_date']; ?></td>
                                <td><?php echo $incident['owner']; ?></td>
                                <td><a href="drivers.php?q=<?=$incident['owner_licence']?>"><?php echo $incident['owner_licence']; ?></a></td>
                                <td><a href="incidents.php?q=<?=$incident['vehicle_licence']?>"><?php echo $incident['vehicle_licence']; ?></a></td>
                                <td><?php echo $incident['incident_report']; ?></td>
                                <td><?php echo $incident['fine_amount'] ? $incident['fine_amount'] : "N/A"; ?></td>
                                <td><?php echo $incident['fine_points'] ? $incident['fine_points'] : "N/A"; ?></td>
                                <td><a href="edit_incident.php?id=<?=$incident['incident_ID']?>">Edit</a></td>
                                <td>
                                    <a href="delete.php?return=incidents.php&id=<?=$incident['incident_ID']?>"
                                        class="link-danger">Delete</a>
                                </td>
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