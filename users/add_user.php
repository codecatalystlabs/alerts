<?php
session_start();
require('../conn.php');
$sql2 = "SELECT id, name FROM admin_units"; // Adjust column names as per your DB
$result2 = $conn->query($sql2);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $gname = trim($_POST['gname']);
    $oname = trim($_POST['oname']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $affiliation = trim($_POST['affiliation']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if username exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already taken!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, gname,oname, surname,email,affiliation,password) VALUES (?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $username, $gname,$oname,$surname,$email, $affiliation,$hashed_password);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Registration successful! Please log in.";
                header("Location: index.php");
                exit();
            } else {
                $error = "Something went wrong, try again!";
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add this to include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- Add this to include Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
    <?php include('../includes/nav.php'); ?>
    <div class="container d-flex justify-content-center align-items-center vh-80">
        <div class="card p-3 shadow-lg" style="width: 700px; margin-top: 70px;">
            <h3 class="text-center">Add User</h3>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Given Name</label>
                    <input type="text" name="gname" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Surname</label>
                    <input type="text" name="surname" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Other Name</label>
                    <input type="text" name="oname" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                <label for="source_of_alert" class="form-label">Assign to</label>
                
                <label for="affiliation" class="form-label">District</label>
                    <select class="form-select" id="affiliation" name="affiliation">
                        <option value="">-- Select District --</option>
                        <?php while ($row = $result2->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['id']); ?>">
                                <?= htmlspecialchars($row['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

            </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Add User</label>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </div>
                <!-- <div class="mt-3 text-center">
                    <small>Already have an account? <a href="../manage/index.php">Login here</a></small>
                </div> -->
            </div>
            </form>
        </div>
    </div>
    <script>
   $(document).ready(function() {
    // Initialize select2 for search functionality
    $('#affiliation').select2({
        placeholder: "Search for a district...",
        allowClear: true,  // Allow clearing the selection
        ajax: {
            url: 'fetch_affiliations.php',  // Endpoint to fetch data dynamically
            dataType: 'json',
            delay: 250,  // Delay to avoid too many requests on each keystroke
            processResults: function(data) {
                return {
                    results: data  // Process the result and return it
                };
            },
            cache: true
        },
        minimumInputLength: 3  // Minimum input length before search is triggered
    });
});
</script>
</body>
</html>
