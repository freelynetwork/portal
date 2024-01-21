<?php
if (isset($_COOKIE['token'])) {
    setcookie("token", "", time()-60*60*24*7);
    echo "<div id='logout-message' class='centered-message' style='display:none;'>
            ログアウトしました。<br>5秒後にトップページに戻ります。
          </div>";
    echo '<meta http-equiv="Refresh" content="5; url=index.php">';
} else {
    echo "<div id='logout-message' class='centered-message' style='display:none;'>
            ログインされていません。<br>5秒後にトップページに戻ります。
          </div>";
    echo '<meta http-equiv="Refresh" content="5; url=index.php">';
}
?>
<!-- 追加: CSSとjQuery -->
<style>
    .centered-message {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    #logout-message, #see-you-message {
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
        $("#logout-message").fadeIn(1000).css("display", "inline-block").animate({
            opacity: 1,
            transform: "translateY(0)"
        }, 1000, function() {
            $("#see-you-message").fadeIn(1000).css("display", "inline-block").animate({
                opacity: 1,
                transform: "translateY(0)"
            }, 1000);
        });
    });
</script>
