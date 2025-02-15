<?php
// URL to encode in the QR code
$url = "https://alerts.health.go.ug";
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Code</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .phone-frame {
            width: 350px;
            background: white;
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            position: relative;
        }
        .logo {
            width: 100px;
            position: relative;
            display: block;
            margin: 0 auto 10px;
        }
        .qr img {
            width: 100%;
            max-width: 250px;
        }
    </style>
</head>
<body>
    <div class="phone-frame">
        <img src="images/MoH Logo.png" alt="Uganda Coat of Arms" class="logo">
        <div class="qr">
            <img src="<?php echo $qrUrl; ?>" alt="QR Code">
            <h3><strong>Alert Call Log</strong></h3>
        </div>
    </div>
</body>
</html>
