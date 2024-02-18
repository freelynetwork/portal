<?php
// Load environment variables from config.php
include 'config.php';
include 'user.php';
$instance = $serverurl;

// Get user from environment variable
if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://" . $instance . "/api/i");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array("i" => $token)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);   

    $res = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

    $arr = json_decode($res, true);
    curl_close($curl);
    // トークンが死んでたらCookie消してリロード
    if ($status == 401) {
        setcookie("token", "", time()-60*60*24*7);
        header('Location: index.php');
    }
    $user = $arr['name'];
} else {
    $user = getenv('USER') !== false ? getenv('USER') : 'Guest';
}

// Check if user is freelynetwork
$freelynetwork = isset($freelynetwork) ? $freelynetwork : false;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosekey Portal</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: white;
            text-align: center;
            overflow-x: hidden; /* 横スクロールを禁止 */
            overflow-y: auto; /* 縦スクロールのみを許可 */
        }

        h2 {
            margin-top: 50px;
            font-size: 2em;
        }

        h4 {
            margin-top: 20px;
            font-size: 1.5em;
            color: #555;
        }

        /* 新しいスタイルを追加 */
        .card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: #8FED8F;
            padding: 20px;
            text-align: center;
            border-radius: 10px; /* カードを四角形にする */
            width: 300px; /* カードの幅を指定 */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* ドロップシャドウを追加 */
            cursor: pointer;
            transition: transform 0.3s ease; /* クリック時のアニメーション用 */
        }

        .card:hover {
            transform: scale(1.05); /* ホバー時の拡大アニメーション */
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <h2>おかえりなさい <?php echo $user; ?> 様</h2>
    <h4>あなたのユーザー名は <?php echo $username; ?> です</h4>

    <!-- カードコンテナを追加 -->
    <div class="card-container">
        <!-- 新しいカード "Status" を追加 -->
        <div class="card" onclick="window.location.href='/status.php';">
            <h2>Status</h2>
            <p>Rosekeyが起動しているかを確認することができます。</p>
        </div>

        <!-- 新しいカード "Emoji Submission" を追加 -->
        <?php if (isset($_COOKIE['token'])) : ?>
            <div class="card" onclick="window.location.href='/emoji-new.php';">
                <h2>絵文字申請機能</h2>
                <p>絵文字を申請することができます。(ログインユーザーのみ)</p>
            </div>
        <?php endif; ?>

        <!-- 新しいカード "Soundboard" を追加 -->
        <?php if (isset($_COOKIE['token'])) : ?>
            <div class="card" onclick="window.location.href='/soundboard.php';">
                <h2>サウンドボード</h2>
                <p>サウンドボードを聞くことができます。(ログインユーザーのみ)</p>
            </div>
        <?php endif; ?>

        <!-- 新しいカード "管理者画面" を追加 -->
        <?php if ($freelynetwork === true) : ?>
            <div class="card" onclick="window.location.href='/admin/index.php';">
                <h2>管理者画面</h2>
                <p>管理者画面にいくことができます【FreelyNetwork所属者のみ】</p>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
