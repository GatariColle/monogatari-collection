<?php
    require dirname(__FILE__).'/../../php/functions/functions.php';
?>

<!doctype html>
<html lang="ru">
<head>
    <?php require 'header.html'?>
</head>
<body>
    <div class="container">
        <?php $chapter = getchapter($title_id, $chapter_id) ?>
        <h1><?php echo $chapter['chapter_name']?></h1>
        <div>
            <?php echo $chapter['chapter_text']?>
        </div>
    </div>
</body>
</html>
