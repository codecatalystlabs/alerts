<?php
require('conn.php');

// Fetch admin units
$sql2 = "SELECT id, name FROM admin_units";
$result2 = $conn->query($sql2);

// Handle form submission
if (isset($_POST['report'])) {
    // Sanitize form data
    $alert_reported_before = mysqli_real_escape_string($conn, $_POST['alert_reported_before']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $person_reporting = mysqli_real_escape_string($conn, $_POST['person_reporting']);
    $village = mysqli_real_escape_string($conn, $_POST['village']);
    $sub_county = mysqli_real_escape_string($conn, $_POST['sub_county']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $alert_case_name = mysqli_real_escape_string($conn, $_POST['alert_case_name']);
    $alert_case_age = mysqli_real_escape_string($conn, $_POST['alert_case_age']);
    $alert_case_sex = mysqli_real_escape_string($conn, $_POST['alert_case_sex']);
    $alert_case_parish = mysqli_real_escape_string($conn, $_POST['alert_case_parish']);
    $point_of_contact_name = mysqli_real_escape_string($conn, $_POST['point_of_contact_name']);
    $point_of_contact_phone = mysqli_real_escape_string($conn, $_POST['point_of_contact_phone']);
    $alert_case_district = mysqli_real_escape_string($conn, $_POST['alert_case_district']);
    $alert_from = 'self_alert';

    // Insert data
    $sql = "INSERT INTO alerts (date, time, person_reporting, village, sub_county, contact_number, alert_case_name, alert_case_age, alert_case_sex, alert_case_parish, point_of_contact_name, point_of_contact_phone, alert_reported_before, alert_case_district, alert_from) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssisssssss", $date, $time, $person_reporting, $village, $sub_county, $contact_number, $alert_case_name, $alert_case_age, $alert_case_sex, $alert_case_parish, $point_of_contact_name, $point_of_contact_phone, $alert_reported_before, $alert_case_district, $alert_from);

    if ($stmt->execute()) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() { document.getElementById('success-message').innerText = 'Alert submitted successfully!'; document.getElementById('success-message').style.display = 'block'; });</script>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
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
    <title>Alert Call Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add this to include Select2 CSS -->
    <link href="style/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- Add this to include Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    
</head>
<body>
   <?php include('includes/nav.php');?>
    <div class="entry-screen-index-open mt-4">
        <h2 class="text-center mb-4">Alert Call Log</h2>
        <form action="" method="POST">
            <div class="mb-2">
                
            </div>
            <div class="row">
            </div>
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date" class="form-label">Time of Call</label>
                    <input type="time" class="form-control" id="time" name="time">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="alert_case_sex" class="form-label">Alert reported before?</label>
                    <select class="form-select" id="alert_reported_before" name="alert_reported_before" required>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="call_taker" class="form-label">Name of person calling</label>
                    <input type="text" class="form-control" id="person_reporting" name="person_reporting">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="contact_number" class="form-label">Number of person calling</label>
                    <input type="tel" class="form-control" id="contact_number" name="contact_number">
                </div>
            </div>
            
            <div class="row">
                <h3>Alert Location</h3>

                <div class="col-md-3 mb-3">
                    <label for="village" class="form-label">Village</label>
                    <input type="text" class="form-control" id="village" name="village">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="alert_case_parish" class="form-label">Parish</label>
                    <input type="text" class="form-control" id="alert_case_parish" name="alert_case_parish">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="sub_county" class="form-label">Sub-county/Division</label>
                    <input type="text" class="form-control" id="sub_county" name="sub_county">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="alert_case_district" class="form-label">District</label>
                        <select class="form-select" id="alert_case_district" name="alert_case_district">
                            <option value="">-- Select District --</option>
                            <?php while ($row = $result2->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($row['name']); ?>">
                                    <?= htmlspecialchars($row['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>                               
                </div>
            </div>
            <div class="row">
                <h3>Case Alert Description</h3>
            <div class="col-md-2 mb-3">
                <label for="alert_case_name" class="form-label">Case Name</label>
                <input type="text" class="form-control" id="alert_case_name" name="alert_case_name">
            </div>
            <div class="col-md-2 mb-3">
                    <label for="alert_case_age" class="form-label">Case Age</label>
                    <input type="number" class="form-control" id="alert_case_age" name="alert_case_age">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="alert_case_sex" class="form-label">Case Sex</label>
                    <select class="form-select" id="alert_case_sex" name="alert_case_sex">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                 <div class="col-md-2 mb-3">
                <label for="point_of_contact_name" class="form-label">Name of Next of Kin</label>
                <input type="text" class="form-control" id="point_of_contact_name" name="point_of_contact_name">
            </div>
            <div class="col-md-3 mb-3">
                    <label for="point_of_contact_phone" class="form-label">Next of Kin Phone Number</label>
                    <input type="tel" class="form-control" id="point_of_contact_phone" name="point_of_contact_phone">
                </div>
        </div>
            
           <button type="submit" class="btn btn-primary" name="report">Submit</button>
        </form>
    </div>
    <script>
    function refreshSidePane() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("side-pane-container").innerHTML = xhr.responseText;
            }
        };
        xhr.open("GET", "includes/side-pane.php", true);
        xhr.send();
    }

    // Refresh every 3 minutes (180000 milliseconds)
    setInterval(refreshSidePane, 15000);
    document.addEventListener("DOMContentLoaded", function () {
        // Restrict date input to today or earlier
        let dateInput = document.getElementById("date");
        let today = new Date().toISOString().split("T")[0];
        dateInput.setAttribute("max", today);

        // Get the necessary elements
        let sexInput = document.getElementById("alert_case_sex");
        let ageInput = document.getElementById("alert_case_age");
        let pregnancyDurationInput = document.getElementById("alert_case_pregnant_duration");

        function togglePregnancyField() {
            let sex = sexInput.value;
            let age = parseInt(ageInput.value, 10);

            if (sex === "Male" || (age && age < 13)) {
                pregnancyDurationInput.value = ""; // Clear input
                pregnancyDurationInput.setAttribute("disabled", "disabled");
            } else {
                pregnancyDurationInput.removeAttribute("disabled");
            }
        }

        // Attach event listeners
        sexInput.addEventListener("change", togglePregnancyField);
        ageInput.addEventListener("input", togglePregnancyField);

        // Call function on page load to apply rules if fields are prefilled
        togglePregnancyField();
    });
    $(document).ready(function() {
    // Initialize select2 for search functionality
    $('#alert_case_district').select2({
        placeholder: "Search for a district...",
        allowClear: true,  // Allow clearing the selection
        ajax: {
            url: 'users/fetch_affiliations.php',  // Endpoint to fetch data dynamically
            dataType: 'json',
            delay: 250,  // Delay to avoid too many requests on each keystroke
            processResults: function(data) {
                return {
                    results: data  // Process the result and return it
                };
            },
            cache: true
        },
        minimumInputLength: 1 // Minimum input length before search is triggered
    });
});
</script>

</body>
</html>