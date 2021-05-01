<?php
require dirname(__FILE__).'/../../php/functions/functions.php';
?>

<!doctype html>
<html lang="ru">
<head>
    <?php require 'header.html'?>
</head>
<body>
<?php include "drawer.html"?>
<div class="wrapper">
    <?php include "topbar.html"?>
    <div class="content light">
        <?php generateTitlePage($title_id);?>
        <?php include_once 'footer.html'?>
    </div>
</div>
<script type="text/javascript" src="/js/main.js"></script>
</body>
</html>

