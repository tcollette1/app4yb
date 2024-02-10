<?php
    // Address error handling.
    ob_start();
    require ('header_plain.html');
    include ('config.php');
    $year = idate("Y");
    $year = ($year+1);
    $nuyear = ($year-1);
    if (isset($_POST["submit"])) {
        if ($_POST['newyear'] != "Change year") {
            $year = $_POST['newyear'];
        }
        else {
            $_POST['newyear'] == $year;
        }
        try {
            $dbc = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $table1 = $db.".users".$year;
            $sql1 = "CREATE TABLE IF NOT EXISTS $table1(
            `id` INT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
            `author` VARCHAR(30) NOT NULL,
            `username` VARCHAR(30) NOT NULL,
            `password` VARCHAR(64) NOT NULL,
            `login_time` DATETIME NOT NULL,
            `email` VARCHAR(35) NOT NULL,
            PRIMARY KEY(`id`)
            ) DEFAULT CHARSET = utf8;";
            $dbc->exec($sql1);
            $table2 = $db.".section_ladder".$year;
            $sql2 = "CREATE TABLE IF NOT EXISTS $table2(
            `id` INT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
            `page_number` INT(3) NOT NULL,
            `section_name` VARCHAR(30) NOT NULL,
            `page_name` VARCHAR(30) NOT NULL,
            `due_date` DATE NOT NULL,
            `editor` VARCHAR(70) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            PRIMARY KEY(`id`)
            ) DEFAULT CHARSET = utf8;";
            $dbc->exec($sql2);
            $table3 = $db.".copy_ladder".$year;
            $sql3 = "CREATE TABLE IF NOT EXISTS $table3(
            `id` INT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
            `page_number` INT(3) NOT NULL,
            `section_name` VARCHAR(30) NOT NULL,
            `page_name` VARCHAR(30) NOT NULL,
            `due_date` DATE NOT NULL,
            `editor` VARCHAR(70) NOT NULL,
            `writer` VARCHAR(700) NOT NULL,
            `email` VARCHAR(49) NOT NULL,
            PRIMARY KEY(`id`)
            ) DEFAULT CHARSET = utf8;";
            $dbc->exec($sql3);
            $table4 = $db.".photo_ladder".$year;
            $sql4 = "CREATE TABLE IF NOT EXISTS $table4(
            `id` INT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
            `page_number` INT(3) NOT NULL,
            `section_name` VARCHAR(30) NOT NULL,
            `page_name` VARCHAR(50) NOT NULL,
            `due_date` DATE NOT NULL,
            `editor` VARCHAR(70) NOT NULL,
            `photog` VARCHAR(700) NOT NULL,
            `email` VARCHAR(200) NOT NULL,
            PRIMARY KEY(`id`)
            ) DEFAULT CHARSET = utf8;";
            $dbc->exec($sql4);
            $table5 = $db.".captions".$year;
            $sql5 = "CREATE TABLE IF NOT EXISTS $table5 (
            `caption_id` int(10) UNSIGNED NOT NULL,
            `title` varchar(35) CHARACTER SET utf8 NOT NULL,
            `author` varchar(35) CHARACTER SET utf8 NOT NULL,
            `entry` TEXT CHARACTER SET utf8 NOT NULL,
            `date_entered` datetime NOT NULL,
            `date_revised` datetime NOT NULL,
            PRIMARY KEY(`caption_id`)
            ) DEFAULT CHARSET=utf8;";
            $dbc->exec($sql5);
            $table6 = $db.".images".$year;
            $sql6 = "CREATE TABLE IF NOT EXISTS $table6 (
            `photo_id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` varchar(35) CHARACTER SET utf8 NOT NULL,
            `thumbnail` varchar(77) CHARACTER SET utf8 NOT NULL,
            `actual` varchar(67) CHARACTER SET utf8 NOT NULL,
            `photographer` varchar(35) CHARACTER SET utf8 NOT NULL,
            `date_entered` datetime NOT NULL,
            `notes` varchar(210) CHARACTER SET utf8 NOT NULL,
            PRIMARY KEY (`photo_id`)
            ) DEFAULT CHARSET=utf8;";
            $dbc->exec($sql6);
            $table7 = $db.".yb_copy".$year;
            $sql7 = "CREATE TABLE IF NOT EXISTS $table7 (
            `copy_id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` varchar(35) CHARACTER SET utf8 NOT NULL,
            `author` varchar(35) CHARACTER SET utf8 NOT NULL,
            `entry` TEXT CHARACTER SET utf8 NOT NULL,
            `date_entered` datetime NOT NULL,
            `date_revised` datetime NOT NULL,
            PRIMARY KEY (`copy_id`)
            ) DEFAULT CHARSET=utf8;";
            $dbc->exec($sql7);
            echo "<h1>Tables creation successful!</h1> <a href=\"../register.php\">Now you can register</a>, login, and begin using the Yearbook Deadline Application. If you entered your name as an administrator, be sure to enter it the same way in the “Your name” field…";
        }
        catch(PDOException $e)
        {
            echo 'ERROR: ' . $e->getMessage();
        }
        $infotxt = file_get_contents('configtree.txt');
        $info = json_decode($infotxt, true);
        $year = array('year' => $year);
        $info2 = array_merge($info, $year);
        $fp = fopen('configtree.txt','w');
        fwrite($fp,json_encode($info2));
    }
    else {
    ?>
<h1>Create the Database Tables</h1>
Now that you’ve established your database connection, you will next create the database tables that will contain user accounts, page copy, uploaded images information, and your copy, photo and section ladders for the year <?php
    echo $year.".<br />";
    ?>
Use another year: 
<button id="change" class="button">Change Year</button><br />
<script>
$(function() {
  $('select').hide(); 
  $('#change').click(function(){
                     $('select').show("slide", { direction: "up" });
                     $('#change').hide();
                     });
  });
</script>
<form action="create_tables.php" method="post" name="table_maker">
<select name="newyear" id="newyear">
<option>Change year</option>
<?php
    echo "<option>$nuyear</option>";
    echo "<option>".($nuyear+2)."</option>";
    ?>
</select><br />
<input type="submit" name="submit" class="button" value="Create Tables"></form>
Clicking the “Create Tables” button will create the default tables.
<?php }
    $dbc = null;
    require ('footer_plain.html');
    ob_end_flush();
?>
