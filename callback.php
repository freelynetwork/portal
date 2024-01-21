<?php
if (isset($_GET['session'])) {
    $session = $_GET['session'];
    $host = $_SERVER['HTTP_REFERER'];
    $url = $host . "api/miauth/" . $session . "/check";

    function getApi($url, $params)
    {
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

    $arr = getApi($url = $url, $params = array());
    if ($arr["ok"]) {
        if (isset($arr['token'])) {
            setcookie('token', $arr['token'], time() + 60 * 60 * 24 * 7);
            $userName = $arr['user']['name'];
            echo "<div id='login-message' style='display:none;'>
                    $userName にログインしました。<br>5秒後にトップページに戻ります。
                </div>";
            echo '<meta http-equiv="Refresh" content="5; url=index.php">';
        }
    } else {
        header('Location: login.php');
    }
} else {
    header('Location: login.php');
}
?>
<!-- 追加: CSSとjQuery -->
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    #login-message {
        display: none;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        padding: 20px;
        text-align: center;
        opacity: 0;
        transform: translateY(-20px);
        transition: opacity 1s ease, transform 1s ease;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $("#login-message").fadeIn(1000).css("display", "inline-block").animate({
            opacity: 1,
            transform: "translateY(0)"
        }, 1000);
    });
</script>
