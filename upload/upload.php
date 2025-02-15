<?php
require('../conn.php');
// Handle file upload
if (isset($_POST['upload'])) {
    $fileName = $_FILES['csv_file']['name'];
    $fileTmp = $_FILES['csv_file']['tmp_name'];

    // Move uploaded file
    $uploadPath = 'uploads/' . $fileName;
    move_uploaded_file($fileTmp, $uploadPath);

    // Open CSV file
    if (($handle = fopen($uploadPath, 'r')) !== FALSE) {
        $headerSkipped = false;

        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (!$headerSkipped) {
                $headerSkipped = true;
                continue;
            }

            // Mapping CSV columns to database fields
            $status = $conn->real_escape_string($row[10]);
            
            // Format date to MySQL DATE format (YYYY-MM-DD)
            $date = date('Y-m-d', strtotime($row[0]));

            $time = $conn->real_escape_string(str_replace(';', ':', $row[1]));
            $call_taker = $conn->real_escape_string($row[2]);
            $person_reporting = $conn->real_escape_string($row[3]);
            $source_of_alert = $conn->real_escape_string($row[4]);
            $alert_case_name = $conn->real_escape_string($row[15]);
            $alert_case_age = is_numeric($row[17]) ? (int)$row[17] : null;
            $alert_case_sex = $conn->real_escape_string($row[18]);
            $point_of_contact_phone = $conn->real_escape_string($row[19]);
            $point_of_contact_name = $conn->real_escape_string($row[20]);
            $point_of_contact_relationship = $conn->real_escape_string($row[20]);
            $symptoms = $conn->real_escape_string($row[16]);
            $actions = $conn->real_escape_string($row[22]);
            $alert_reported_before = $conn->real_escape_string($row[9]);
            $alert_case_village = $conn->real_escape_string($row[11]);
            $alert_case_parish = $conn->real_escape_string($row[12]);
            $alert_case_sub_county = $conn->real_escape_string($row[13]);
            $alert_case_district = $conn->real_escape_string($row[14]);
            $contact_number = $conn->real_escape_string($row[6]);

            // Insert data into alerts table
            $sql = "INSERT INTO alerts (status, date, time, call_taker, person_reporting, source_of_alert, 
                    alert_case_name, alert_case_age, alert_case_sex, point_of_contact_phone, 
                    point_of_contact_name, point_of_contact_relationship, symptoms, actions, alert_reported_before,alert_case_village,alert_case_parish,alert_case_sub_county,alert_case_district,contact_number)
                    VALUES ('$status', '$date', '$time', '$call_taker', '$person_reporting', '$source_of_alert',
                    '$alert_case_name', '$alert_case_age', '$alert_case_sex', '$point_of_contact_phone',
                    '$point_of_contact_name', '$point_of_contact_relationship', '$symptoms', '$actions', 
                    '$alert_reported_before','$alert_case_village','$alert_case_parish','$alert_case_sub_county',
                    '$alert_case_district','$contact_number')";

            if ($conn->query($sql) !== TRUE) {
                echo "Error inserting data for $alert_case_name: " . $conn->error . "<br>";
            } else {
                echo "Inserted alert for: $alert_case_name <br>";
            }
        }
        fclose($handle);
    }

    unlink($uploadPath); // Remove uploaded file after processing
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Alerts CSV File</title>
</head>
<body>
    <h2>Upload Alerts CSV File</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required><br><br>
        <input type="submit" name="upload" value="Upload & Import">
    </form>
</body>
</html>
