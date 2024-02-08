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

// Group emojis by folder
$folders = [];
foreach ($emojis as $emoji) {
    $folder_name = $emoji['folder_name'];
    if (!isset($folders[$folder_name])) {
        $folders[$folder_name] = [];
    }
    $folders[$folder_name][] = $emoji;
}

// 対応済みボタンがクリックされた場合の処理
if (isset($_POST['folder_name'])) {
    $folderName = $_POST['folder_name'];
    
    // フォルダを削除する処理
    $folderPath = '/emoji/' . $folderName; // フォルダのパスを設定（適切なパスに置き換えてください）
    if (file_exists($folderPath)) {
        if (is_dir($folderPath)) {
            $files = glob($folderPath . '/*');
            foreach ($files as $file) {
                unlink($file); // ファイルを削除
            }
            rmdir($folderPath); // フォルダを削除
        } else {
            unlink($folderPath); // ファイルを削除
        }
    }
    
    // データベースからのレコード削除
    $statement = $database->prepare('DELETE FROM emoji WHERE folder_name = :folder_name');
    $statement->bindValue(':folder_name', $folderName, SQLITE3_TEXT);
    $statement->execute();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emojiデータ表示</title>
    <!-- CSSスタイル -->
    <style>
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
        .folder {
            margin-bottom: 20px;
        }
        .folder-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .emoji-list {
            display: flex;
            flex-wrap: wrap;
        }
        .emoji-item {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
            margin-bottom: 10px;
            width: calc(33.33% - 20px);
        }
        .emoji-item img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-bottom: 10px;
        }
        .emoji-description {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Emojiデータ</h1>
        <?php foreach ($folders as $folder_name => $folder_emojis): ?>
            <div class="folder">
                <h2 class="folder-name"><?php echo htmlspecialchars($folder_name); ?></h2>
                <!-- 対応済みボタン -->
                <form method="post">
                    <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder_name); ?>">
                    <button type="submit">対応済み</button>
                </form>
                <div class="emoji-list">
                    <?php foreach ($folder_emojis as $emoji): ?>
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
</body>
</html>
