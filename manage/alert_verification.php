<?php
session_start();
require('../conn.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['level'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch existing data if an alert ID is provided
$alert_data = null;
if (isset($_GET['id'])) {
    $alert_id = intval($_GET['id']); // Ensure ID is an integer
    $stmt = $conn->prepare("SELECT * FROM alerts WHERE id = ?");
    $stmt->bind_param("i", $alert_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $alert_data = $result->fetch_assoc();
    $stmt->close();
}

// Fetch admin units for dropdown
$sql2 = "SELECT id, name FROM admin_units";
$result2 = $conn->query($sql2);

// If form is submitted, update the alert
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report'])) {
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
    $history = isset($_POST['history']) ? implode(", ", array_map(fn($value) => mysqli_real_escape_string($conn, $value), $_POST['history'])) : null;
    $health_facility_visit = mysqli_real_escape_string($conn, $_POST['health_facility_visit']);
    $traditional_healer_visit = mysqli_real_escape_string($conn, $_POST['traditional_healer_visit']);
    $symptoms = isset($_POST['symptoms']) ? implode(", ", array_map(fn($symptom) => mysqli_real_escape_string($conn, $symptom), $_POST['symptoms'])) : null;
    $actions = mysqli_real_escape_string($conn, $_POST['actions']);

    $update_sql = "UPDATE alerts SET status=?, date=?, time=?, call_taker=?, cif_no=?, person_reporting=?, village=?, sub_county=?, contact_number=?, source_of_alert=?, alert_case_name=?, alert_case_age=?, alert_case_sex=?, alert_case_pregnant_duration=?, alert_case_village=?, alert_case_parish=?, alert_case_sub_county=?, alert_case_district=?, alert_case_nationality=?, point_of_contact_name=?, point_of_contact_relationship=?, point_of_contact_phone=?, history=?, health_facility_visit=?, traditional_healer_visit=?, symptoms=?, actions=? WHERE id=?";
    
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssssssisisssssssssssssi", $status, $date, $time, $call_taker, $cif_no, $person_reporting, $village, $sub_county, $contact_number, $source_of_alert, $alert_case_name, $alert_case_age, $alert_case_sex, $alert_case_pregnant_duration, $alert_case_village, $alert_case_parish, $alert_case_sub_county, $alert_case_district, $alert_case_nationality, $point_of_contact_name, $point_of_contact_relationship, $point_of_contact_phone, $history, $health_facility_visit, $traditional_healer_visit, $symptoms, $actions, $alert_id);

    if ($stmt->execute()) {
        echo "Alert updated successfully!";
        header("Location: alert_verification.php?id=$alert_id"); // Redirect to avoid resubmission
        exit();
    } else {
        echo "Error updating alert: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alert Verification Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add this to include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- Add this to include Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
    <?php include('../includes/nav.php');?>
   <div id="side-pane-container">
        <?php include("../includes/side-pane.php"); ?>
    </div>
    <div class="entry-screen mt-1">
        <h2 class="text-center mb-4">Alert Verification Form</h2>
        <form action="" method="POST">
            <div class="mb-2">
                
            </div>
            <div class="row">
            <div class="col-md-4 mb-3">
            <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="Alive">Alive</option>
                    <option value="Dead">Dead</option>
                </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?= isset($alert_data['date']) ? htmlspecialchars($alert_data['date']) : ''; ?>" disabled>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" class="form-control" id="time" name="time" value="<?= isset($alert_data['time']) ? htmlspecialchars($alert_data['time']) : ''; ?>" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="call_taker" class="form-label">Call Taker</label>
                    <input type="text" class="form-control" id="call_taker" name="call_taker" value="<?= isset($alert_data['call_taker']) ? htmlspecialchars($alert_data['call_taker']) : ''; ?>" disabled>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="cif_no" class="form-label">CIF No</label>
                    <input type="text" class="form-control" id="cif_no" name="cif_no" value="<?= isset($alert_data['cif_no']) ? htmlspecialchars($alert_data['cif_no']) : ''; ?>">
                </div>
                <div class="col-md-4 mb-3">
                <label for="person_reporting" class="form-label">Person Reporting Alert</label>
                <input type="text" class="form-control" id="person_reporting" name="person_reporting" value="<?= isset($alert_data['person_reporting']) ? htmlspecialchars($alert_data['person_reporting']) : ''; ?>" disabled>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="village" class="form-label">Village</label>
                    <input type="text" class="form-control" id="village" name="village" value="<?= isset($alert_data['village']) ? htmlspecialchars($alert_data['village']) : ''; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="sub_county" class="form-label">Sub-county</label>
                    <input type="text" class="form-control" id="sub_county" name="sub_county" value="<?= isset($alert_data['sub_county']) ? htmlspecialchars($alert_data['sub_county']) : ''; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="tel" class="form-control" id="contact_number" name="contact_number" value="<?= isset($alert_data['contact_number']) ? htmlspecialchars($alert_data['contact_number']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                <label for="source_of_alert" class="form-label">Source of Alert</label>
                <select class="form-select" id="source_of_alert" name="source_of_alert">
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
                <input type="text" class="form-control" id="alert_case_name" name="alert_case_name" value="<?= isset($alert_data['alert_case_name']) ? htmlspecialchars($alert_data['alert_case_name']) : ''; ?>">
            </div>
            
        </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="alert_case_age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="alert_case_age" name="alert_case_age" value="<?= isset($alert_data['alert_case_age']) ? htmlspecialchars($alert_data['alert_case_age']) : ''; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="alert_case_sex" class="form-label">Sex</label>
                    <select class="form-select" id="alert_case_sex" name="alert_case_sex">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="alert_case_pregnant_duration" class="form-label">Pregnant Duration</label>
                    <input type="number" class="form-control" id="alert_case_pregnant_duration" name="alert_case_pregnant_duration" placeholder="(In Months)" value="<?= isset($alert_data['alert_case_pregnant_duration']) ? htmlspecialchars($alert_data['alert_case_pregnant_duration']) : ''; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="alert_case_village" class="form-label">Village/Institution Name</label>
                    <input type="text" class="form-control" id="alert_case_village" name="alert_case_village" value="<?= isset($alert_data['alert_case_village']) ? htmlspecialchars($alert_data['alert_case_village']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="alert_case_parish" class="form-label">Parish</label>
                    <input type="text" class="form-control" id="alert_case_parish" name="alert_case_parish" value="<?= isset($alert_data['alert_case_parish']) ? htmlspecialchars($alert_data['alert_case_parish']) : ''; ?>">
                </div>
                <label for="affiliation" class="form-label">District</label>
                    <select class="form-select" id="affiliation" name="affiliation">
                        <option value="">-- Select District --</option>
                        <?php while ($row = $result2->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['id']); ?>">
                                <?= htmlspecialchars($row['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>   
                <div class="col-md-4 mb-3">
                    <label for="alert_case_district" class="form-label">District</label>
                    <input type="text" class="form-control" id="alert_case_district" name="alert_case_district" >
                </div>
            </div>
            <div class="row">
            <div class="col-md-3 mb-3">
                    <label for="nationality" class="form-label">Nationality</label>
                    <select class="form-select" id="nationality" name="alert_case_nationality">
                        <option value="">Select Nationality</option>
                        <option value="Uganda">Uganda</option>
                    </select>
                </div>
            <div class="col-md-3 mb-3">
                <label for="point_of_contact_name" class="form-label">Point of Contact Name</label>
                <input type="text" class="form-control" id="point_of_contact_name" name="point_of_contact_name" value="<?= isset($alert_data['point_of_contact_name']) ? htmlspecialchars($alert_data['point_of_contact_name']) : ''; ?>">
            </div>
        
          
                <div class="col-md-3 mb-3">
                    <label for="point_of_contact_relationship" class="form-label">Relationship</label>
                    <input type="text" class="form-control" id="point_of_contact_relationship" name="point_of_contact_relationship" value="<?= isset($alert_data['point_of_contact_relationship']) ? htmlspecialchars($alert_data['point_of_contact_relationship']) : ''; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="point_of_contact_phone" class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="point_of_contact_phone" name="point_of_contact_phone" value="<?= isset($alert_data['point_of_contact_phone']) ? htmlspecialchars($alert_data['point_of_contact_phone']) : ''; ?>">
                </div>
            </div>
            <div class="mb-3">
                <label for="history" class="form-label">History (Last 21 Days)</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Other mass gathering" id="mass_gathering" name="history[]" <?= (isset($alert_data['history']) && strpos($alert_data['history'], 'Other mass gathering') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="mass_gathering">Other mass gathering</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Contact of suspect/probable/confirmed case" id="contact_case" name="history[]" <?= (isset($alert_data['history']) && strpos($alert_data['history'], 'Contact of suspect/probable/confirmed case') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="contact_case">Contact of suspect/probable/confirmed case</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Contact of sudden/unexplained death" id="unexplained_death" name="history[]" <?= (isset($alert_data['history']) && strpos($alert_data['history'], 'Contact of sudden/unexplained death') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="unexplained_death">Contact of sudden/unexplained death</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="health_facility_visit" class="form-label">Visited Health Facility</label>
                <textarea class="form-control" id="health_facility_visit" name="health_facility_visit" rows="2" placeholder="Include date, facility name, and contact/location."></textarea>
            </div>
            <div class="mb-3">
                <label for="traditional_healer_visit" class="form-label">Visited Traditional Healer</label>
                <textarea class="form-control" id="traditional_healer_visit" name="traditional_healer_visit" rows="2" placeholder="Include date, healer name, and contact/location."></textarea>
            </div>
            <div class="mb-3">
                <label for="symptoms" class="form-label">Signs and Symptoms</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Fever" id="fever" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'Fever') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="fever">Fever (&ge;38&deg;C)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Headache" id="headache" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'Headache') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="headache">Headache</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="General Weakness" id="weakness" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'General Weakness') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="weakness">General Weakness</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Rash" id="rash" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'rash') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="rash">Skin/Body Rash</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Sore Throat" id="sore_throat" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'Sore Throat') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="sore_throat">Sore Throat</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="actions" class="form-label">Actions Taken</label>
                <textarea class="form-control" id="actions" name="actions" rows="3"></textarea>
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
    $('#affiliation').select2({
        placeholder: "Search for a district...",
        allowClear: true,  // Allow clearing the selection
        ajax: {
            url: '../users/fetch_affiliations.php',  // Endpoint to fetch data dynamically
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