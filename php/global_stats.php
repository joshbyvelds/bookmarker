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

$total_visits = $db->prepare("SELECT SUM(visits) FROM Bookmarks;");
$total_visits->execute();
$total_visits = $total_visits->fetchAll(PDO::FETCH_ASSOC)[0]['SUM(visits)'];

// Get last Bookmark Visited..
$last_bookmark = $db->prepare("SELECT title,lastvisit  FROM Bookmarks ORDER BY lastvisit DESC LIMIT 1");
$last_bookmark->execute();
$last_bookmark = $last_bookmark->fetchAll(PDO::FETCH_ASSOC)[0];
$last_bookmark['lastvisit'] = date('l, F j, Y: g:i:s A', strtotime($last_bookmark['lastvisit']));

// Get User Bookmarks
$user_bookmarks = $db->prepare("SELECT COUNT(ID) FROM Bookmarks WHERE user = ?;");
$user_bookmarks->bindParam(1, $_SESSION['user_id']);
$user_bookmarks->execute();
$user_bookmarks = $user_bookmarks->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(ID)'];

// Get Top 10 Bookmarks
$topten = $db->prepare("SELECT title,visits FROM Bookmarks ORDER BY visits DESC LIMIT 10");
$topten->execute();
$topten = $topten->fetchAll(PDO::FETCH_ASSOC);

$json['total_visits'] = $total_visits;
$json['last_bookmark'] = $last_bookmark;
$json['user_bookmarks'] = $user_bookmarks;
$json['topten'] = $topten;

echo json_encode($json);
