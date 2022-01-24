<?php
function insert($sql){
    require ('connection.php');
    if ($con->error) {
        throw new Exception($con->error);
    }
    $con->query($sql);
}
function queryOne($sql): ?array {
    require('connection.php');
    $response = $con->query($sql);
    if ($con->error) {
        throw new Exception($con->error);
    }
    $result = (!empty($response)) ? $response->fetch_assoc() : null;
    $con->close();
    return $result;
}

function queryAll($sql): ?array {
    require('connection.php');
    $response = $con->query($sql);
    if ($con->error) {
        throw new Exception($con->error);
    }
    $result = (!empty($response)) ? $response->fetch_all(MYSQLI_ASSOC) : null;
    $con->close();
    return $result;
}

function getNTitles(int $n) {
    $sql = "SELECT title_id, title_name, title_description, title_cover, title_rank_acceess from titles ORDER BY title_id DESC LIMIT ".$n;
    return queryAll($sql);
}
function getRecentTitles()
{
    $sql = "SELECT title_id, title_name, title_description, title_cover, title_rank_acceess from titles ORDER BY title_id DESC LIMIT 8";
    return queryAll($sql);
}
function getPopularTitles()
{
    $sql = "SELECT title_id, title_name, title_description, title_cover, title_rank_acceess from titles ORDER BY visit_counter DESC LIMIT 8";
    return queryAll($sql);
}
function gettitleinfo($id, $login = '')
{
    $sql = "SELECT titles.*, bookmarks.status from titles left join bookmarks on (bookmarks.title_id = titles.title_id and bookmarks.login = '$login') where titles.title_id = '$id'";
    return queryOne($sql);
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
    return queryAll($sql);
}

function getchapter($tid,$cid){
    $sql = "SELECT * from chapters where title_id =".$tid." and chapter_id = ".$cid." ";
    return queryOne($sql);
}
function chapterchecknext($tid,$cid): ?int{
    $sql = "SELECT chapter_id from chapters where title_id =".$tid." and chapter_id = ".$cid + 1 ." ";
    return !empty(queryOne($sql)) ? $cid + 1 : null;
}
function chaptercheckprevious($tid,$cid): ?int{
    $sql = "SELECT chapter_id from chapters where title_id =".$tid." and chapter_id = ".$cid - 1 ." ";
    return !empty(queryOne($sql)) ? $cid - 1 : null;
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
    $sql = "SELECT title_id, title_name, title_description, title_cover, title_rank_acceess from titles where title_name like '%$query%' $g"."ORDER BY title_id DESC LIMIT 10";
    return queryAll($sql);
}
// registration and login
function registration($login, $pass): bool{
    $sql = "SELECT login from accounts where login ='$login'";
    $res = queryOne($sql);
    if(empty($res)){
        $sql = "INSERT INTO accounts(login, password, access_rank) VALUES ('$login','$pass',1);";
        insert($sql);
        return true;
    }
    else {
        return false;
    }
}

function start_session(){
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function logIn($login, $pass): bool {
    $sql = "SELECT login, access_rank from accounts where login = '$login' and password = '$pass'";
    $res = queryOne($sql);
    if (is_array($res)) {
        session_start();
        $user["login"] = $res['login'];
        $user["logged_in"] = true;
        $user["access_rank"] = $res['access_rank'];
        $_SESSION["user"] = $user;
        return true;
    }
    return false;
}
function logOut() {

    session_start();
    unset($_SESSION['user']);
}
//subscription
function subscription($login): bool{

    // Your payment checking function call could be here

    if ($_SESSION["user"]['access_rank'] <2) {
        $sql = "UPDATE accounts SET access_rank = 2 where login = '$login'";
        $_SESSION["user"]['access_rank'] = 2;
        insert($sql);
        return true;
    }
    else{
        return false;
    }
}
function unsubscription($login): bool{
    if ($_SESSION["user"]['access_rank'] == 2) {
        $sql = "UPDATE accounts SET access_rank = 1 where login = '$login'";
        $_SESSION["user"]['access_rank'] = 1;
        insert($sql);
        return true;
    }
    else{
        return false;
    }
}
//bookmarks
function addbookmark($login, $title_id, $status) {
    $sql = "Select * from bookmarks where login = '$login' and title_id ='$title_id'";
    $res = queryOne($sql);
    if(empty($res)){
        $sql = "INSERT INTO bookmarks(login,title_id,status) VALUES ('$login','$title_id','$status');";
        insert($sql);
    }
    else{
        $sql ="UPDATE bookmarks set status = '$status' where login ='$login' and title_id = '$title_id'";
        insert($sql);
    }
}

function deletebookmark($login, $title_id){
    $sql = "delete from bookmarks where login = '$login' and title_id = '$title_id'";
    insert($sql);
}

function showbookmark($login, $status){
    $sql = "SELECT titles.title_id, title_name, title_description, title_cover, title_rank_acceess from titles join bookmarks on bookmarks.title_id = titles.title_id and bookmarks.login = '$login' and bookmarks.status = '$status'";
    return queryAll($sql);
}
//threads
function newpost($thread_id,$text, $author){
    $sql ="insert into threads(thread_id,text,author) values('$thread_id','$text','$author')";
    insert($sql);
}
function getComments($thread_id){
    $sql = "SELECT * FROM THREADS where thread_id = '$thread_id'";
    return queryAll($sql);
}
function getthreadsinfo()
{
    $sql = "SELECT t.title_id, t.title_name, j.comments_no FROM titles AS t LEFT JOIN
(SELECT threads.thread_id, COUNT(threads.comment_id) as comments_no FROM threads GROUP BY threads.thread_id
) as j ON (t.title_id = j.thread_id)";
    return queryAll($sql);
}

function getUser() {
    start_session();
    return $_SESSION['user'] ?? null;
}

function getReferer() {
    return !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
}

?>
