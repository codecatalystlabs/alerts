<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require('../conn.php');

$updated_id = isset($_GET['updated_id']) ? $_GET['updated_id'] : null;
$affiliation = $_SESSION['affiliation'];
$user_type = $_SESSION['user_type'];
$username = $_SESSION['username'];
$level = $_SESSION['level'];

// Filter inputs
$district = isset($_GET['district']) ? $_GET['district'] : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Pagination settings
$limit = 10;  // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


// Base query
$sql = "SELECT * FROM alerts WHERE 1=1";
$params = [];
$types = "";

// Apply filters
if (!empty($affiliation) && !in_array($affiliation, ['MoH Call Centre', 'EMS', 'REOC'])) {
    $sql .= " AND alert_case_district = ?";
    $params[] = $affiliation;
    $types .= "s";
}
if (!empty($district)) {
    $sql .= " AND alert_case_district LIKE ?";
    $params[] = "{$district}%";  
    $types .= "s";
}
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND date BETWEEN ? AND ?";
    $params[] = $from_date;
    $params[] = $to_date;
    $types .= "ss";
}

// Get total records count
$count_sql = str_replace("SELECT *", "SELECT COUNT(*) AS total", $sql);
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Append ORDER BY, LIMIT, OFFSET
$sql .= " ORDER BY date DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

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
    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
    <?php include('../includes/nav.php'); ?>
    <div class="container call_log-body mt-2">
        <h5 class="text-center mb-2">Alert Call Log</h5>
        
                <!-- Filter Form -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-2">
                    <label>District:</label>
                    <input type="text" name="district" class="form-control" value="<?php echo htmlspecialchars($district); ?>">
                </div>
                <div class="col-md-3">
                    <label>From Date:</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo htmlspecialchars($from_date); ?>">
                </div>
                <div class="col-md-3">
                    <label>To Date:</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo htmlspecialchars($to_date); ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="export_excel.php?district=<?php echo urlencode($district); ?>&from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>" class="btn btn-success mb-3">Export to Excel</a>
                </div>
            </div>
        </form>

        


        <div class="table-container">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
                <tr>
                    <th rowspan="2">Name of Person Calling</th>
                    <th rowspan="2">Source of Signal</th>
                    <th rowspan="2">Person Calling Phone</th>
                    <th rowspan="2">Date</th>
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
        </thead>
        <tbody>
            <?php while ($des = $result->fetch_assoc()): ?>
                <tr id="row-<?php echo $des['id']; ?>" class="<?php echo ($updated_id == $des['id']) ? 'highlight' : ''; ?>">
                    <td><?php echo htmlspecialchars($des['person_reporting']); ?></td>
                    <td><?php echo htmlspecialchars($des['alert_from']); ?></td>
                    <td><?php echo htmlspecialchars($des['contact_number']); ?></td>
                    <td><?php echo htmlspecialchars($des['date']); ?></td>
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
                        <button class="btn btn-primary btn-sm verify-btn" data-id="<?php echo $des['id']; ?>">Verify</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<nav>
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&district=<?php echo urlencode($district); ?>&from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>">Previous</a>
            </li>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&district=<?php echo urlencode($district); ?>&from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&district=<?php echo urlencode($district); ?>&from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('.verify-btn').click(function(){
        var alertId = $(this).data('id');

        $.ajax({
            url: 'generateToken.php',
            type: 'POST',
            data: { alert_id: alertId },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    // Redirect to verification page with token
                    window.location.href = 'alert_verification.php?token=' + response.token + '&id=' + alertId;
                } else {
                    alert('Error generating token.');
                }
            }
        });
    });
});
</script>

</body>
</html>
