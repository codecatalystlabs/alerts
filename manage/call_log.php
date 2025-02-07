<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['affiliation'])) {
    header("Location: ../index.php");
    exit();
}

require('../conn.php');

$updated_id = isset($_GET['updated_id']) ? $_GET['updated_id'] : null;
$affiliation = $_SESSION['affiliation'];
$user_type = $_SESSION['user_type'];
$username = $_SESSION['username'];
$level = $_SESSION['level'];

// Base query
$sql = "SELECT * FROM alerts";
$params = [];
$types = "";

// Restrict results if affiliation is set
if (!empty($affiliation) && !in_array($affiliation, ['MoH Call Centre', 'EMS', 'REOC'])) {
    $sql .= " WHERE alert_case_district = ?";
    $params[] = $affiliation;
    $types .= "s";
}

// Append ORDER BY clause
$sql .= " ORDER BY date DESC";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php include('../includes/nav.php'); ?>
    <div class="container mt-2">
        <h2 class="text-center mb-2">Alert Call Log</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">Alert ID</th>
                        <th rowspan="2">Name of Person Calling</th>
                        <th rowspan="2">Source of Signal</th>
                        <th rowspan="2">Contact Number of person calling</th>
                        <th rowspan="2">Alert reported before?</th>
                        <th colspan="4">Signal location</th>
                        <th colspan="6">Alert Case</th>  
                        <th rowspan="2">Time</th>
                        <th rowspan="2">Action</th>
                    </tr>
                    <tr>
                         <th>Village</th>
                        <th>Parish</th>
                        <th>Subcounty</th>
                        <th>District</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Contact</th>
                        <th>Next of Kin</th>
                        <th>Contact of Next of Kin</th>
                        
                    </tr>
                </tr>
                </thead>
                <tbody>
                    <?php while ($des = $result->fetch_assoc()): ?>
                    <tr id="row-<?php echo $des['id']; ?>" class="<?php echo ($updated_id == $des['id']) ? 'highlight' : ''; ?>">
                        <td><?php echo htmlspecialchars($des['alert_case_district'].'-'.$des['id']); ?></td>
                        <td><?php echo htmlspecialchars($des['person_reporting']); ?></td>
                        <td><?php echo htmlspecialchars($des['source_of_alert']); ?></td>
                        <td><?php echo htmlspecialchars($des['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($des['alert_reported_before']); ?></td>
                        <td><?php echo htmlspecialchars($des['village']); ?></td>
                        <td><?php echo htmlspecialchars($des['alert_case_parish']); ?></td>
                        <td><?php echo htmlspecialchars($des['sub_county']); ?></td>
                        <td><?php echo htmlspecialchars($des['alert_case_district']); ?></td>                
                        <td><?php echo htmlspecialchars($des['alert_case_name']); ?></td>
                        <td><?php echo htmlspecialchars($des['alert_case_age']); ?></td>
                        <td><?php echo htmlspecialchars($des['alert_case_sex']); ?></td>
                        <td><?php echo htmlspecialchars($des['point_of_contact_name']); ?></td>
                        <td><?php echo htmlspecialchars($des['point_of_contact_relationship']); ?></td>
                        <td><?php echo htmlspecialchars($des['point_of_contact_phone']); ?></td>
                        <td><?php echo htmlspecialchars($des['time']); ?></td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="alert_verification.php?id=<?php echo $des['id']; ?>">Verify</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let updatedRow = document.querySelector(".highlight");
            if (updatedRow) {
                updatedRow.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        });
    </script>
</body>
</html>
