<?php

//Get all of our good Ol' composer stuff
require_once("vendor/autoload.php");

// Setup Twig cuz it's you know Awesome..
$loader = new \Twig_Loader_Filesystem(__DIR__.'/view');
$twig = new \Twig_Environment($loader);

//TODO:: Check if user is logged in..
$loggedIn = false;

//TODO:: Get user config if logged in else load default

//TODO:: setup bookmarks

// Finally lets render our templates..

// Twig vars go here. :)
echo $twig->render('index.twig',
    [
        'title' => 'Bookmarker!'
    ]
);