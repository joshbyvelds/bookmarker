<?php

require_once("db.inc.php");

$json = [];
$json['error'] = false;

session_start();

$bookmark_id = $_POST["id"];

if(isset($_POST['id'])){
    $bookmark_id = $_POST["id"];
}else{
    $json['error'] = true;
    $json['general_error'] = "bookmark ID missing.";
    echo json_encode($json);
    exit();
}

if(empty($bookmark_id)){
    $json['error'] = true;
    $json['general_error'] = "Type of bookmark form missing. <small>Should be a hidden element.</small>";
}

if($json['error']){
    echo json_encode($json);
    exit();
}

// Get total visits & last visit.

$bookmark_stats = $db->prepare("SELECT visits,lastvisit FROM Bookmarks WHERE id = ?;");
$bookmark_stats->bindParam(1, $bookmark_id);
$bookmark_stats->execute();
$bookmark_stats = $bookmark_stats->fetchAll(PDO::FETCH_ASSOC)[0];

$bookmark_stats['lastvisit'] = date('l, F j, Y: g:i:s A', strtotime($bookmark_stats['lastvisit']));


// Get Data for chart..
$json['stats'] = $bookmark_stats;

echo json_encode($json);
