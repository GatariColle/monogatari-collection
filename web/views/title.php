<?php
require dirname(__FILE__).'/../../php/functions/functions.php';

$titleInfo = gettitleinfo($title_id);
if (empty($titleInfo)) {
    print_r("Nothing found. 404!");
    exit(0);
}
$chaptersList = array();
//$chaptersList = getchapterlist($title_id);
// TODO: don't forget to put visit counter increasing function

?>

<!doctype html>
<html lang="ru">
<head>
    <?php require 'header.html'?>
</head>
<body>
<div class="drawer">Drawer</div>
<div class="wrapper">
    <div class="topbar"> Top bar</div>
    <div class="content">
        <?php generateTitlePage($titleInfo, $chaptersList);?>
        <?php include_once 'footer.html'?>
    </div>
</div>
<script type="text/javascript" src="/js/main.js"></script>
</body>
</html>

