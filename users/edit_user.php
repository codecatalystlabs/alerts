<?php
session_start();
require('../conn.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user ID
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['update'])) {
    $gname = $_POST['gname'];
    $oname = $_POST['oname'];
    $affiliation = $_POST['affiliation'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];
    //$username = $_POST['username'];
   $updateQuery = "UPDATE users SET email = ?, gname = ?, oname = ?, affiliation = ?, user_type = ?, password = ? WHERE id = ?"; 

$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("ssssssi", $email, $gname, $oname, $affiliation, $user_type, $password, $id);

$stmt->execute();

    
    if ($stmt->execute()) {
        $_SESSION['message'] = "User updated successfully!";
        header('Location: index.php');
        exit();
    } else {
        echo "Error updating user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
     <?php include('../includes/nav.php');?>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <form method="POST">
            <!-- <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
            </div> -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Given Name</label>
                    <input type="text" name="gname" class="form-control" value="<?php echo $user['gname']; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Surname</label>
                    <input type="text" name="surname" class="form-control" value="<?php echo $user['surname']; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Other Name</label>
                    <input type="text" name="oname" class="form-control" value="<?php echo $user['oname']; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Affiliation</label>
                    <input type="text" name="affiliation" class="form-control" value="<?php echo $user['affiliation']; ?>">
                </div>
               <div class="col-md-3 mb-3">
                    <label for="nationality" class="form-label">User Type</label>
                    <select class="form-select" id="user_type" name="user_type" required>
                        <option value="">Select User Type</option>
                        <option value="Admin">Admin</option>
                        <option value="MoH">MoH</option>
                        <option value="REOC">REOD</option>
                        <option value="District">District</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Update</label>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </div>
        <div class="col-md-2 mb-3">
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
        </form>
    </div>
</body>
</html>
