<?php
require dirname(__FILE__).'/../../php/functions/functions.php';
?>

<!doctype html>
<html lang="ru">
<head>
    <?php include "header.html"; ?>
</head>
<body>
<div class="drawer">Drawer</div>
<div class="wrapper">
    <div class="topbar"> Top bar</div>
    <div class="content">
        <div class="container">
        <?php carousel("Популярное", gettitlesformainpopular()); ?>

        <?php carousel("Недавнее", gettitlesformainrecent()); ?>
        </div>
        <?php include_once 'footer.html'?>
    </div>
</div>
<script type="text/javascript" src="/js/main.js"></script>
</body>
</html>