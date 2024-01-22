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
    $imageName = $_POST['image_name'] ?? '';
    $imageDescription = $_POST['image_description'] ?? '';
    $imageFile = $_FILES['image_file'] ?? null;

    // Validate image file
    if (!$imageFile || !is_uploaded_file($imageFile['tmp_name'])) {
        $validationErrors[] = '画像ファイルを選択してください。';
    } elseif (!in_array(strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg'])) {
        $validationErrors[] = 'サポートされていないファイル形式です。PNG, JPG, JPEG ファイルを選択してください。';
    } elseif ($imageFile['size'] > 30 * 1024 * 1024) { // 30MB
        $validationErrors[] = 'ファイルサイズが大きすぎます。30MBまでの画像ファイルを選択してください。';
    }

    // Validate image name
    if (empty($imageName)) {
        $validationErrors[] = '絵文字の名前を入力してください。';
    } elseif (!preg_match('/^[a-zA-Z]+$/', $imageName)) {
        $validationErrors[] = '絵文字の名前は英語のみ使用できます。';
    }

    // Validate image description
    if (empty($imageDescription)) {
        $validationErrors[] = '絵文字の説明を入力してください。';
    }

    // If no validation errors, set $validationPassed to true
    if (empty($validationErrors)) {
        $validationPassed = true;
    }

    // If everything is valid, update emoji.json and move the uploaded file
    if ($validationPassed) {
        // Create emoji folder if it doesn't exist
        if (!is_dir('emoji')) {
            mkdir('emoji');
        }

      // Add entry to emoji.json
      $emojiData = [
          'image_path' => 'https://portal.joinrosekey.org/emoji/' . $imageName . '.png', // Adjust file type accordingly
          'name' => $imageName,
          'description' => $imageDescription,
      ];

      $emojiJson = file_exists('emoji.json') ? json_decode(file_get_contents('emoji.json'), true) : [];
      $emojiJson[] = $emojiData;
      file_put_contents('emoji.json', json_encode($emojiJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Move uploaded file to /emoji folder
        move_uploaded_file($imageFile['tmp_name'], 'emoji/' . $imageName . '.png'); // Adjust file type accordingly

        // Display success message
        echo "絵文字の申請が完了しました。<br>3秒後にホームに戻ります。";
        echo '<meta http-equiv="Refresh" content="3; url=index.php">';
        exit;
    } else {
        // Display error messages
        echo "エラーが発生しました。入力内容を確認してください。";
        print_r($validationErrors); // これがエラーメッセージの詳細
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
        <label for="image_file">画像ファイル (30MBまでのPNG, JPG, JPEG)</label>
        <input type="file" name="image_file" accept=".png, .jpg, .jpeg" required><br>

        <label for="image_name">絵文字の名前 (英語のみ)</label>
        <input type="text" name="image_name" required><br>

        <label for="image_description">説明</label>
        <textarea name="image_description" required></textarea><br>

        <input type="submit" value="申請する">
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>
