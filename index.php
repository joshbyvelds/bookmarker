<?php

//Get all of our good Ol' composer stuff
require_once("vendor/autoload.php");

// Setup Twig cuz it's..  you know.. Awesome..
$loader = new \Twig_Loader_Filesystem(__DIR__.'/view');
$twig = new \Twig_Environment($loader);

$title = "Bookmarker! - The Ultimate Bookmark Organizer";

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


session_start();
// If Index Page..
$loggedIn = isset($_SESSION['username']);


var_dump($loggedIn);
//TODO:: Get user config if logged in else load public/guest bookmarks

//TODO:: setup bookmarks

echo $twig->render('index.twig',
    [
        'title' => $title,
        'loggedin' => $loggedIn
    ]
);