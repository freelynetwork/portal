<?php
// Initialize SQLite3 database
$database = new SQLite3('emoji.db');

// Create emoji table if it doesn't exist
$database->exec('CREATE TABLE IF NOT EXISTS emoji (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    folder_name TEXT,
    image_path TEXT,
    description TEXT
)');

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

        // Loop through each uploaded file
        for ($i = 0; $i < $fileCount; $i++) {
            $tmpName = $imageFiles['tmp_name'][$i];
            $fileName = $imageFiles['name'][$i];
            $imageDescription = $imageDescriptions[$i] ?? '';

            // Move uploaded file to the folder with the same name as folder name
            move_uploaded_file($tmpName, $folderPath . '/' . $fileName);

            // Insert entry into emoji table
            $statement = $database->prepare('INSERT INTO emoji (folder_name, image_path, description) VALUES (:folder_name, :image_path, :description)');
            $statement->bindParam(':folder_name', $folderName, SQLITE3_TEXT);
            $statement->bindParam(':image_path', $folderPath . '/' . $fileName, SQLITE3_TEXT);
            $statement->bindParam(':description', $imageDescription, SQLITE3_TEXT);
            $statement->execute();
        }
    }

    // If no validation errors, set $validationPassed to true
    if (empty($validationErrors)) {
        $validationPassed = true;
    }

    // If everything is valid, display success message
    if ($validationPassed) {
        echo "絵文字の申請が完了しました。<br>3秒後にホームに戻ります。";
        echo '<meta http-equiv="Refresh" content="3; url=index.php">';
        exit;
    } else {
        // Display error messages
        echo "エラーが発生しました。入力内容を確認してください。";
        print_r($validationErrors);
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
        <label for="folder_name">フォルダの名前 (英語のみ)</label>
        <input type="text" name="folder_name" required><br>

        <label for="image_file">画像ファイル (30MBまでのPNG, JPG, JPEG、最大30枚)</label>
        <input type="file" name="image_file[]" accept=".png, .jpg, .jpeg, .gif, .webp" multiple required><br>

        <label for="image_description[]">説明</label>
        <textarea name="image_description[]" required></textarea><br>

        <input type="submit" value="申請する">
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>
