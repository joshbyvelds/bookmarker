<?php

require_once("vendor/autoload.php");

$loader = new \Twig_Loader_Filesystem(__DIR__.'/view');
$twig = new \Twig_Environment($loader);

// Twig vars go here. :)
echo $twig->render('index.twig',
    [
        'title' => 'Bookmarker!'
    ]
);