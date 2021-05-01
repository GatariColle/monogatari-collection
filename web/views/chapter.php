<?php
require dirname(__FILE__) . '/../../php/functions/functions.php';
?>

<!doctype html>
<html lang="ru">
<head>
    <?php require 'header.html' ?>
</head>
<body>
<?php include "drawer.html" ?>
<div class="wrapper">
    <?php include "topbar.html"?>
    <div class="content light">
        <div class="container">
            <?php $chapter = getchapter($title_id, $chapter_id) ?>
            <h1><?php echo $chapter['chapter_name'] ?></h1>
            <div>
                <?php echo $chapter['chapter_text'] ?>
            </div>
        </div>
        <?php include 'footer.html'?>
    </div>
</div>
<script type="text/javascript" src="/js/main.js"></script>
</body>
</html>
