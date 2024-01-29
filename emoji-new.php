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
            mkdir($folderPath);
        }

        // Loop through each uploaded file
        for ($i = 0; $i < $fileCount; $i++) {
            $tmpName = $imageFiles['tmp_name'][$i];
            $fileName = $imageFiles['name'][$i];
            $imageDescription = $imageDescriptions[$i] ?? ''; // Modified

            // Move uploaded file to the folder with the same name as folder name
            move_uploaded_file($tmpName, $folderPath . '/' . $fileName);

            // Add entry to emoji.json with folder path as image path
            if ($i === 0) {
                $emojiData = [
                    'image_path' => 'https://portal.joinrosekey.org/' . $folderPath . '/',
                    'name' => $folderName,
                    'description' => $imageDescription,
                ];
                $emojiJson[] = $emojiData;
            }
        }
    }

    // If no validation errors, set $validationPassed to true
    if (empty($validationErrors)) {
        $validationPassed = true;
    }

    // If everything is valid, update emoji.json
    if ($validationPassed) {
        // Write updated emoji.json
        file_put_contents('emoji.json', json_encode($emojiJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        // Display success message
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
        <label for="folder_name">フォルダの名前 (英語のみ)</label> <!-- Added -->
        <input type="text" name="folder_name" required><br> <!-- Added -->

        <label for="image_file">画像ファイル (30MBまでのPNG, JPG, JPEG、最大30枚)</label>
        <input type="file" name="image_file[]" accept=".png, .jpg, .jpeg" multiple required><br>

        <label for="image_description[]">説明</label>
        <textarea name="image_description[]" required></textarea><br>

        <input type="submit" value="申請する">
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>
