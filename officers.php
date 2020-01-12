<?php
require './config.php';

if ($_SESSION['isLoggedIn'] === "false" || $_SESSION['isAdmin'] === "false") {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM User_Access";

// Append to query if query parameter passed
$q = isset($_GET['q']) ? $_GET['q'] : "";
if ($q != "") {
    $query = $query . " WHERE username LIKE '%$q%'";
}

$users = $db->query($query);

$users = $users ? $users : [];
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
            <img src="img/officer.svg" class="dash-img" alt="icon" />
            <h2 class="m-0">Police Officers</h2>
            <div class="add-div"><a href="edit_officer.php" class="btn add-btn">Add Officer</a></div>
            <form action="officers.php" method="get" class="form-search">
                <input type="search" name="q" placeholder="Search by username" class="form-input search-bar" />
                <button type="submit" class="btn container-raised-1">Search</button>
            </form>
            <div class="tbl-container container-raised-2">
                <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['user_ID']; ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['role']; ?></td>
                                <td><a href="edit_officer.php?id=<?=$user['user_ID']?>">Edit</a></td>
                                <td>
                                    <a href="delete.php?return=officers.php&id=<?=$user['user_ID']?>"
                                        class="link-danger">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            <?php if (count($users) == 0): ?>
                                <span class="danger f-18">No Results found</span>
                            <?php endif?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</body>

</html>