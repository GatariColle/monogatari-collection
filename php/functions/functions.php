<?php
function gettitlesformainrecent()
{
    require('connection.php');
    $sql = "SELECT title_id, title_name, title_description, title_cover from titles ORDER BY title_id DESC LIMIT 8";
    $res = $con->query($sql);
    $cards = $res->fetch_all(MYSQLI_ASSOC);
    $con->close();
    return $cards;
}
function gettitlesformainpopular()
{
    require('connection.php');
    $sql = "SELECT title_id, title_name, title_description, title_cover from titles ORDER BY visit_count DESC LIMIT 8";
    $res = $con->query($sql);
    $cards = $res->fetch_all(MYSQLI_ASSOC);
    $con->close();
}
?>