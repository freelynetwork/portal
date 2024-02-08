<?php
include 'user.php';

// Check if $freelynetwork is false
if (!$freelynetwork) {
    echo "あなたはこのページへのアクセスが許可されていません。<br>3秒後にトップページに戻ります。";
    echo '<meta http-equiv="Refresh" content="3; url=index.php">';
    exit;
}

// Initialize SQLite3 database
$database = new SQLite3('emoji.db');

// Fetch all data from the emoji table
$query = $database->query('SELECT * FROM emoji');
$emojis = [];

while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
    $emojis[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emojiデータ表示</title>
    <style>
        /* CSSスタイル */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .emoji-slider {
            margin-bottom: 20px;
        }

        .emoji-item img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-bottom: 10px;
        }

        .emoji-item a {
            display: inline-block;
            margin-bottom: 10px;
            padding: 5px 10px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .emoji-item a:hover {
            background-color: #45a049;
        }

        .emoji-description {
            font-style: italic;
        }
    </style>
    <!-- jQueryとSlickスライダーライブラリを読み込む -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Emojiデータ</h1>
        <?php foreach ($folders as $folder_name => $emojis): ?>
            <div class="emoji-slider">
                <h2><?php echo htmlspecialchars($folder_name); ?></h2>
                <div class="slider">
                    <?php foreach ($emojis as $emoji): ?>
                        <div class="emoji-item">
                            <img src="<?php echo htmlspecialchars($emoji['image_path']); ?>" alt="Emoji Image">
                            <p class="emoji-description">説明: <?php echo htmlspecialchars($emoji['description']); ?></p>
                            <a href="<?php echo htmlspecialchars($emoji['image_path']); ?>" download>画像ファイルをダウンロード</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // スライダーを初期化する
        $('.slider').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 2000,
            arrows: true,
            dots: true
        });
    </script>
</body>
</html>
