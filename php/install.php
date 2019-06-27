<?php

$json = [];

$json['error'] = false;

if(isset($_POST['sql_root_username'])){
    $root_username = $_POST['sql_root_username'];
}

if(isset($_POST['sql_root_password'])){
    $root_password = $_POST['sql_root_password'];
}

if(isset($_POST['sql_user'])){
    $sql_user = $_POST['sql_user'];
}

if(isset($_POST['sql_password'])){
    $sql_password = $_POST['sql_password'];
}

if(isset($_POST['sql_database'])){
    $sql_database = $_POST['sql_database'];
}

if(isset($_POST['admin_username'])){
    $admin_username = $_POST['admin_username'];
}

if(isset($_POST['admin_password'])){
    $admin_password = $_POST['admin_password'];
}

if(empty($root_username)){
    $json['error'] = true;
    $json['sql_root_error'] = "Please the username for Root.";
}

if(empty($sql_user)){
    $json['error'] = true;
    $json['sql_user_error'] = "Please the username for SQL connection.";
}

if(empty($sql_password)){
    $json['error'] = true;
    $json['sql_password_error'] = "Please the password for SQL connection.";
}

if(empty($sql_database)){
    $json['error'] = true;
    $json['sql_database_error'] = "Please the name of the database app will use.";
}

if(empty($admin_username)){
    $json['error'] = true;
    $json['admin_username_error'] = "Please enter the username for admin account.";
}

if(empty($admin_password)){
    $json['error'] = true;
    $json['admin_password_error'] = "Please create a password for admin account.";
}

if($json['error']){
    echo json_encode($json);
}


//Create Database
$host="localhost";
$root = $root_username;

$user = $sql_user;
$pass = $sql_password;
$db = $sql_database;
$admin_role = 2;
$admin_password = password_hash($admin_password, PASSWORD_DEFAULT);

    try {
        // Create Database with local mysql user..
        $dbh = new PDO("mysql:host=$host", $root, $root_password);
        $dbh->exec("CREATE DATABASE `$db`;
                CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
                GRANT ALL ON `$db`.* TO '$user'@'localhost';
                FLUSH PRIVILEGES;")
        or die(print_r($dbh->errorInfo(), true));
        $dbh = null;

        //create db.inc.php file for later connections
        $dbfile = fopen("db.inc.php", "w");
        $txt = "<?php\n";
        $txt .= "try {\n";
        $txt .= "    \$db = new PDO('mysql:host=localhost;dbname=$sql_database', \"$sql_user\", \"$sql_password\");\n";
        $txt .= "    \$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling\n";
        $txt .= " } catch (PDOException \$e) {\n";
        $txt .= "    print \"Error!: \" . \$e->getMessage() . \"<br/>\";\n";
        $txt .= "    die();\n";
        $txt .= "}\n";
        $txt .= "?>\n";
        fwrite($dbfile, $txt);
        fclose($dbfile);

        // Reconnect with new user..
        $db = new PDO("mysql:dbname=$sql_database;host=localhost", $user, $pass);
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling

        // Create Tables..

        // Users..

        $sql ="CREATE table users(
             id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
             username VARCHAR( 50 ) NOT NULL,
             password VARCHAR( 250 ) NOT NULL,
             role INT( 2 ) NOT NULL);" ;
        $db->exec($sql);

        // Bookmarks..

        $sql ="CREATE table bookmarks(
             id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
             user INT( 11 ) NOT NULL,
             title VARCHAR( 50 ) NOT NULL,
             url VARCHAR( 250 ) NOT NULL,
             type INT( 2 ) NOT NULL,
             passcode VARCHAR( 250 ) NOT NULL,
             visits INT( 7 ) NOT NULL,
             lastvisit TIMESTAMP NOT NULL);" ;
        $db->exec($sql);

        // Categories.

        // Groups..
        $sql ="CREATE table bookmark_groups(
             id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
             user INT( 11 ) NOT NULL,
             title VARCHAR( 50 ) NOT NULL,
             bookmarks VARCHAR( 500 ) NOT NULL,
             type INT( 2 ) NOT NULL,
             used INT( 7 ) NOT NULL,
             lastused TIMESTAMP NOT NULL);" ;
        $db->exec($sql);

        // Settings..
        $sql ="CREATE table settings(
             user INT( 11 ) PRIMARY KEY,
             favorites VARCHAR( 150 ));" ;
        $db->exec($sql);

        // Admin..


        // Add Admin to users, settings and Admin Tables..
        $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $admin_username);
        $stmt->bindParam(2, $admin_password);
        $stmt->bindParam(3, $admin_role);
        $stmt->execute();

        $user_id = 1;
        $fav = "";
        $stmt = $db->prepare("INSERT INTO settings (user, favorites) VALUES (?, ?)");
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(1, $fav);
        $stmt->execute();

        session_start();
        $_SESSION['username'] = $admin_username;

        echo json_encode($json);

    } catch (PDOException $e) {
        die("DB ERROR: ". $e->getMessage());
    }
?>


