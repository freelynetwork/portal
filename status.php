<?php
function checkStatus($url) {
    $headers = get_headers($url);
    
    // Extract the status code from the headers
    if (is_array($headers)) {
        foreach ($headers as $header) {
            if (strpos($header, 'HTTP') === 0) {
                $statusCode = intval(substr($header, 9, 3));
                return $statusCode;
            }
        }
    }
    
    // Default to an error status if no valid status code is found
    return 500;
}

$cards = [
    [
        'title' => 'Rosekey',
        'url' => 'https://rosekey.sbs',
    ],
    // Add more cards as needed
];

include 'header.php';
?>

<div id="card-container">
    <?php foreach ($cards as $card): ?>
        <?php
            $statusCode = checkStatus($card['url']);
            $cardColor = ($statusCode === 200) ? '#8FED8F' : '#FF7F7F';
            $statusText = ($statusCode === 200) ? '200 OK' : 'NG';
        ?>
        <div class="card" style="background-color: <?php echo $cardColor; ?>;">
            <h2><?php echo $card['title']; ?></h2>
            <p><a href="<?php echo $card['url']; ?>" target="_blank"><?php echo $card['url']; ?></a></p>
            <p>ステータスコード: <?php echo $statusText; ?></p>
        </div>
    <?php endforeach; ?>
</div>

<style>
    body {
        margin: 0;
        padding: 0;
    }

    .card {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 15px;
        margin: 10px;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease; /* 追加: transformプロパティ */
    }

    .card:hover {
        background-color: #f5f5f5;
        transform: scale(1.05); /* 追加: ホバー時の拡大アニメーション */
    }
</style>

<script>
    // Add jQuery for card animation
    $(document).ready(function(){
        $(".card").click(function(){
            window.open($(this).find('a').attr('href'), '_blank');
        });
    });
</script>

<?php include 'footer.php'; ?>
