<?php

function OneQuery($sql): ?array {
    require('connection.php');
    $response = $con->query($sql);
    $result = (!empty($response)) ? $response->fetch_assoc() : null;
    $con->close();
    return $result;
}

function AllQuery($sql): ?array {
    require('connection.php');
    $response = $con->query($sql);
    $result = (!empty($response)) ? $response->fetch_all(MYSQLI_ASSOC) : null;
    $con->close();
    return $result;
}

function getNTitles(int $n) {
    $sql = "SELECT title_id, title_name, title_description, title_cover from titles ORDER BY title_id DESC LIMIT ".$n;
    return AllQuery($sql);
}
function getRecentTitles()
{
    $sql = "SELECT title_id, title_name, title_description, title_cover from titles ORDER BY title_id DESC LIMIT 8";
    return AllQuery($sql);
}
function getPopularTitles()
{
    $sql = "SELECT title_id, title_name, title_description, title_cover from titles ORDER BY visit_counter DESC LIMIT 8";
    return AllQuery($sql);
}
function gettitleinfo($id)
{
    $sql = "SELECT * from titles where title_id = ".$id." ";
    return OneQuery($sql);
}
function visitcounter($id)
{
    require('connection.php');
    $sql = "UPDATE titles SET visit_counter = visit_counter + 1 where title_id = ".$id." ";
    $res = $con->query($sql);
}

function getchapterlist($id)
{
    $sql = "SELECT chapter_id, chapter_name from chapters where title_id = " . $id . " ";
    return AllQuery($sql);
}

function getchapter($tid,$cid){
    $sql = "SELECT * from chapters where title_id =".$tid." and chapter_id = ".$cid." ";
    return OneQuery($sql);
}
function chapterchecknext($tid,$cid): ?int{
    $sql = "SELECT chapter_id from chapters where title_id =".$tid." and chapter_id = ".$cid + 1 ." ";
    return !empty(OneQuery($sql)) ? $cid + 1 : null;
}
function chaptercheckprevious($tid,$cid): ?int{
    $sql = "SELECT chapter_id from chapters where title_id =".$tid." and chapter_id = ".$cid - 1 ." ";
    return !empty(OneQuery($sql)) ? $cid - 1 : null;
}
function search(string $query, ?string $genresStr){
    // genres: "genre1,genre2,genre3"
    // Hint: genres can be null, if none were selected
    // Todo: write a query if genres are not null
    $genres = explode(',', $genresStr);
    $g= "";
    foreach ($genres as $genre) {
        $g .= "and genres like '%$genre%' ";
    }
    $sql = "SELECT title_id, title_name, title_description, title_cover from titles where title_name like '%$query%' $g"."ORDER BY title_id DESC LIMIT 10";
    return AllQuery($sql);
}
// registration and login
function registration(){
    $login = $_POST['login'];
    $pass = $_POST['password'];
    $sql = "INSERT INTO accounts(login, password, access_rank) VALUES ('$login','$pass',0);";
    return AllQuery($sql);
}
function sessionstart(){
    session_start();
}
function login(){
    $login = $_POST['login'];
    $pass = $_POST['password'];
    $sql = "SELECT login,access_rank from accounts where login = '$login' and password = '$pass'";
    $res = AllQuery($sql);
    $_SESSION["login"] = $res['login'];
    $_SESSION["logined"] = true;
    $_SESSION["access_rank"] = $res['access_rank'];
}
function sessionquit()
{
    session_destroy();
}
// views
function carousel(string $name, array $data = null):void
{

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
    <div class="card p-1">
        <h1>$name</h1>
        <div class="horizontal-scrollable">
        $cards
        </div>
    </div>
HTML;
    echo $carousel;
}


function generateTitlePage(int $title_id): void {
    $titleInfo = gettitleinfo($title_id);
    if (empty($titleInfo)) {
        print_r("Nothing found. 404!");
        exit(0);
    }
    $chaptersList = getchapterlist($title_id);
    visitcounter($title_id);

    $chapters = null;
    foreach ($chaptersList as $chapter) {
        $chapters .= <<< HTML
        <li><a href="/read/{$titleInfo['title_id']}/{$chapter['chapter_id']}">{$chapter['chapter_name']}</a></li>
HTML;
    }

    $page = <<<HTML
    <div class="container card p-1">
            <div class="flex">
                <div class="title-img">
                    <img src="{$titleInfo['title_cover']}" alt="{$titleInfo['title_jpname']} poster">
                </div>
                <div class="title-info">
                    <div class="title-name">
                        <h1>{$titleInfo['title_name']}</h1>
                    </div>
                    <div class="title-status muted"><p>{$titleInfo['status_translate']}; {$titleInfo['status_publish']}</p></div>
                    <div class="grid">
                        <div>Жанры:</div>
                        <div>{$titleInfo['genres']}</div>
                        <div>Автор:</div>
                        <div>{$titleInfo['author']}</div>
                        <div>Альт. имя:</div>
                        <div>{$titleInfo['title_jpname']}</div>
                        <div>годы выпуска:</div>
                        <div>{$titleInfo['years']}</div>
                        <div>Эту страницы посетили</div>
                        <div>{$titleInfo['visit_counter']} раз(-а)</div>
                    </div>
                </div>
            </div>
            <div>
                <h2>Аннотация</h2>
                <p>{$titleInfo['title_description']}</p>
            </div>
            <div>
                <h2>Chapters</h2>
                <div class="vertical-scrollable card">
                    <ol class="chapters-list">
                        {$chapters}
                    </ol>
                </div>
            </div>
        </div>
HTML;

    echo $page;
}
?>
