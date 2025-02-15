<?php
session_start();
require('../conn.php');

// Retrieve the alert id from the URL
if (!isset($_GET['id'])) {
    die('No alert id provided');
}
$alert_id = $_GET['id'];

// Check if a token already exists for this alert_id
$stmt = $conn->prepare("SELECT token FROM alert_verification_tokens WHERE alert_id = ? LIMIT 1");
$stmt->bind_param("i", $alert_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // A token already exists, fetch it
    $stmt->bind_result($token);
    $stmt->fetch();
} else {
    // No token exists, generate a new one
    require 'functions.php'; // Assuming generateToken() is defined here
    $token = generateToken();
    $expires_at = date('Y-m-d H:i:s', strtotime('+20 hours'));

    // Insert the new token into the database
    $insert_stmt = $conn->prepare("INSERT INTO alert_verification_tokens (alert_id, token, expires_at) VALUES (?, ?, ?)");
    $insert_stmt->bind_param("iss", $alert_id, $token, $expires_at);
    $insert_stmt->execute();

    // Optionally, set a session variable or do additional processing
    $_SESSION['token'] = $token;
}
//$alert_id = $row['alert_id'];

// Fetch existing alert data
$stmt = $conn->prepare("SELECT * FROM alerts WHERE id = ?");
$stmt->bind_param("i", $alert_id);
$stmt->execute();
$alert_data = $stmt->get_result()->fetch_assoc();
$stmt->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report'])) {
    // Sanitize inputs
    $inputs = [
        'status', 'verification_date', 'verification_time', 'cif_no', 'person_reporting',
        'village', 'sub_county', 'contact_number', 'source_of_alert', 'alert_case_name',
        'alert_case_age', 'alert_case_sex', 'alert_case_pregnant_duration', 'alert_case_village',
        'alert_case_parish', 'alert_case_sub_county', 'alert_case_district', 'alert_case_nationality',
        'point_of_contact_name', 'point_of_contact_relationship', 'point_of_contact_phone',
        'health_facility_visit', 'traditional_healer_visit'
    ];

    $data = [];
    foreach ($inputs as $input) {
        $data[$input] = isset($_POST[$input]) ? mysqli_real_escape_string($conn, $_POST[$input]) : null;
    }

    $data['cif_no'] = strtoupper($data['cif_no']);
    $data['history'] = isset($_POST['history']) ? implode(", ", array_map(fn($val) => mysqli_real_escape_string($conn, $val), $_POST['history'])) : null;
    $data['symptoms'] = isset($_POST['symptoms']) ? implode(", ", array_map(fn($val) => mysqli_real_escape_string($conn, $val), $_POST['symptoms'])) : null;
    $data['actions'] = isset($_POST['actions']) ? implode(", ", array_map(fn($val) => mysqli_real_escape_string($conn, $val), $_POST['actions'])) : null;

    // Update alert
    $update_sql = "UPDATE alerts SET status=?, verification_date=?, verification_time=?, cif_no=?, person_reporting=?, village=?, sub_county=?, contact_number=?, source_of_alert=?, alert_case_name=?, alert_case_age=?, alert_case_sex=?, alert_case_pregnant_duration=?, alert_case_village=?, alert_case_parish=?, alert_case_sub_county=?, alert_case_district=?, alert_case_nationality=?, point_of_contact_name=?, point_of_contact_relationship=?, point_of_contact_phone=?, history=?, health_facility_visit=?, traditional_healer_visit=?, symptoms=?, actions=? WHERE id=?";
    
    $stmt = $conn->prepare($update_sql);
    $data_values = array_values($data);
    $data_values[] = $alert_id; // Append $alert_id to the array
    $stmt->bind_param("ssssssssssisisssssssssssssi", ...$data_values);

    
    if ($stmt->execute()) {
        // Mark the token as used
        $stmt = $conn->prepare("UPDATE alert_verification_tokens SET used = 1 WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "Alert updated successfully!";
        
        
        // Notify EMS if action includes "EMS"
        if (strpos($data['actions'], 'EMS') !== false) {
            $stmt = $conn->prepare("SELECT contact_number, person_reporting FROM alerts WHERE id = ?");
            $stmt->bind_param("i", $alert_id);
            $stmt->execute();
            $details = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            $stmt = $conn->prepare("SELECT email, gname, surname, oname FROM users WHERE affiliation = 'EMS' OR affiliation = 'MoH Call Centre'");
            $stmt->execute();
            $users = $stmt->get_result();
            
            while ($person = $users->fetch_assoc()) {
                $to = $person['email'];
                $subject = "Action needed for alert #$alert_id";
                $message = "Dear EMS Team, after verification, alert #$alert_id needs your attention. Please contact {$details['person_reporting']} at {$details['contact_number']} for more details.";
                $headers = "From: no-reply@alerts.health.go.ug";
                
                if (mail($to, $subject, $message, $headers)) {
                    echo "Mail sent to the respective responders.";
                }
            }
        }
        
                                                            
        header("Location: alert_verification.php?id=$alert_id");
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
<!-- Only include one version of jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Add this to include Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> -->

    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
    <?php include('../includes/nav.php');?>
   <div id="side-pane-container">
        <?php include("../includes/side-pane.php"); ?>
    </div>
    <div class="entry-screen mt-1">
        <h2 class="text-center mb-2">Alert Verification Form</h2>
        <form action="" method="POST" action="">
            <input type="hidden" name="alert_id" value="<?php echo $alert['id']; ?>">
            <div class="mb-2">
                
            </div>
            <div class="row">
            <div class="col-md-3 mb-3">
            <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="Alive">Alive</option>
                    <option value="Dead">Dead</option>
                </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date" class="form-label">Verification Date</label>
                    <input type="date" class="form-control" id="verification_date" name="verification_date" value="<?= isset($alert_data['verification_date']) ? htmlspecialchars($alert_data['verification_date']) : ''; ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="verification_time" class="form-label">Verification Time</label>
                    <input type="time" class="form-control" id="verification_time" name="verification_time" value="<?= isset($alert_data['verification_time']) ? htmlspecialchars($alert_data['verification_time']) : ''; ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="cif_no" class="form-label">CIF No</label>
                    <input type="text" class="form-control" id="cif_no" name="cif_no" value="<?= isset($alert_data['cif_no']) ? htmlspecialchars($alert_data['cif_no']) : ''; ?>">
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3 mb-3">
                <label for="person_reporting" class="form-label">Who reported the Alert</label>
                <input type="text" class="form-control" id="person_reporting" name="person_reporting" value="<?= isset($alert_data['person_reporting']) ? htmlspecialchars($alert_data['person_reporting']) : ''; ?>" disabled>
                </div>
                <div class="col-md-3 mb-3">
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
                <div class="col-md-3 mb-3">
                    <label for="alert_case_village" class="form-label">Village/Institution Name</label>
                    <input type="text" class="form-control" id="alert_case_village" name="alert_case_village" value="<?= isset($alert_data['alert_case_village']) ? htmlspecialchars($alert_data['alert_case_village']) : ''; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="tel" class="form-control" id="contact_number" name="contact_number" value="<?= isset($alert_data['contact_number']) ? htmlspecialchars($alert_data['contact_number']) : ''; ?>">
                </div>
            </div>
            <hr>
            <div class="row">
                
                
                <div class="col-md-3 mb-3">
                <label for="alert_case_name" class="form-label">Name</label>
                <input type="text" class="form-control" id="alert_case_name" name="alert_case_name" value="<?= isset($alert_data['alert_case_name']) ? htmlspecialchars($alert_data['alert_case_name']) : ''; ?>">
            </div>
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
            </div>
            <div class="row">
                
            <hr>
                
        </div>
            <div class="row"> 
                <div class="col-md-3 mb-3">
                    <label for="region">Region:</label>
                    <select id="region" name="region" class="form-control">
                        <option value="">-- Select Region --</option>
                        <?php 
                        // Fetch all regions for dropdown
                        $regions = $conn->query("SELECT id, region FROM regions");
                        while ($row = $regions->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['id']) ?>">
                                <?= htmlspecialchars($row['region']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                <label for="district">District:</label>
                    <select id="district" name="district" class="form-control">
                        <option value="">-- Select District --</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="subcounty">Subcounty:</label>
                    <select id="subcounty" name="subcounty" class="form-control">
                        <option value="">-- Select Subcounty --</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="alert_case_parish" class="form-label">Parish</label>
                    <input type="text" class="form-control" id="alert_case_parish" name="alert_case_parish" value="<?= isset($alert_data['alert_case_parish']) ? htmlspecialchars($alert_data['alert_case_parish']) : ''; ?>">
                </div>
            </div>
            <hr>
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
            <hr>
            <div class="mb-3">
                <label for="history" class="form-label"><strong>History (Last 21 Days)</strong></label></br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Other mass gathering" id="mass_gathering" name="history[]" <?= (isset($alert_data['history']) && strpos($alert_data['history'], 'Other mass gathering') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="mass_gathering">Other mass gathering</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Contact of suspect/probable/confirmed case" id="contact_case" name="history[]" <?= (isset($alert_data['history']) && strpos($alert_data['history'], 'Contact of suspect/probable/confirmed case') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="contact_case">Contact of suspect/probable/confirmed case</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Contact of sudden/unexplained death" id="unexplained_death" name="history[]" <?= (isset($alert_data['history']) && strpos($alert_data['history'], 'Contact of sudden/unexplained death') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="unexplained_death">Contact of sudden/unexplained death</label>
                </div>
            </div>
            <div class="row">
            <div class=" col-md-6 mb-3">
                <label for="health_facility_visit" class="form-label">Visited Health Facility</label>
                <input class="form-control" id="health_facility_visit" name="health_facility_visit" rows="2" placeholder="Include date, facility name, and contact/location."></textarea>
            </div>
            <div class=" col-md-6 mb-3">
                <label for="traditional_healer_visit" class="form-label">Visited Traditional Healer</label>
                <input class="form-control" id="traditional_healer_visit" name="traditional_healer_visit" rows="2" placeholder="Include date, healer name, and contact/location."></textarea>
            </div>
        </div>
            <hr>
            <div class="mb-3">
                <label for="symptoms" class="form-label"><strong>Signs and Symptoms</strong></label></br>
                
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Fever" id="fever" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'Fever') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="fever">Fever (&ge;38&deg;C)</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Headache" id="headache" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'Headache') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="headache">Headache</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="General Weakness" id="weakness" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'General Weakness') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="weakness">General Weakness</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Rash" id="rash" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'rash') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="rash">Skin/Body Rash</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Sore Throat" id="sore_throat" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'Sore Throat') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="sore_throat">Sore Throat</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="vomiting" id="vomiting" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'vomiting') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="vomiting">Vomiting</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="bleeding" id="bleeding" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'bleeding') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="bleeding">Bleeding</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Abdominal Pain" id="Abdominal Pain" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'abdominal_pain') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Abdominal Pain">Abdominal Pain</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Aching Muscles/Joints" id="aching_muscle" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'Aching Muscles/Joints') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="aching_muscle">Aching Muscles/ Pain</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Difficulty Swallowing" id="difficult_swallowing" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'Difficulty Swallowing') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Difficulty Swallowing">Difficulty Swallowing</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Difficulty Breathing" id="difficulty_breathing" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'Difficulty Breathing') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Difficulty Breathing">Difficulty Breathing</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Lethergy/Weakness" id="lethergy_weakness" name="symptoms[]" <?= (isset($alert_data['symptoms']) && strpos($alert_data['symptoms'], 'Lethergy/Weakness') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Lethergy/Weakness">Lethergy/Weakness</label>
                </div>
            </div>
       
            <hr>
            <div class="mb-3">
                <label for="actions" class="form-label"><strong>Actions</strong></label></br>
                
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Case Verification Desk" id="fever" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'Case Verification Desk') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Case Verification Desk">Case Verification Desk</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Discarded" id="fever" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'Discarded') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Discarded">Discarded</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Validated for EMS Evacuation" id="Validated for EMS Evacuation" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'Validated for EMS Evacuation') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Validated for EMS Evacuation">Validated for EMS Evacuation</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Safe Dignified Burial team" id="Safe Dignified Burial team" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], '') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Safe Dignified Burial team">Safe Dignified Burial team</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="For Field Verification" id="For Field Verification" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'For Field Verification') !== false) ? 'checked' : 'For Field Verification'; ?>>
                    <label class="form-check-label" for="For Field Verification">For Field Verification</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Call to verify and follow-up in 24hrs" id="Call to verify and follow-up in 24hrs" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'Call to verify and follow-up in 24hrs') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Call to verify and follow-up in 24hrs">Call to verify and follow-up in 24hrs</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="" id="For review" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'For review') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="For review">For review</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Epi link" id="Epi link" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'Epi link') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Epi link">Epi link</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Samples Picked" id="Samples Picked" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'Samples Picked') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Samples Picked">Samples Picked</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="In Isolation" id="Isolated" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'In Isolation') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="In Isolated">In Isolation</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="In Quarantine" id="In_Quarantine" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'In Quarantine') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="In_Quarantine">In Quarantine</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Pending Lab Results" id="Pending_Lab_Results" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'Pending Lab Results') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Pending Lab Results">Pending Lab Results</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Pending EMS Pickup" id="Pending_EMS_Pickup" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'Pending EMS Pickup') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Pending_EMS_Pickup">Pending EMS Pickup</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="Forwarded to EMS" id="Forwarded_to_EMS" name="actions[]" <?= (isset($alert_data['actions']) && strpos($alert_data['actions'], 'Forwarded to EMS') !== false) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="Forwarded to EMS">Forwarded to EMS</label>
                </div>
            </div>
       
            <button type="submit" class="btn btn-primary" name="report">Submit</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    $(document).ready(function(){
    // When a region is selected, load districts
    $('#region').change(function(){
        var regionId = $(this).val();
        console.log('Region selected:', regionId);
        $.ajax({
            url: 'getDistrict.php',
            type: 'POST',
            data: { region: regionId },
            dataType: 'json',
            success: function(response) {
                console.log('District response:', response);
                $("#district").empty().append("<option value=''>-- Select District --</option>");
                $("#subcounty").empty().append("<option value=''>-- Select Subcounty --</option>");
                $.each(response, function(index, district) {
                    $("#district").append("<option value='" + district.id + "'>" + district.district + "</option>");
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error loading districts:', textStatus, errorThrown);
            }
        });
    });

    // When a district is selected, load subcounties
    $('#district').change(function(){
        var districtId = $(this).val();
        console.log('District selected:', districtId);
        $.ajax({
            url: 'getSubcounties.php',
            type: 'POST',
            data: { district: districtId },
            dataType: 'json',
            success: function(response) {
                console.log('Subcounty response:', response);
                $("#subcounty").empty().append("<option value=''>-- Select Subcounty --</option>");
                $.each(response, function(index, subcounty) {
                    $("#subcounty").append("<option value='" + subcounty.id + "'>" + subcounty.subcounty + "</option>");
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error loading subcounties:', textStatus, errorThrown);
            }
        });
    });
});

</script>

</body>
</html>