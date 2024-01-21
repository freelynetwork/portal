<?php
// Load environment variables from config.php
include 'config.php';

// Get user from environment variable
$user = getenv('USER') !== false ? getenv('USER') : 'Guest';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosekey Portal</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: white;
            text-align: center;
        }

        h2 {
            margin-top: 50px;
            font-size: 2em;
        }

        h3 {
            margin-top: 20px;
            font-size: 1.5em;
            color: #555;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <h2>おかえりなさい <?php echo $user; ?> 様</h2>
    <h3>準備中</h3>

    <?php include 'footer.php'; ?>
</body>
</html>
