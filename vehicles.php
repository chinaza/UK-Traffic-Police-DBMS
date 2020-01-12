<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}

$query = "SELECT People.people_name AS owner, People.people_licence AS owner_licence,
Vehicle.vehicle_ID AS vehicle_ID, Vehicle.vehicle_type AS vehicle_type, Vehicle.vehicle_colour AS vehicle_colour, Vehicle.vehicle_licence AS vehicle_licence
FROM Vehicle
LEFT JOIN Ownership ON Ownership.vehicle_ID = Vehicle.vehicle_ID
LEFT JOIN People ON People.people_ID = Ownership.people_ID
";

// Append to query if query parameter passed
$q = isset($_GET['q']) ? $_GET['q'] : "";
if ($q != "") {
    $query = $query . " WHERE Vehicle.vehicle_licence LIKE '%$q%'";
}

$vehicles = $db->query($query);

$vehicles = $vehicles ? $vehicles : [];
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
            <img src="img/car.svg" class="dash-img" alt="icon" />
            <h2 class="m-0">Vehicles</h2>
            <form action="vehicles.php" method="get" class="form-search">
                <input type="search" name="q" placeholder="Search by vehicle's licence" class="form-input search-bar" />
                <button type="submit" class="btn container-raised-1">Search</button>
            </form>
            <div class="add-div"><a href="edit_vehicle.php" class="btn add-btn">Add Vehicle</a></div>
            <div class="tbl-container container-raised-2">
                <table>
                    <table>
                        <thead>
                            <tr>
                                <th>Owner</th>
                                <th>Owner Licence</th>
                                <th>Vehicle Type</th>
                                <th>Vehicle colour</th>
                                <th>Vehicle Licence</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicles as $vehicle): ?>
                            <tr>
                                <td><?php echo $vehicle['owner'] ?? "N/A"; ?></td>
                                <td><?php echo $vehicle['owner_licence'] ?? "N/A"; ?></td>
                                <td><?php echo $vehicle['vehicle_type']; ?></td>
                                <td><?php echo $vehicle['vehicle_colour']; ?></td>
                                <td><?php echo $vehicle['vehicle_licence']; ?></td>
                                <td><a href="edit_vehicle.php?id=<?=$vehicle['vehicle_ID']?>">Edit</a></td>
                                <td>
                                    <a href="delete.php?return=vehicles.php&id=<?=$vehicle['vehicle_ID']?>"
                                        class="link-danger">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            <?php if (count($vehicles) == 0): ?>
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