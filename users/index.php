<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['level'])) {
    header("Location: ../index.php");
    exit();
}
require('../conn.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/style.css" rel="stylesheet" type="text/css">

</head>
<body>
     <?php include('../includes/nav.php');?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">User Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">User Management</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Users List</h5>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Given Name</th>
                            <th>Surname</th>
                            <th>Other Name</th>
                            <th>Email</th>
                            <th>Affiliation</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM users";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['gname']}</td>
                                <td>{$row['surname']}</td>
                                <td>{$row['oname']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['affiliation']}</td>
                                <td>{$row['username']}</td>
                                <td><a href='edit_user.php?id={$row['id']}' class='btn btn-primary btn-sm'>Edit</a>
                                    <a href='delete_user.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a></td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add_user.php" class="btn btn-success">Add User</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
