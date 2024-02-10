<?php
 // Connects to your Database 
include ('config.php');
$dbc = new PDO("mysql:host=$host;dbname=$db", $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
try {
    $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
ob_start();
require ('header_plain.html');
 //This code runs if the form has been submitted
 if (isset($_POST['submit'])) { 
 
 //This makes sure they did not leave any fields blank
 if (!$_POST['author'] | !$_POST['username'] | !$_POST['pass'] | !$_POST['pass2'] | !$_POST['email'] ) {
 		die('<h3>Oops!</h3> You did not complete all of the required fields. <a href="javascript:history.go(-1)">Undo</a>');
 	}
 // add slashes before apostrophes
 $_POST['author'] = str_replace("'","’", $_POST['author']);
 $author = ucwords(strtolower($_POST['author']));
// checks if the username is in use
 $_POST['username'] = addslashes($_POST['username']);
 $usercheck = $_POST['username'];
 $check = $dbc->prepare("SELECT username FROM users".$year." WHERE username = '$usercheck'");
 $check->execute();
 $check2 = $check->rowCount();
 
 //if the name exists it gives an error
 if ($check2 != 0) {
 		die('<h3>Sorry!</h3>The username '.$_POST['username'].' is already in use. <a href="javascript:history.go(-1)">Undo</a>');
 				}
 // this makes sure both passwords entered match
 	if ($_POST['pass'] != $_POST['pass2']) {
 		die('<h3>Oops!</h3> Your passwords did not match. <a href="javascript:history.go(-1)">Undo</a>');
 	}
 
 	// here we encrypt the password and add slashes if needed
 	$_POST['pass'] = md5($_POST['pass']);
 	$_POST['pass'] = addslashes($_POST['pass']);
 	$email = $_POST['email'];
 // now we insert it into the database
 	$insert = $dbc->prepare("INSERT INTO users".$year." (author, username, password, email) VALUES (:author, :username, :password, :email)");
 	$insert->execute(array(':author'=> $author, ':username'=>$usercheck,':password'=>$_POST['pass'], ':email'=>$email));
	if ($author == $admin) {
	$infotxt = file_get_contents('configtree.txt'); 
	$info = json_decode($infotxt, true);
	$email = array('admin_email' => $email);
	$info2 = array_merge($info, $email);
	$fp = fopen('configtree.txt','w'); 
	fwrite($fp,json_encode($info2));
    }
 	?>
 <h1>Registered</h1>
 <p>Thank you for registering - you may now <a href="login.php">login</a>!</p>

 <?php } 
 else {	 ?>
 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
 <table border="0">
<tr><td colspan="2"><h1>Please register now...</h1></td></tr>
<tr><td style="text-align:right">Your name:</td><td>
 <input type="text" name="author" maxlength="30" placeholder="(First Last)">
 </td></tr>
<tr><td style="text-align:right">Username:</td><td>
 <input type="text" name="username" maxlength="30" placeholder="(no_spaces!)">
 </td></tr>
 <tr><td style="text-align:right">Password:</td><td>
 <input type="password" name="pass" maxlength="20">
 </td></tr>
 <tr><td style="text-align:right">Confirm Password:</td><td>
 <input type="password" name="pass2" maxlength="20">
 </td></tr>
<tr><td style="text-align:right">Email address:</td><td>
 <input type="text" name="email" maxlength="30">
 </td></tr>
 <tr><th colspan=2><input type="submit" name="submit" class="button"
value="Register"></th></tr> </table>
 </form>
 <?php
 }
 $dbc = null;
 require ('footer_plain.html');
 ob_end_flush();
 ?> 