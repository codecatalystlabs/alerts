<?php
require('../conn.php');

$search = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT id, name FROM admin_units WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = ["id" => $row['id'], "text" => $row['name']];
}

echo json_encode($data);
?>
