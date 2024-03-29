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
        /* 既存のスタイルを保持 */
        body {
            margin: 0;
            padding: 0;
            background-color: white;
            text-align: center;
            overflow-x: hidden; /* 横スクロールを禁止 */
            overflow-y: auto; /* 縦スクロールのみを許可 */
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* ビューポートの高さいっぱいに表示 */
        }

        h2 {
            margin-top: 20px; /* 元の50pxから変更 */
            font-size: 2em;
        }

        h3 {
            margin-top: 10px; /* 元の20pxから変更 */
            font-size: 1.5em;
            color: #555;
        }

        /* 新しいスタイルを追加 */
        .soundboard-container {
            flex-grow: 1; /* 残りの空間を全て占有 */
            padding: 40px;
            overflow-y: auto; /* 縦スクロールが必要な場合にスクロールバーを表示 */
        }

        .soundboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 70px;
            padding-bottom: 70px; /* カードの下にフッターが貫通しないように余白を追加 */
        }

        .sound-card {
            width: calc(33.33% - 20px); /* カードの幅を調整 */
            max-width: 200px; /* 最大幅を設定 */
            margin-bottom: 20px; /* カードの下に余白を追加 */
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

        /* フッターのスタイル */
        .footer {
            padding: 20px;
            background-color: #333;
            color: #fff;
            text-align: center;
            width: 100%;
            position: fixed; /* 画面下部に固定 */
            bottom: 0; /* 画面下部に固定 */
        }

        /* ポップアップスタイル */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .popup button {
            margin-top: 10px;
            padding: 5px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .popup button:hover {
            background-color: #0056b3;
        }
    </style>

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
            <button class="share-button" onclick="showPopup('<?php echo $sound['soundurl']; ?>')">Share</button>
        </div>
    <?php endforeach; ?>
</div>

<!-- ポップアップ -->
<div class="popup" id="popup">
    <div class="popup-content">
        <p>Rosekeyサーバーにこの音声を共有したい場合は以下のように行ってください。</p>
        <p>ノート作成(投稿時)にファイル添付ボタンを押して、URLから を選択し以下のURLを指定してください</p>
        <p><span id="soundUrl"></span></p>
        <button onclick="closePopup()">OK</button>
    </div>
</div>

<!-- JavaScript -->
<script>
    function playSound(button) {
        var soundUrl = button.getAttribute('data-soundurl');
        var audio = new Audio(soundUrl);
        audio.play();
    }

    function showPopup(soundUrl) {
        document.getElementById('soundUrl').innerText = soundUrl;
        document.getElementById('popup').style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }
</script>
<?php include 'footer.php'; ?>
</body>
</html>
