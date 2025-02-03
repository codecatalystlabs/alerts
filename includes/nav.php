<?php
//session_start();
require('../conn.php');
$level = $_SESSION['level'];
$_SESSION['level'] = $level;
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
        <li><a href="../manage/index.php">Add Alerts</a></li>
        <li><a href="../manage/alerts.php">View Alerts</a></li>
        <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'Admin'): ?>
            <li><a href="../users/index.php">Manage Users</a></li>
        <?php endif; ?>
        <!-- <li><a href="news.php">Manage Metadata</a></li> -->
        <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
        <!-- <li><a href="resources.php">Resources</a></li>
        <li>
            <a href="learn.php">Learn more about self-care</a>
            <ol class="submenu">
                <li>
                    <a href="srh.php">SRH</a>
                    <ol class="submenu2">
                        <li><a href="hiv.php">HIV</a></li>
                    </ol>
                </li>
                <li>
                    <a href="#">Maternal Health</a>
                    <ol class="submenu2">
                <li>
                    <a href="anc.php">ANC</a>
                </li>
                <li>
                    <a href="pac.php">PAC</a>
                </li>
            </ol>
        </li>
                <li><a href="mental.php">Mental Health</a></li>
                <li><a href="workplace.php">Workplace Support (OHS)</a></li>
                <li><a href="ncd.php">Non-communicable Diseases</a></li>

            </ol>
        </li>
        <li><a href="briefs.php">Policy Briefs</a></li> -->
        
    </ol>
</nav>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>
</body>
</html>