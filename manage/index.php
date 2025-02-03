<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['level'])) {
    header("Location: ../index.php");
    exit();
}
$sql2 = "SELECT id, name FROM admin_units"; // Adjust column names as per your DB
$result2 = $conn->query($sql2);
require('../conn.php');
if (isset($_POST['report'])) {
// Capture form data
$status = mysqli_real_escape_string($conn, $_POST['status']);
$date = mysqli_real_escape_string($conn, $_POST['date']);
$time = mysqli_real_escape_string($conn, $_POST['time']);
$call_taker = mysqli_real_escape_string($conn, $_POST['call_taker']);
$cif_no = mysqli_real_escape_string($conn, strtoupper($_POST['cif_no']));
$person_reporting = mysqli_real_escape_string($conn, $_POST['person_reporting']);
$village = mysqli_real_escape_string($conn, $_POST['village']);
$sub_county = mysqli_real_escape_string($conn, $_POST['sub_county']);
$contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
$source_of_alert = mysqli_real_escape_string($conn, $_POST['source_of_alert']);
$alert_case_name = mysqli_real_escape_string($conn, $_POST['alert_case_name']);
$alert_case_age = mysqli_real_escape_string($conn, $_POST['alert_case_age']);
$alert_case_sex = mysqli_real_escape_string($conn, $_POST['alert_case_sex']);
$alert_case_pregnant_duration = mysqli_real_escape_string($conn, $_POST['alert_case_pregnant_duration']) ?? null;
$alert_case_village = mysqli_real_escape_string($conn, $_POST['alert_case_village']);
$alert_case_parish = mysqli_real_escape_string($conn, $_POST['alert_case_parish']);
$alert_case_sub_county = mysqli_real_escape_string($conn, $_POST['alert_case_sub_county']);
$alert_case_district = mysqli_real_escape_string($conn, $_POST['alert_case_district']);
$alert_case_nationality = mysqli_real_escape_string($conn, $_POST['alert_case_nationality']);
$point_of_contact_name = mysqli_real_escape_string($conn, $_POST['point_of_contact_name']);
$point_of_contact_relationship = mysqli_real_escape_string($conn, $_POST['point_of_contact_relationship']);
$point_of_contact_phone = mysqli_real_escape_string($conn, $_POST['point_of_contact_phone']);
$history = isset($_POST['history']) ? implode(", ", array_map(function($value) use ($conn) {
    return mysqli_real_escape_string($conn, $value);
}, $_POST['history'])) : null;

$health_facility_visit = mysqli_real_escape_string($conn, $_POST['health_facility_visit']);
$traditional_healer_visit = mysqli_real_escape_string($conn, $_POST['traditional_healer_visit']);
$symptoms = isset($_POST['symptoms']) ? implode(", ", array_map(function($symptom) use ($conn) {
    return mysqli_real_escape_string($conn, $symptom);
}, $_POST['symptoms'])) : null;
$actions = mysqli_real_escape_string($conn, $_POST['actions']);

// Insert data into the database
$sql = "INSERT INTO alerts (status, date, time, call_taker, cif_no, person_reporting,village, sub_county, contact_number, source_of_alert, alert_case_name, alert_case_age, alert_case_sex, alert_case_pregnant_duration, alert_case_village, alert_case_parish, alert_case_sub_county, alert_case_district, alert_case_nationality, point_of_contact_name, point_of_contact_relationship, point_of_contact_phone, history, health_facility_visit, traditional_healer_visit, symptoms, actions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Ensure that you are passing 27 variables in the bind_param section:
$stmt->bind_param(
    "sssssssssssisisssssssssssss",$status, $date, $time, $call_taker, $cif_no, $person_reporting, $village, $sub_county, $contact_number, $source_of_alert, $alert_case_name, $alert_case_age, $alert_case_sex, $alert_case_pregnant_duration, $alert_case_village, $alert_case_parish, $alert_case_sub_county, $alert_case_district, $alert_case_nationality, $point_of_contact_name, $point_of_contact_relationship, $point_of_contact_phone, $history, $health_facility_visit, $traditional_healer_visit, $symptoms, $actions);


if ($stmt->execute()) {
     echo "Alert submitted successfully!";
    header("Location: ".$_SERVER['PHP_SELF']); // Redirect to the same page
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
    <title>MPOX Alert Verification Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
    <?php include('../includes/nav.php');?>
   <div id="side-pane-container">
        <?php include("../includes/side-pane.php"); ?>
    </div>
    <div class="entry-screen mt-1">
        <h2 class="text-center mb-4">Alert Call Log</h2>
        <form action="" method="POST">
            <div class="mb-2">
                
            </div>
            <div class="row">
            <div class="col-md-4 mb-3">
            <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Alive">Alive</option>
                    <option value="Dead">Dead</option>
                </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" class="form-control" id="time" name="time" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="call_taker" class="form-label">Call Taker</label>
                    <input type="text" class="form-control" id="call_taker" name="call_taker" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="cif_no" class="form-label">CIF No</label>
                    <input type="text" class="form-control" id="cif_no" name="cif_no" required>
                </div>
                <div class="col-md-4 mb-3">
                <label for="person_reporting" class="form-label">Person Reporting Alert</label>
                <input type="text" class="form-control" id="person_reporting" name="person_reporting" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="village" class="form-label">Village</label>
                    <input type="text" class="form-control" id="village" name="village" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="sub_county" class="form-label">Sub-county</label>
                    <input type="text" class="form-control" id="sub_county" name="sub_county" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="tel" class="form-control" id="contact_number" name="contact_number" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                <label for="source_of_alert" class="form-label">Source of Alert</label>
                <select class="form-select" id="source_of_alert" name="source_of_alert" required>
                    <option value="Community">Community</option>
                    <option value="Health Facility">Health Facility</option>
                    <option value="Contact Tracing">Contact Tracing</option>
                    <option value="VHT">VHT</option>
                    <option value="Active Case Search">Active Case Search</option>
                    <option value="SMS Alert">SMS Alert</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="alert_case_name" class="form-label">Name</label>
                <input type="text" class="form-control" id="alert_case_name" name="alert_case_name" required>
            </div>
            </div>
        </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="alert_case_age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="alert_case_age" name="alert_case_age" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="alert_case_sex" class="form-label">Sex</label>
                    <select class="form-select" id="alert_case_sex" name="alert_case_sex" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="alert_case_pregnant_duration" class="form-label">Pregnant Duration</label>
                    <input type="number" class="form-control" id="alert_case_pregnant_duration" name="alert_case_pregnant_duration" placeholder="(In Months)">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="alert_case_village" class="form-label">Village/Institution Name</label>
                    <input type="text" class="form-control" id="alert_case_village" name="alert_case_village" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="alert_case_parish" class="form-label">Parish</label>
                    <input type="text" class="form-control" id="alert_case_parish" name="alert_case_parish" required>
                </div>
                <select class="form-select" id="affiliation" name="affiliation" required>
                    <option value="">-- Select Affiliation --</option>
                    <?php while ($row = $result2->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['id']); ?>">
                            <?= htmlspecialchars($row['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <div class="col-md-4 mb-3">
                    <label for="alert_case_district" class="form-label">District</label>
                    <input type="text" class="form-control" id="alert_case_district" name="alert_case_district" required>
                </div>
            </div>
            <div class="row">
            <div class="col-md-3 mb-3">
                    <label for="nationality" class="form-label">Nationality</label>
                    <select class="form-select" id="nationality" name="alert_case_nationality" required>
                        <option value="">Select Nationality</option>
                        <option value="Uganda">Uganda</option>
                    </select>
                </div>
            <div class="col-md-3 mb-3">
                <label for="point_of_contact_name" class="form-label">Point of Contact Name</label>
                <input type="text" class="form-control" id="point_of_contact_name" name="point_of_contact_name" required>
            </div>
        
          
                <div class="col-md-3 mb-3">
                    <label for="point_of_contact_relationship" class="form-label">Relationship</label>
                    <input type="text" class="form-control" id="point_of_contact_relationship" name="point_of_contact_relationship" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="point_of_contact_phone" class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="point_of_contact_phone" name="point_of_contact_phone" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="history" class="form-label">History (Last 21 Days)</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Other mass gathering" id="mass_gathering" name="history[]">
                    <label class="form-check-label" for="mass_gathering">Other mass gathering</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Contact of suspect/probable/confirmed case" id="contact_case" name="history[]">
                    <label class="form-check-label" for="contact_case">Contact of suspect/probable/confirmed case</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Contact of sudden/unexplained death" id="unexplained_death" name="history[]">
                    <label class="form-check-label" for="unexplained_death">Contact of sudden/unexplained death</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="health_facility_visit" class="form-label">Visited Health Facility</label>
                <textarea class="form-control" id="health_facility_visit" name="health_facility_visit" rows="2" placeholder="Include date, facility name, and contact/location." required></textarea>
            </div>
            <div class="mb-3">
                <label for="traditional_healer_visit" class="form-label">Visited Traditional Healer</label>
                <textarea class="form-control" id="traditional_healer_visit" name="traditional_healer_visit" rows="2" placeholder="Include date, healer name, and contact/location." required></textarea>
            </div>
            <div class="mb-3">
                <label for="symptoms" class="form-label">Signs and Symptoms</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Fever" id="fever" name="symptoms[]">
                    <label class="form-check-label" for="fever">Fever (&ge;38&deg;C)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Headache" id="headache" name="symptoms[]">
                    <label class="form-check-label" for="headache">Headache</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="General Weakness" id="weakness" name="symptoms[]">
                    <label class="form-check-label" for="weakness">General Weakness</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Rash" id="rash" name="symptoms[]">
                    <label class="form-check-label" for="rash">Skin/Body Rash</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Sore Throat" id="sore_throat" name="symptoms[]">
                    <label class="form-check-label" for="sore_throat">Sore Throat</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="actions" class="form-label">Actions Taken</label>
                <textarea class="form-control" id="actions" name="actions" rows="3" required></textarea>
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
</script>

</body>
</html>