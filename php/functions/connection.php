<?php

$dburl = getenv('CLEARDB_DATABASE_URL');

if (empty($dburl))
    throw new Exception('Connection string is empty');

$info = parse_url($dburl);
if ($info['scheme'] != 'mysql')
    throw new Exception('Connection string is invalid');


$con = new mysqli($info['host'], $info['user'], $info['pass'], str_replace('/', '', $info['path']))
or die ('Could not connect to the database server' . mysqli_connect_error());
// $con->query("SET NAMES UTF8");
if (!$con->set_charset('utf8mb4')) {
    printf("Exception when setting utf8mb4 charset: %s\n", $con->error);
    exit;
}
?>