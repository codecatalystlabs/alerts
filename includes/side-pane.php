<?php
include('conn.php');
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
<div class="left-pane">
<?php
$ds = "SELECT * FROM alerts ORDER BY id DESC LIMIT 3;";
$dd = mysqli_query($conn, $ds)or die(mysqli_error($conn));
while ($des = mysqli_fetch_assoc($dd)):
?>
    <textarea disabled class="text" value="">
        <?php echo $des['status'].' '.$des['date'].' '.$des['time'].' '.$des['call_taker'].' '.$des['cif_no'].' '.$des['person_reporting'].' '.$des['village'].' '.$des['sub_county'].' '.$des['contact_number'].' '.$des['source_of_alert'].' '.$des['alert_case_name'].' '.$des['alert_case_age'].' '.$des['alert_case_sex'].' '.$des['alert_case_pregnant_duration'].' '.$des['alert_case_village'].' '.$des['alert_case_parish'].' '.$des['alert_case_sub_county'].' '.$des['alert_case_district'].' '.$des['alert_case_nationality'].' '.$des['point_of_contact_name'].' '.$des['point_of_contact_relationship'].' '.$des['point_of_contact_phone'].' '.$des['history'].' '.$des['health_facility_visit'].' '.$des['traditional_healer_visit'].' '.$des['symptoms'].' '.$des['actions']; ?>
    </textarea>
<?php endwhile; ?>
</div>
</body>
</html>