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
        }

        header {
            background-color: #4169E1;
            padding: 10px;
            color: white;
            text-align: center;
            border-bottom: 2px solid white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>

    <!-- Add jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <header>
        <div id="portal-text">Rosekey Portal</div>
        <?php
            if (isset($_COOKIE['token'])) {
                echo '<div id="portal-logout">ログアウト</div>';
            } else {
                echo '<div id="portal-login">ログイン</div>';
            }
            
        ?>
        <!-- 右側は何も表示しない -->
    </header>
    <script>
        // Add jQuery script for the portal text click event
        $(document).ready(function(){
            $("#portal-text").click(function(){
                window.location.href = '/';
            });
            // ログイン
            $("#portal-login").click(function(){
                window.location.href = 'login.php';
            });
            // ログアウト
            $("#portal-logout").click(function(){
                window.location.href = 'logout.php';
            });
        });
    </script>
