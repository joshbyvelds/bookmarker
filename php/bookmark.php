<?php

require_once("db.inc.php");

$json = [];
$json['error'] = false;

if(isset($_POST['type'])){
    $submit_type = $_POST['type'];
}else{
    $json['error'] = true;
    $json['general_error'] = "Type of bookmark form missing. <small>Should be a hidden element.</small";
    echo json_encode($json);
    exit();
}

if(empty($submit_type)){
    $json['error'] = true;
    $json['general_error'] = "Type of bookmark form missing. <small>Should be a hidden element.</small>";
}

if($json['error']){
    echo json_encode($json);
    exit();
}

switch($submit_type){
    case("new"):
        if(isset($_POST['title'])){
            $title = $_POST['title'];
        }

        if(isset($_POST['url'])){
            $url = $_POST['url'];
        }

        if(isset($_POST['bookmark_type'])){
            $bookmark_type = $_POST['bookmark_type'];
        }

        if(empty($title)){
            $json['error'] = true;
            $json['title_error'] = "Please enter a title for the bookmark.>";
        }

        if(empty($url)){
            $json['error'] = true;
            $json['url_error'] = "Please enter a url for the bookmark.";
        }

        if(empty($bookmark_type)){
            $json['error'] = true;
            $json['type_error'] = "Please select a bookmark type";
        }

        if($json['error']){
            echo json_encode($json);
            exit();
        }


        $file = '../img/temp/temp_bookmark_image.jpg';
        $newfile = '../img/thumbnails/' . str_replace(' ', '_', strtolower($title)) . '.jpg';

        if(file_exists($file)){
            if (!copy($file, $newfile)) {
                $json['image'] = "failed to copy";
            }else{
                unlink($file);
            }
        }

        $passcode = 0;

        $stmt = $db->prepare("INSERT INTO bookmarks (title, url, type, passcode) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $title);
        $stmt->bindParam(2, $url);
        $stmt->bindParam(3, $bookmark_type);
        $stmt->bindParam(4, $passcode);
        $stmt->execute();

}