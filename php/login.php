<?php

require_once("db.inc.php");

$json = [];

$json['error'] = false;

if(isset($_POST['login_username'])){
    $login_username = $_POST['login_username'];
}

if(isset($_POST['login_password'])){
    $login_password = $_POST['login_password'];
}


if(empty($login_username)){
    $json['error'] = true;
    $json['login_username_error'] = "Please enter the username for admin account.";
}

if(empty($login_password)){
    $json['error'] = true;
    $json['login_password_error'] = "Please create a password for admin account.";
}

if($json['error']){
    echo json_encode($json);
}


//Create Database
$host="localhost";
$root="root";

    try {
        $result = $db->prepare("SELECT username, password FROM users WHERE username = ?");
        $result->bindParam(1, $login_username);
        $result->execute();

        $user = $result->fetchAll(PDO::FETCH_ASSOC);
        if(count($user) === 1){

            if(password_verify($login_password, $user[0]['password'])){
                session_start();
                $_SESSION['username'] = $login_username;
            }else{
                $json['error'] = true;
                $json['login_password_error'] = "username and password do not match. Try again.";
            }

            echo json_encode($json);
        }

    } catch (PDOException $e) {
        die("DB ERROR: ". $e->getMessage());
    }
?>


