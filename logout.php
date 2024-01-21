<?php
if (isset($_COOKIE['token'])) {
    setcookie("token", "", time()-60*60*24*7);
    echo "ログアウトしました。<br>3秒後にトップページに戻ります。";
    echo '<meta http-equiv="Refresh" content="3; url=index.php">';
} else {
    echo "ログインされていません。<br>3秒後にトップページに戻ります。";
    echo '<meta http-equiv="Refresh" content="3; url=index.php">';
}
?>