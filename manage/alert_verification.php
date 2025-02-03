<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
$level = $_SESSION['level'];
$_SESSION['level'] = $level;
require('../conn.php');

$id = $_GET['id']; // Get the alert ID from the URL

// Fetch the existing record
$sql = "SELECT * FROM alerts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$alert = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if (isset($_POST['update'])) {
    $case_verification_desk = isset($_POST['case_verification_desk']) ? implode(", ", $_POST['case_verification_desk']) : "";
    // $case_verification_desk_reason = mysqli_real_escape_string($conn, $_POST['case_verification_desk_reason']);
    $field_verification = mysqli_real_escape_string($conn, $_POST['field_verification']);
    $field_verification_decision = mysqli_real_escape_string($conn, $_POST['field_verification_decision']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    $lab_result = mysqli_real_escape_string($conn, $_POST['lab_result']);
    $lab_result_date = mysqli_real_escape_string($conn, $_POST['lab_result_date']);

    // Update data in the database
    $sql = "UPDATE alerts SET
    case_verification_desk = ?,
    -- case_verification_desk_reason = ?, 
    field_verification = ?,
    field_verification_decision = ?,
    feedback = ?,
    lab_result = ?,
    lab_result_date = ?,
    is_highlighted = 1
    WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", 
    $case_verification_desk, $field_verification, 
    $field_verification_decision, $feedback, $lab_result, $lab_result_date, $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Record updated successfully'); window.location.href='alerts.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Verification and Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php include('../includes/nav.php');?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Verification and Feedback</h2>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $alert['id']; ?>">

            <!-- Case Verification Desk -->
            <div class="mb-3">
                <label class="form-label">Case Verification Desk (Tick all that apply)</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Validated for EMS Evacuation" 
                        id="validated_ems" name="case_verification_desk[]" 
                        <?php echo (strpos($alert['case_verification_desk'], 'Validated for EMS Evacuation') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="validated_ems">Validated for EMS Evacuation</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Safe Dignified Burial Team" 
                        id="sdb_team" name="case_verification_desk[]" 
                        <?php echo (strpos($alert['case_verification_desk'], 'Safe Dignified Burial Team') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="sdb_team">Safe Dignified Burial Team</label>
                </div>
                <!-- <textarea class="form-control mt-2" name="case_verification_desk_reason" placeholder="Reason for discarded cases"><?php echo htmlspecialchars($alert['case_verification_desk_reason']); ?></textarea> -->
            </div>

            <!-- Field Verification -->
            <div class="mb-3">
                <label class="form-label">Field Verification</label>
                <textarea class="form-control" name="field_verification" rows="2"><?php echo htmlspecialchars($alert['field_verification']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Field Verification Decision</label>
                <textarea class="form-control" name="field_verification_decision" rows="2"><?php echo htmlspecialchars($alert['field_verification_decision']); ?></textarea>
            </div>

            <!-- Feedback -->
            <div class="mb-3">
                <label class="form-label">Feedback</label>
                <textarea class="form-control" name="feedback" rows="2"><?php echo htmlspecialchars($alert['feedback']); ?></textarea>
            </div>

            <!-- Lab Results -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lab Result</label>
                    <select class="form-select" name="lab_result">
                        <option value="Positive" <?php echo ($alert['lab_result'] == 'Positive') ? 'selected' : ''; ?>>Positive</option>
                        <option value="Negative" <?php echo ($alert['lab_result'] == 'Negative') ? 'selected' : ''; ?>>Negative</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lab Result Date</label>
                    <input type="date" class="form-control" name="lab_result_date" value="<?php echo $alert['lab_result_date']; ?>" id="lab_result_date">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" name="update">Update</button>
        </form>
    </div>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
        // Restrict date input to today or earlier
        let dateInput = document.getElementById("lab_result_date");
        let today = new Date().toISOString().split("T")[0];
        dateInput.setAttribute("max", today);
    });
    </script>
</body>
</html>
