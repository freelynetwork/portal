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

        /* 新しいスタイルを追加 */
        .card {
            background-color: #8FED8F;
            padding: 20px;
            margin: 10px;
            text-align: center;
            border-radius: 10px; /* カードを四角形にする */
            width: 300px; /* カードの幅を指定 */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* ドロップシャドウを追加 */
            cursor: pointer;
            transition: transform 0.3s ease; /* クリック時のアニメーション用 */
        }

        .card:hover {
            transform: scale(1.05); /* ホバー時の拡大アニメーション */
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <h2>おかえりなさい <?php echo $user; ?> 様</h2>

    <!-- 新しいカード "Status" を追加 -->
    <div class="card" onclick="window.location.href='/status.php';">
        <h2>Status</h2>
        <p>Rosekeyが起動しているかを確認することができます。</p>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
