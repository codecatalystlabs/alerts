<?php
//session_start();
require('conn.php');
//$level = $_SESSION['level'];
//$_SESSION['level'] = $level;
//$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
<nav>
    
    <ol class="menu">
        <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="../manage/index.php">Add Alerts</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="../manage/alerts.php">View Alerts</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="../manage/call_log.php">View Call Logs</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'Admin'): ?>
            <li><a href="../users/index.php">Manage Users</a></li>
            <li><a href="../upload/upload.php">Upload Alerts</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
        
            <?php endif; ?>
            <?php if (!isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a class="nav-link" href="manage/login.php">Log in</a></li>
            <?php endif; ?>
    </ol>
</nav>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>
</body>
</html>