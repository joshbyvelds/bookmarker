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

        session_start();
        $user_id = (int)$_SESSION['user_id'];

        // Private Passcode storage..
        $passcode = 0;

        // Default Stats Values
        date_default_timezone_set('America/Toronto');
        $date = date("Y-m-d H:i:s");
        $visits = 0;


        $stmt = $db->prepare("INSERT INTO bookmarks (user, title, url, type, passcode, visits, lastvisit) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $title);
        $stmt->bindParam(3, $url);
        $stmt->bindParam(4, $bookmark_type);
        $stmt->bindParam(5, $passcode);
        $stmt->bindParam(6, $visits);
        $stmt->bindParam(7, $date);
        $stmt->execute();

        $json['last_id'] = $db->lastInsertId();
        $json['image'] = str_replace(' ', '_', strtolower($title));

        echo json_encode($json);

     // --- ADD FAVORITE --- //

    case("like"):
        if(isset($_POST['id'])){
            $id = "|" . $_POST['id'];
        }

        session_start();
        $user_id = $_SESSION['user_id'];

        // Limit Favorites to 10..
        $result = $db->prepare("SELECT favorites FROM settings WHERE user = ?");
        $result->bindParam(1, $user_id);
        $result->execute();
        $fav = $result->fetchAll(PDO::FETCH_ASSOC)[0]['favorites'];
        $favorites = (strlen($fav) === 0) ? [] : explode("|", $fav);

        if(count($favorites) < 11){
            $stmt = $db->prepare("UPDATE settings SET favorites = CONCAT(COALESCE(`favorites`,''), ?) WHERE user = ?");
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $user_id);
            $stmt->execute();
            $stmt->execute(); // <--- I have no idea why but we need to run this twice in order to get it to save to the db.
        }

        $json['slot'] = count($favorites);
        echo json_encode($json);

    case("unlike"):
        if(isset($_POST['id'])){
            $id = "|" . $_POST['id'];
        }

        session_start();
        $user_id = $_SESSION['user_id'];

        // Limit Favorites to 10..
        $result = $db->prepare("SELECT favorites FROM settings WHERE user = ?");
        $result->bindParam(1, $user_id);
        $result->execute();
        $favorites = explode("|", $result->fetchAll(PDO::FETCH_ASSOC)[0]['favorites']);

        $remove_me = array_search ($_POST['id'],$favorites);
        unset($favorites[$remove_me]);

        $favorites = implode("|", $favorites);

        $stmt = $db->prepare("UPDATE settings SET favorites = ? WHERE user = ?");
        $stmt->bindParam(1, $favorites);
        $stmt->bindParam(2, $user_id);
        $stmt->execute();

        echo json_encode($json);
}
