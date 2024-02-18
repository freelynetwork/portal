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
        'soundurl' => 'https://vocaloid.social/files/5c54fbd7-15ff-4070-85a4-b049a79a3b62',
        'title' => 'いいのよ',
        'filesid' => '9pughcn4eelc01dx',
    ],
    [
        'soundurl' => 'https://vocaloid.social/files/0f748193-a0d9-44fd-b39d-01d9f80c3659',
        'title' => 'おはよー',
        'filesid' => '9pugit13eelc01e3',
    ],
    [
        'soundurl' => 'https://vocaloid.social/files/ddf1a2c9-a1ff-405e-b7ef-1534f39eca4e',
        'title' => 'きゃー',
        'filesid' => '9pugisugeelc01dz',
    ],
    [
        'soundurl' => 'https://vocaloid.social/files/6e83265c-4799-40a7-badf-d56a80d25211',
        'title' => 'ごめん',
        'filesid' => '9pugit11eelc01e2',
    ],
    [
        'soundurl' => 'https://vocaloid.social/files/2b022fe2-b73e-4a37-a520-db685a8766c4',
        'title' => 'シラー',
        'filesid' => '9pugisyoeelc01e0',
    ],
    [
        'soundurl' => 'https://vocaloid.social/files/dfabb77b-85e2-4595-b8b8-da0cd38472d9',
        'title' => 'すごい!',
        'filesid' => '9pugisz6eelc01e1',
    ],
    [
        'soundurl' => 'https://vocaloid.social/files/809edb57-cb05-47ca-917f-dcebc12af636',
        'title' => 'どういたしまして',
        'filesid' => '9pugit2beelc01e4',
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
            position: relative; /* 相対位置指定 */
            width: 200px;
            height: 200px;
            background-color: #f0f0f0;
            border-radius: 10px;
            display: flex;
            flex-direction: column; /* カード内の要素を縦方向に配置する */
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

        .share-button {
            position: absolute; /* 絶対位置指定 */
            bottom: 10px; /* カードの下端からの距離 */
            right: 10px; /* カードの右端からの距離 */
            background-color: #007bff;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .share-button:hover {
            background-color: #0056b3;
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
                <!-- 共有ボタン -->
                <button class="share-button" onclick="shareSound('<?php echo $sound['filesid']; ?>')">Share</button>
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

        function shareSound(filesid) {
            // クッキーにfilesidを保存（セッションのみ有効）
            document.cookie = "filesid=" + filesid + "; path=/";
            // share.phpにリダイレクト
            window.location.href = "share.php";
        }
    </script>
  <?php include 'footer.php'; ?>
</body>
</html>
