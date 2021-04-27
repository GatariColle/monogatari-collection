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
    $sql = "SELECT title_id, title_name, title_description, title_cover from titles ORDER BY visit_counter DESC LIMIT 8";
    $res = $con->query($sql);
    $cards = $res->fetch_all(MYSQLI_ASSOC);
    $con->close();
    return $cards;
}
function gettitleinfo($id)
{
    require('connection.php');
    $sql = "SELECT * from titles where title_id = ".$id." ";
    $res = $con->query($sql);
    $cards = $res->fetch_all(MYSQLI_ASSOC);
    $con->close();
    return $cards;
}
function visitcounter($id)
{
    require('connection.php');
    $sql = "UPDATE titles SET visit_counter = visit_counter + 1 where title_id = ".$id." ";
    $res = $con->query($sql);
}

function carousel(string $name, array $data = null):void {

    if (empty($data)) {
        echo "Кажется что-то пошло не так при загрузке \"{$name}\".";
        return;
    }
    $cards = null;
    foreach ($data as $poster) {
        $cards .= <<<HTML
        <div class="poster-card">
            <div class="poster-img">
                <img src="{$poster['title_cover']}" alt="{$poster['title_name']} poster">
            </div>
            <div class="poster-annotation">
                <div class="container">
                    <div class="center"><b>{$poster['title_name']}</b></div>
                    <div><p>{$poster['title_description']}</p></div>
                </div>
            </div>
            <a href="/read/{$poster['title_id']}"></a>
            <!--<a href="/read/{$poster['title_id']}"></a>-->
            <!--<a href="/read?title={$poster['title_id']}"></a>-->
        </div>
HTML;

    }

    $carousel = <<<HTML
    <div class="card">
        <h1>$name</h1>
        <div class="horizontal-scrollable">
        $cards
        </div>
    </div>
HTML;
    echo $carousel;
}
?>
