<?php
session_start();
require('../conn.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../manage/index.php');
    exit();
}

// Get user ID
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

// Delete user from database
$query = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['message'] = "User deleted successfully!";
    header('Location: index.php');
    exit();
} else {
    echo "Error deleting user.";
}
?>
