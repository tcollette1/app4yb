<?php
include ('config.php');
	// Connect and select.
$dbc = new PDO("mysql:host=$host;dbname=$db", $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
try {
    $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

//Checks if there is a login cookie 
//if there is, it logs you in and directs you to the story page 

if(isset($_COOKIE['ID_my_site'])) {
 	$username = $_COOKIE['ID_my_site'];
 	$pass = $_COOKIE['Key_my_site'];
 	$check = $dbc->prepare("SELECT * FROM users".$year." WHERE username = '$username'");
	$check->execute();
	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
}	
 else 
 //if the cookie does not exist, they are taken to the login screen 
 {			 
 header("Location: login.php"); 
 } 

?>