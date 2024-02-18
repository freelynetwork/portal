<?php
// Include header
include 'header.php';

// Check if TOKEN exists in cookie
if (!isset($_COOKIE['token'])) {
    echo "ログインされていません。3秒後に、トップページ(index.php)に戻ります。";
    echo "<meta http-equiv='refresh' content='3;url=index.php'>";
    exit;
}

// Get TOKEN from cookie
$TOKEN = $_COOKIE['token'];

// Initialize replyid variable
$replyid = "";
$fileid = $_COOKIE['filesid'];

// Set replyid if value exists in POST data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply_id'])) {
    $replyid = $_POST['reply_id'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input data
    $text = $_POST['text'];
    $visibility = $_POST['visibility'];

    // Map visibility options
    switch ($visibility) {
        case 'パブリック':
            $visibility = 'public';
            break;
        case 'ホーム':
            $visibility = 'home';
            break;
        case 'フォロワー限定':
            $visibility = 'followers';
            break;
        default:
            $visibility = 'public';
    }

    // Prepare data for POST request
    $data = array(
        "i" => $TOKEN,
        "text" => $text,
        "visibility" => $visibility,
        "localonly" => 'true',
        "fileIds" => $fileid,
    );

    // Add replyId if replyid is not empty
    if (!empty($replyid)) {
        $data['replyId'] = $replyid;
    }

    // URL to Vocaloid.social's notes create endpoint
    $url = "https://vocaloid.social/api/notes/create";

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options for POST request
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    // Execute POST request
    $result = curl_exec($ch);

    // Check for errors and response
    if ($result === false) {
        echo "Error: " . curl_error($ch);
    } else {
        // Check HTTP status code
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200) {
            echo "投稿が完了しました。5秒後にホーム(index.php)に戻ります。";
            echo "<meta http-equiv='refresh' content='5;url=index.php'>";
            exit;
        } else {
            echo "Error: Unexpected HTTP status code - " . $http_code;
        }
    }

    // Close cURL session
    curl_close($ch);
}
?>

<!-- CSS styles -->
<style>
    /* Basic reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Body styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    /* Form container styles */
    form {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Textarea styles */
    textarea {
        width: 100%;
        height: 150px;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: none;
    }

    /* Select styles */
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* Textbox styles */
    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* Submit button styles */
    input[type="submit"] {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        form {
            margin: 30px auto;
            padding: 15px;
        }
    }
</style>

<!-- HTML form -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="reply_id" value="<?php echo htmlspecialchars($replyid); ?>" placeholder="リプライする投稿ID (リプライする場合のみ)">
    <textarea name="text"></textarea><br>
    <select name="visibility">
        <option value="パブリック">パブリック</option>
        <option value="ホーム">ホーム</option>
        <option value="フォロワー限定">フォロワー限定</option>
    </select><br>
    <?php if (!empty($fileid)): ?>
    <div>
        <h1>Music Share Mode</h1>
        <!-- その他のコンテンツを追加 -->
    </div>
    <?php endif; ?>
    <input type="submit" value="投稿">
</form>

<?php
// Include footer
include 'footer.php';
?>