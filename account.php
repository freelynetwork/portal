<?php
include 'user.php';

// ヘッダーを含む共通のHTML部分をインクルード
include 'header.php';
?>

<style>
    /* 既存のスタイルを保持 */
    body {
        margin: 0;
        padding: 0;
        background-color: white;
        text-align: center;
        overflow-x: hidden; /* 横スクロールを禁止 */
        overflow-y: auto; /* 縦スクロールのみを許可 */
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* ビューポートの高さいっぱいに表示 */
    }

    h2 {
        margin-top: 20px; /* 元の50pxから変更 */
        font-size: 2em;
    }

    h3 {
        margin-top: 10px; /* 元の20pxから変更 */
        font-size: 1.5em;
        color: #555;
    }

    /* 新しいスタイルを追加 */
    .account-info-container {
        display: grid;
        place-items: center;
        height: 100%;
        padding: 20px;
    }

    .card {
        padding: 20px;
        text-align: center;
        border-radius: 10px; /* カードを四角形にする */
        width: 100%; /* カードの幅を100%に指定 */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* ドロップシャドウを追加 */
    }

    footer {
        position: fixed; /* フッターを画面下部に固定 */
        bottom: 0; /* フッターを画面下部に配置 */
        width: 100%; /* フッターを画面幅いっぱいに広げる */
    }
</style>



<!-- コンテンツ部分 -->
<div class="account-info-container {">
    <div class="card">
        <h2>アカウント情報</h2>
        <h3>ID: <?php echo $id; ?></h3>
        <h3>名前: <?php echo $name; ?></h3>
        <h3>ユーザー名: <?php echo $username; ?></h3>
        <h3>運営ユーザー: <?php echo ($freelynetwork ? 'Yes' : 'No'); ?></h3>
    </div>
</div>

<?php
// フッターを含む共通のHTML部分をインクルード
include 'footer.php';
?>
