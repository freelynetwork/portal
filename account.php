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
    .card-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px; /* 元の20pxから変更 */
        margin-top: 10px; /* 元の20pxから変更 */
        flex-grow: 1; /* 残りの空間を全て占有 */
        padding-bottom: 70px; /* 元の50pxから変更 */
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
        border: 2px solid #333; /* 追加のスタイル：境界線を追加 */
        margin-bottom: 20px; /* 追加のスタイル：下部マージンを追加 */
    }

    .card:hover {
        transform: scale(1.05); /* ホバー時の拡大アニメーション */
    }

    footer {
        position: fixed; /* フッターを画面下部に固定 */
        bottom: 0; /* フッターを画面下部に配置 */
        width: 100%; /* フッターを画面幅いっぱいに広げる */
    }
</style>


<!-- コンテンツ部分 -->
<div class="card-container">
    <div class="card">
        <h2>アカウント情報</h2>
        <h3>名前: <?php echo $user; ?></h3>
        <h3>ユーザー名: <?php echo $username; ?></h3>
        <h3>運営ユーザー: <?php echo ($freelynetwork ? 'Yes' : 'No'); ?></h3>
    </div>
</div>

<?php
// フッターを含む共通のHTML部分をインクルード
include 'footer.php';
?>
