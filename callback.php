<?php
if (isset($_GET['session'])) {
    $session = $_GET['session'];
    $host = $_SERVER['HTTP_REFERER'];
    $url = $host."api/miauth/".$session."/check";
    
    function getApi($url, $params) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);

        $res = curl_exec($curl);
        $arr = json_decode($res, true);
        curl_close($curl);
        return $arr;
    }

    $arr = getApi($url=$url, $params=array());
    if ($arr["ok"]) {
        if (isset($arr['token'])) {
            setcookie('token', $arr['token'], time()+60*60*24*7);
            //echo $_COOKIE['token']."<br>";
            echo $arr['user']['name']."にログインしました。<br>3秒後にトップページに戻ります。";
            echo '<meta http-equiv="Refresh" content="3; url=index.php">';
         }
    } else {
        header('Location: login.php');
    }
} else {
    header('Location: login.php');
}
?>