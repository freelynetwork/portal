<?php
include 'config.php'; // config.phpを読み込む

if (isset($_COOKIE['token'])) {
    echo "既にログインされています。<br>3秒後にトップページに戻ります。";
    echo '<meta http-equiv="Refresh" content="3; url=index.php">';
} else {
    echo "どこにログインしますか？";
    echo '<form method="post" id="urlForm">
            <input type="text" name="instance_url" placeholder="https://" required>
            <input type="submit" value="送信">
          </form>';

    if (isset($_POST['instance_url'])) {
        $instance = $_POST['instance_url'];

        // Check if URL starts with https://
        if (!preg_match('/^https:\/\/.*/', $instance)) {
            echo '<script>alert("URLはhttps://から始まる必要があります。");</script>';
            exit;
        }

        $nodeinfo_url = $instance . "/nodeinfo/2.0";

        // Access the specified URL
        $json = @file_get_contents($nodeinfo_url);

        if (!$json) {
            echo '<script>alert("指定されたURLにアクセスできませんでした。");</script>';
            exit;
        }

        $data = json_decode($json, true);

        // Check if the software name is "rosekey"
        if (isset($data['software']['name']) && strtolower($data['software']['name']) !== 'rosekey') {
            echo '<script>alert("このサーバーはRosekeyを採用していないのでログインできません。");</script>';
            exit;
        }

        $appname = "Rosekey Portal";
        $callback = "portal.joinrosekey.org";
        $permissions = "read:account";

        function guidv4($data = null) {
            $data = $data ?? random_bytes(16);
            assert(strlen($data) == 16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        $uuid = guidv4();
        $url = "https://" . $instance . "/miauth/" . $uuid . "?name=" . $appname . "&callback=https://" . $callback . "/callback.php&permission=" . $permissions;
        // MiAuthのURLにリダイレクト
        header('Location: ' . $url . '');
    }
}
?>
