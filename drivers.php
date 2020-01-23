<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false") {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM People";

// Append to query if query parameter passed
$q = isset($_GET['q']) ? $_GET['q'] : "";
if ($q != "") {
    $query = $query . " WHERE people_name LIKE '%$q%' OR people_licence LIKE '%$q%'";
}

$drivers = $db->query($query);

$drivers = $drivers ? $drivers : [];
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
            <img src="img/driver.svg" class="dash-img" alt="icon" />
            <h2 class="m-0">Drivers</h2>
            <form action="drivers.php" method="get" class="form-search">
                <input type="search" name="q" placeholder="Search by driver's name or licence" class="form-input search-bar" />
                <button type="submit" class="btn container-raised-1">Search</button>
            </form>
            <div class="add-div"><a href="edit_driver.php" class="btn add-btn">Add Driver</a></div>
            <div class="tbl-container container-raised-2">
                <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>License</th>
                                <th>Address</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($drivers as $driver): ?>
                            <tr>
                                <td><a href="incidents.php?q=<?=$driver['people_licence']?>"><?php echo $driver['people_name']; ?></a></td>
                                <td><?php echo $driver['people_licence']; ?></td>
                                <td><?php echo $driver['people_address']; ?></td>
                                <td><a href="edit_driver.php?id=<?=$driver['people_ID']?>">Edit</a></td>
                                <td>
                                    <a href="delete.php?return=drivers.php&id=<?=$driver['people_ID']?>"
                                        class="link-danger">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            <?php if (count($drivers) == 0): ?>
                                <span class="danger f-18">No Results found</span>
                            <?php endif?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</body>

</html>