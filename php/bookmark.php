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

    // ----- VISIT BOOKMARK ----- //

    case("visit"):

        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }

        if($json['error']){
            echo json_encode($json);
            exit();
        }

        date_default_timezone_set('America/Toronto');
        $date = date("Y-m-d H:i:s");

        $stmt = $db->prepare("UPDATE bookmarks SET visits = visits + 1, lastvisit = ? WHERE id = ?");
        $stmt->bindParam(1, $date);
        $stmt->bindParam(2, $id);
        $stmt->execute();


    // ----- NEW BOOKMARK ----- //

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

        // Default Stats Values
        date_default_timezone_set('America/Toronto');
        $date = date("Y-m-d H:i:s");
        $visits = 0;

        $stmt = $db->prepare("INSERT INTO bookmarks (title, url, type, passcode, visits, lastvisit) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $title);
        $stmt->bindParam(2, $url);
        $stmt->bindParam(3, $bookmark_type);
        $stmt->bindParam(4, $passcode);
        $stmt->bindParam(5, $visits);
        $stmt->bindParam(6, $date);
        $stmt->execute();

        $json['last_id'] = $db->lastInsertId();
        $json['image'] = str_replace(' ', '_', strtolower($title));

        echo json_encode($json);

}
