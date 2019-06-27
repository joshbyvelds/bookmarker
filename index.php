<?php

//Get all of our good Ol' composer stuff
require_once("vendor/autoload.php");

// Setup Twig cuz it's..  you know.. Awesome..
$loader = new \Twig_Loader_Filesystem(__DIR__.'/view');
$twig = new \Twig_Environment($loader);

$title = "Bookmarker! - The Ultimate Bookmark Organizer";

session_start();

// Check if db file exists..
if(!file_exists ( 'php/db.inc.php' )) {
    // If not, load install page..
    echo $twig->render('install.twig', ['title' => 'Install - Bookmarker!']);
    exit();
}

// If Login page..
if(isset($_GET["page"]) && $_GET["page"] === "login"){
    echo $twig->render('login.twig', ['title' => 'Login - Bookmarker!']);
    exit();
}

// If Register page..
if(isset($_GET["page"]) && $_GET["page"] === "register"){
    echo $twig->render('register.twig', ['title' => 'Register - Bookmarker!']);
    exit();
}

// If Logout page..
if(isset($_GET["page"]) && $_GET["page"] === "logout"){
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
}



// If Index Page..
require_once("php/db.inc.php");
$loggedIn = isset($_SESSION['username']);
$username = "";

// Get Grid type..
if(!isset($_GET['grid_type'])){
    $gridtype = "bookmarks";
}else{
    $gridtype = $_GET['grid_type'];
}


// If Grid type is bookmarks
if($gridtype === "bookmarks"){
    if(isset($_GET['category'])){
        if($loggedIn){
            $username = $_SESSION['username'];
            $user_id = $_SESSION['user_id'];
            $result = $db->prepare("SELECT * FROM bookmarks WHERE type = 1 OR user = ? ORDER BY id ASC ");
            $result->bindParam(1, $user_id);
            $result->execute();
            $grid_items = $result->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $result = $db->prepare("SELECT * FROM bookmarks WHERE type = 1 ORDER BY id ASC");
            $result->execute();
            $grid_items = $result->fetchAll(PDO::FETCH_ASSOC);
        }
    }else{
        if($loggedIn){
            $username = $_SESSION['username'];
            $user_id = $_SESSION['user_id'];
            $result = $db->prepare("SELECT * FROM bookmarks WHERE type = 1 OR user = ? ORDER BY id ASC ");
            $result->bindParam(1, $user_id);
            $result->execute();
            $grid_items = $result->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $result = $db->prepare("SELECT * FROM bookmarks WHERE type = 1 ORDER BY id ASC");
            $result->execute();
            $grid_items = $result->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}

// If Grid type is groups
$bookmarks = [];
if($gridtype === "groups"){

    // Get Groups..
    if($loggedIn){
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];
        $result = $db->prepare("SELECT * FROM groups WHERE type = 1 OR user = ? ORDER BY id ASC ");
        $result->bindParam(1, $user_id);
        $result->execute();
        $grid_items = $result->fetchAll(PDO::FETCH_ASSOC);
    }else{
        $result = $db->prepare("SELECT * FROM groups WHERE type = 1 ORDER BY id ASC");
        $result->execute();
        $grid_items = $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Bookmarks for new groups and group editing..
    if($loggedIn){
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];
        $result = $db->prepare("SELECT * FROM bookmarks WHERE type = 1 OR user = ? ORDER BY id ASC ");
        $result->bindParam(1, $user_id);
        $result->execute();
        $bookmarks = $result->fetchAll(PDO::FETCH_ASSOC);
    }else{
        $result = $db->prepare("SELECT * FROM bookmarks WHERE type = 1 ORDER BY id ASC");
        $result->execute();
        $bookmarks = $result->fetchAll(PDO::FETCH_ASSOC);
    }
}

$favorites = [];
if($loggedIn) {
    $user_id = $_SESSION['user_id'];
    $result = $db->prepare("SELECT favorites FROM settings WHERE user = ?");
    $result->bindParam(1, $user_id);
    $result->execute();
    $results = $result->fetchAll(PDO::FETCH_ASSOC);
    if(!empty($results)){
        $fav = $result->fetchAll(PDO::FETCH_ASSOC)[0]['favorites'];
        $fav2 = (strlen($fav) === 0) ? [] : explode("|", $fav);

        if(count($fav2) > 0){
            $in = join(',', array_fill(0, count($fav2), '?'));
            $result2 = $db->prepare("SELECT * FROM bookmarks WHERE id IN ($in)");
            $result2->execute($fav2);
            $favorites = $result2->fetchAll(PDO::FETCH_ASSOC);
        }
    }


}


echo $twig->render('index.twig',
    [
        'title' => $title,
        'loggedin' => $loggedIn,
        'grid_type' => $gridtype,
        'grid_items' => $grid_items,
        'bookmarks' => $bookmarks,
        'username' => $username,
        'favorite_sites' => $favorites,
    ]
);