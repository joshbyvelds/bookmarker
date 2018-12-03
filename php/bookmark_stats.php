<?php

require_once("db.inc.php");

$json = [];
$json['error'] = false;

session_start();


if($json['error']){
    echo json_encode($json);
    exit();
}

// Get total visits

$bookmark_stats = $db->prepare("SELECT visits,lastvist FROM Bookmarks WHERE id = ?;");
$bookmark_stats->bindParam(1, $bookmark_id);
$bookmark_stats->execute();
$bookmark_stats = $bookmark_stats->fetchAll(PDO::FETCH_ASSOC)[0];


// Get Data for chart..
$json['stats'] = $bookmark_stats;

echo json_encode($json);
