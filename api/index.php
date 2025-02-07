<?php
require('../conn.php');

// Set headers for API responses
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Determine request method
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = explode("/", trim($_SERVER['REQUEST_URI'], "/"));

if ($method === 'POST' && isset($request_uri[1]) && $request_uri[1] === 'submit-alert') {
    // Read and decode JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
        exit;
    }

    // Extract and sanitize input
    $alert_reported_before = mysqli_real_escape_string($conn, $data['alert_reported_before'] ?? '');
    $date = mysqli_real_escape_string($conn, $data['date'] ?? '');
    $time = mysqli_real_escape_string($conn, $data['time'] ?? '');
    $person_reporting = mysqli_real_escape_string($conn, $data['person_reporting'] ?? '');
    $village = mysqli_real_escape_string($conn, $data['village'] ?? '');
    $sub_county = mysqli_real_escape_string($conn, $data['sub_county'] ?? '');
    $contact_number = mysqli_real_escape_string($conn, $data['contact_number'] ?? '');
    $alert_case_name = mysqli_real_escape_string($conn, $data['alert_case_name'] ?? '');
    $alert_case_age = mysqli_real_escape_string($conn, $data['alert_case_age'] ?? '');
    $alert_case_sex = mysqli_real_escape_string($conn, $data['alert_case_sex'] ?? '');
    $alert_case_parish = mysqli_real_escape_string($conn, $data['alert_case_parish'] ?? '');
    $point_of_contact_name = mysqli_real_escape_string($conn, $data['point_of_contact_name'] ?? '');
    $point_of_contact_phone = mysqli_real_escape_string($conn, $data['point_of_contact_phone'] ?? '');
    $alert_case_district = mysqli_real_escape_string($conn, $data['alert_case_district'] ?? '');
    $alert_from = 'Community Alerts';
    $symptoms = isset($data['symptoms']) ? implode(", ", array_map(fn($symptom) => mysqli_real_escape_string($conn, $symptom), $data['symptoms'])) : null;

    // Prepare and execute query
    $sql = "INSERT INTO alerts (date, time, person_reporting, village, sub_county, contact_number, alert_case_name, alert_case_age, alert_case_sex, alert_case_parish, point_of_contact_name, point_of_contact_phone, alert_reported_before, alert_case_district, alert_from, symptoms) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssissssssss", $date, $time, $person_reporting, $village, $sub_county, $contact_number, $alert_case_name, $alert_case_age, $alert_case_sex, $alert_case_parish, $point_of_contact_name, $point_of_contact_phone, $alert_reported_before, $alert_case_district, $alert_from, $symptoms);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Alert submitted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Fetch all alerts
if ($method === 'GET' && isset($request_uri[1]) && $request_uri[1] === 'get-alerts') {
    $result = $conn->query("SELECT * FROM alerts ORDER BY date DESC");

    $alerts = [];
    while ($row = $result->fetch_assoc()) {
        $alerts[] = $row;
    }

    echo json_encode(["status" => "success", "alerts" => $alerts]);
    exit;
}

// Fetch all admin units
if ($method === 'GET' && isset($request_uri[1]) && $request_uri[1] === 'get-admin-units') {
    $result = $conn->query("SELECT id, name FROM admin_units");

    $admin_units = [];
    while ($row = $result->fetch_assoc()) {
        $admin_units[] = $row;
    }

    echo json_encode(["status" => "success", "admin_units" => $admin_units]);
    exit;
}

// Default response for undefined routes
echo json_encode(["status" => "error", "message" => "Invalid API endpoint"]);
exit;
?>
