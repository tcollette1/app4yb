<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>
</title>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script>
            $(document).ready(function () {
                var strTitle2 = $('h2').text();
                var strTitle3 = $('h3').text();
                $(document).attr('title', strTitle2 + " — " + strTitle3);
            });
        </script>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
<link href="../app-styles.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="wrap">
<div id="header"><div style="float:left">
  <h2>Yearbook Deadline Application</h2>
  </div>
<hr style="clear:both" />
</div>
<?php
    $host = $_POST["host"];
    $user = $_POST["username"];
    $pass = $_POST["password"];
    $db = $_POST["database2"];
    $admin = $_POST["admin"];
    $domain = $_POST["domain"];
    if ($_POST['timezone'] == "Mountain") {
        $timezone = "America/Denver";
    }
    else if ($_POST['timezone'] == "Mountain (Arizona)") {
        $timezone = "America/Phoenix";
    }
    else if ($_POST['timezone'] == "Pacific") {
        $timezone = "America/Los_Angeles";
    }
    else if ($_POST['timezone'] == "Central") {
        $timezone = "America/Chicago";
    }
    else {
        $timezone = "America/New_York";
    }
    
    if (isset($_POST["submit"])) {
        if (!$_POST['host'] | !$_POST['username'] | !$_POST['password'] | !$_POST['database2'] | !$_POST['admin'] ) {
            die('<h3>Oops!</h3> You did not complete all of the required fields. <a href="javascript:history.go(-1)">Undo</a>');
        }
        try {
            $dbc = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo 'ERROR: ' . $e->getMessage();
        }
        if ($dbc != null) {
            echo "<h3>The database is connected!</h3> Now you will <a href=\"../create_tables.php\">create its tables</a>…";
            $store = array(
                           'host' => $host,
                           'user' => $user,
                           'password' => $pass,
                           'database' => $db,
                           'timezone' => $timezone,
                           'admin' => $admin,
                           'domain' => $domain 
                           ); 
            
            $fp = fopen('../configtree.txt','w'); 
            
            fwrite($fp,json_encode($store));
        }
        else {
            echo "<h3>The database connection was refused.</h3> Please <a href=\"javascript:history.go(-1)\">go back</a>, check your entries and try again…";
        }
    }
    else {
    ?>
  <h3>Create The Database Connection </h3>
  <form action="" method="post" enctype="multipart/form-data" name="database">
<input type="text" name="host" id="host" value="localhost" />
<label for="host">Host Name (usually “localhost”)</label>
<br />
<input type="text" name="username" id="username" /> 
Username:
<label for="username">Either "root" or a username given by the host</label>
<br />
<input type="password" name="password" id="password" />
<label for="password">Password: Enter the password you used when setting up the database</label>
<br />
<input type="text" name="database2" id="database" />
<label for="database">Database Name: Enter the database name you used when setting it up</label>
<br />
<input type="text" name="admin" id="admin" />
<label for="admin">Enter your name as the administrator of the application</label>
<br />
<input type="text" name="domain" id="domain" />
<label for="domain">If installing on a web host, enter your site’s domain name</label>
<br />
<select name="timezone" id="timezone">
<option>Pacific</option>
<option selected="selected">Mountain</option>
<option>Mountain (Arizona)</option>
<option>Central</option>
<option>Eastern</option>
</select>
<label for="timezone">Select your time zone</label><br />
<input name="submit" type="submit" class="button" value="Set Up the Database" />  </form>
<div style="clear:both"></div>
</div>
<?php
$dbc = null;
}
?>
</body>
</html>
