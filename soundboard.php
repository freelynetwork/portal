<?php

// Check if the user is logged in
if (!isset($_COOKIE['token'])) {
    echo "ログインされていません。<br>3秒後にホームに戻ります。";
    echo '<meta http-equiv="Refresh" content="3; url=index.php">';
    exit;
}

session_start();

// セッションが10分経過しているかどうかを確認
$currentTime = time();
if (isset($_SESSION['popup_time']) && $currentTime - $_SESSION['popup_time'] < 600) {
    // 10分未満の場合はポップアップを表示しない
} else {
    // 10分経過しているか、セッションが存在しない場合はポップアップを表示
    $_SESSION['popup_time'] = $currentTime;
    echo "<script>document.addEventListener('DOMContentLoaded', function() {document.querySelector('.popup').style.display = 'flex';});</script>";
}

// ポップアップの表示とユーザーの選択を処理
if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    // Yesが選択された場合、セッション時間をクッキーに記録する
    setcookie('popup_time', $currentTime, time() + 600); // 10分間有効なクッキーを設定
    echo "<script>document.querySelector('.popup').style.display = 'none';</script>";
} elseif (isset($_POST['confirm']) && $_POST['confirm'] === 'no') {
    // Noが選択された場合は何もしない
}
?>

<?php
// サウンドボードの設定（管理しやすいように）
$sounds = [
    [
        'soundurl' => 'sound1.mp3',
        'title' => 'Sound 1',
    ],
    [
        'soundurl' => 'sound2.mp3',
        'title' => 'Sound 2',
    ],
    // 他のサウンドを追加
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soundboard</title>
    <style>
        /* CSSスタイル */
        .soundboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 50px; /* ポップアップが表示されるために余白を追加 */
        }

        .sound-card {
            width: 200px;
            height: 200px;
            background-color: #f0f0f0;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .sound-card:hover {
            transform: scale(1.05);
        }

        .sound-card p {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
    </style>
</head>
<body>
  <?php include 'header.php'; ?>
    <!-- サウンドカードの表示 -->
    <div class="soundboard">
        <?php foreach ($sounds as $sound): ?>
            <div class="sound-card" data-soundurl="<?php echo $sound['soundurl']; ?>" onclick="playSound(this)">
                <p><?php echo $sound['title']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- JavaScript -->
    <script>
        function playSound(button) {
            var soundUrl = button.getAttribute('data-soundurl');
            var audio = new Audio(soundUrl);
            audio.play();
        }
    </script>
  <?php include 'footer.php'; ?>
</body>
</html>
