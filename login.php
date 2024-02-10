<?php // Connects to your Database 
include ('config.php');
	// Connect and select.
$dbc = new PDO("mysql:host=$host;dbname=$db", $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
try {
    $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
// Turn on output buffering.
ob_start();
require ('header_plain.html');
//Checks if there is a login cookie 
//if there is, it logs you in and directs you to the members page 

if(isset($_COOKIE['ID_my_site'])) {
 	$username = $_COOKIE['ID_my_site'];
 	$pass = $_COOKIE['Key_my_site'];
 	$check = $dbc->prepare("SELECT * FROM users".$year." WHERE username = '$username'");
	$check->execute();
 	while ($info = $check->fetch(PDO::FETCH_ASSOC)) {
 		if ($pass != $info['password'])
 			{ 
 			}
 		else {
 			header("Location: index.php"); 
			}
 		}
 } 

//if the login form is submitted 

 if (isset($_POST['submit'])) { 

// if form has been submitted
// makes sure they filled it in
 	if(!$_POST['username'] | !$_POST['pass']) {
 		die('You did not fill in a required field.');
 	}
 	// checks it against the database
	$username = $_POST['username'];
	$check = $dbc->prepare("SELECT * FROM users".$year." WHERE username = '$username'")or die();
	$check->execute();
 //Gives error if user dosen't exist
 $check2 = $check->rowCount();
 if ($check2 == 0) {
 		die($username.' does not exist in our database. <a href="register.php">Click Here to Register</a>');
 		}
 while($info = $check->fetch(PDO::FETCH_ASSOC)) 	
 {
 $_POST['pass'] = stripslashes($_POST['pass']);
 	$info['password'] = stripslashes($info['password']);
 	$_POST['pass'] = md5($_POST['pass']);
 
 //gives error if the password is wrong
 	if ($_POST['pass'] != $info['password']) {
 		die('Incorrect password, <a href="login.php">please try again.</a>');
	}
 // if login is ok then we add a cookie
 else if ($admin == $info['author'])
		 {
$_POST['username'] = stripslashes($_POST['username']);
	$hour = time() + 28800;
	setcookie(ID_my_site, $_POST['username'], $hour);
	setcookie(Key_my_site, $_POST['pass'], $hour);
	//then redirect them to the members area
	header("Location: index.php");
		 }
else {
$_POST['username'] = stripslashes($_POST['username']);
	$hour = time() + 3600;
	setcookie(ID_my_site, $_POST['username'], $hour);
	setcookie(Key_my_site, $_POST['pass'], $hour);
	//then redirect them to the members area
	header("Location: index.php");
		 }
	 }
 	$time_query = $dbc->prepare("UPDATE users".$year." SET `login_time` = NOW() WHERE `username` = ?");
	$time_query->execute(array($username)); // Execute the query.
}
 // if they are not logged in
 else {	 ?>
 <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
 <table border="0">
   <tr><td colspan=2><h1>Login</h1></td></tr>
   <tr><td>Username:</td>
    <td> <input type="text" name="username" maxlength="40"> </td></tr>
 <tr><td>Password:</td>
   <td> <input type="password" name="pass" maxlength="50"> </td></tr> <tr><td colspan="2" align="right"> <input type="submit" name="submit" value="Login"> </td></tr> </table> </form> 
<?php
 } 
 require ('footer_plain.html');
 ob_end_flush();
?> 