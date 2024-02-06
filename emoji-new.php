<?php
// Check if the user is logged in
if (!isset($_COOKIE['token'])) {
    echo "ログインされていません。<br>3秒後にホームに戻ります。";
    echo '<meta http-equiv="Refresh" content="3; url=index.php">';
    exit;
}

// Initialize the variable
$validationPassed = false;
$validationErrors = [];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process form data
    $folderName = $_POST['folder_name'] ?? '';
    $imageFiles = $_FILES['image_file'] ?? null;
    $imageDescriptions = $_POST['image_description'] ?? [];

    // Validate folder name
    if (empty($folderName)) {
        $validationErrors[] = 'フォルダの名前を入力してください。';
    }

    // Validate image files
    if (!isset($imageFiles) || !is_array($imageFiles['tmp_name'])) {
        $validationErrors[] = '画像ファイルを選択してください。';
    } else {
        $fileCount = count($imageFiles['tmp_name']);
        if ($fileCount > 30) {
            $validationErrors[] = '最大30枚の画像ファイルを選択してください。';
        }

        // Create folder based on folder name if it doesn't exist
        $folderPath = 'emoji/' . $folderName;
        if (!is_dir($folderPath)) {
            if (!mkdir($folderPath, 0777, true)) {
                // Failed to create directory
                $validationErrors[] = 'フォルダを作成できませんでした。';
            }
        }

        // SQLite3データベースファイル名
        $dbFile = 'emoji.db';

        // SQLite3データベースに接続、または作成
        $db = new SQLite3($dbFile);

        // テーブルが存在しない場合は作成
        $db->exec("CREATE TABLE IF NOT EXISTS emojis (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    image_path TEXT,
                    name TEXT,
                    description TEXT
                  )");

        // Loop through each uploaded file
        for ($i = 0; $i < $fileCount; $i++) {
            $tmpName = $imageFiles['tmp_name'][$i];
            $fileName = $imageFiles['name'][$i];
            $imageDescription = $imageDescriptions[$i] ?? '';

            // Move uploaded file to the folder with the same name as folder name
            $imagePath = $folderPath . '/' . $fileName;
            if (move_uploaded_file($tmpName, $imagePath)) {
                // Add entry to emojis table
                $stmt = $db->prepare("INSERT INTO emojis (image_path, name, description) VALUES (:image_path, :name, :description)");
                $stmt->bindValue(':image_path', 'https://portal.joinrosekey.org/' . $imagePath);
                $stmt->bindValue(':name', $folderName);
                $stmt->bindValue(':description', $imageDescription);
                $stmt->execute();
                $stmt->close();
            } else {
                // If failed to move file, add an error message
                $validationErrors[] = '画像ファイルのアップロードに失敗しました。';
            }
        }

        // データベース接続をクローズ
        $db->close();
    }

    // If no validation errors, set $validationPassed to true
    if (empty($validationErrors)) {
        $validationPassed = true;
    }

    // If everything is valid, update emoji.json
    if ($validationPassed) {
        // Display success message
        echo "絵文字の申請が完了しました。<br>3秒後にホームに戻ります。<br>";
        echo "アップロードされた画像:<br>";
        foreach ($imageFiles['name'] as $fileName) {
            echo $fileName . "<br>";
        }
        echo '<meta http-equiv="Refresh" content="3; url=index.php">';
        exit;
    } else {
        // Display error messages
        echo "エラーが発生しました。入力内容を確認してください。<br>";
        echo "エラーメッセージ:<br>";
        foreach ($validationErrors as $error) {
            echo $error . "<br>";
        }
        echo "アップロードされた画像:<br>";
        foreach ($imageFiles['name'] as $fileName) {
            echo $fileName . "<br>";
        }
        // Additional error handling and message display can be added here
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosekey Portal - 絵文字申請</title>
    <style>
        /* Your modern CSS styles here */
        /* Example: */
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="file"],
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <!-- Emoji Submission Form -->
    <form action="emoji-new.php" method="post" enctype="multipart/form-data">
        <label for="folder_name">フォルダの名前 (英語のみ)</label> <!-- Added -->
        <input type="text" name="folder_name" required><br> <!-- Added -->

        <label for="image_file">画像ファイル (30MBまでのPNG, JPG, JPEG、最大30枚)</label>
        <input type="file" name="image_file[]" accept=".png, .jpg, .jpeg, .gif, .webp" multiple required><br>

        <label for="image_description[]">説明</label>
        <textarea name="image_description[]" required></textarea><br>

        <input type="submit" value="申請する">
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>
