<?php
require dirname(__FILE__).'/../../php/functions/functions.php';
?>

<!doctype html>
<html lang="ru">
<head>
    <?php include "header.html"; ?>
</head>
<body>
<?php include "drawer.html"?>
<div class="wrapper">
    <?php include "topbar.html"?>
    <div class="content light">
        <div class="container gap-1 flex flex-column">
        <?php carousel("Популярное", gettitlesformainpopular()); ?>

        <?php carousel("Недавнее", gettitlesformainrecent()); ?>
        </div>
        <?php include 'footer.html'?>
    </div>
</div>
<script type="text/javascript" src="/js/main.js"></script>
</body>
</html>