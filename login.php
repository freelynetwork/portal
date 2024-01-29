<?php
include 'config.php'; // config.phpを読み込む

    if (isset($_COOKIE['token'])) {
        echo "既にログインされています。<br>3秒後にトップページに戻ります。";
        echo '<meta http-equiv="Refresh" content="3; url=index.php">';
    } else {
        $appname = "Rosekey Portal";
        // インスタンス(仮にまた破壊した時のために変数化笑)
        $instance = $serverurl;
        $callback = "portal.joinrosekey.org";
        // $callback = "localhost:3000";
        // とりあえずアカウント情報の読み取りだけ
        $permissions = "read:account";

        // https://www.uuidgenerator.net/dev-corner/php を参照
        function guidv4($data = null) {
            // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
            $data = $data ?? random_bytes(16);
            assert(strlen($data) == 16);
        
            // Set version to 0100
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
            // Set bits 6-7 to 10
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        
            // Output the 36 character UUID.
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        $uuid = guidv4();
        $url = "https://".$instance."/miauth/".$uuid."?name=".$appname."&callback=https://".$callback."/callback.php&permission=".$permissions;
        // MiAuthのURLにリダイレクト
        header('Location: '.$url.'');
    }
    
?>