<?php 
ob_start();
require ('header.html');

// Connect and select.
require_once('mysql2.php');

$panelCounter = 0; 
echo "<div id=\"title_list\">\n";
?>

Search by photographer:<br />
<form action="view_entry_photographer.php" method="POST"><select name="photographer">
<option>Choose photographer</option>";
<?php 
$list_query = $dbc->prepare("SELECT DISTINCT photographer FROM images".$year." ORDER BY photographer ASC");
$list_query->execute();
	while ($myrows = $list_query->fetch(PDO::FETCH_ASSOC)) {
		$photographer = stripslashes($myrows['photographer']);
		print "<option name=\"photographer\" value=\"".$myrows['photographer']."\">".$photographer."</option>";
	}
 // Run the query.
$title_query = $dbc->prepare("SELECT * FROM images".$year." ORDER BY date_entered DESC");
$title_query->execute();
?>
</select>
<input type="submit" name="submit" value="Go" />
</form>
<a href="view_photos.php">Return to all entries</a>
<?php
echo "</div>\n<div id=\"story_list\" style=\"width:490px\">\n";
// Define the query.
if (isset ($_POST['submit'])) {
 // Run the query.
$photographer = addslashes($_POST['photographer']);
	$list_query = $dbc->prepare("SELECT * FROM images".$year." WHERE photographer = '$photographer' ORDER BY date_entered DESC");
	$list_query->execute();
$photographer = stripslashes($_POST['photographer']);
print "<h3>Photos by ". $photographer."</h3>\n";
	// Retrieve and print every record.
	while ($rows = $list_query->fetch(PDO::FETCH_ASSOC)) {
		$photographer = stripslashes($rows['photographer']);
		$anchor_name = urlencode($rows['title']);
		if ($panelCounter%2==0) {
		echo "<div class=\"evenrow\"><img src=\"{$rows['thumbnail']}\" style=\"float:right\" /><a name=\"".$anchor_name."\"><h5>{$rows['title']}</h5></a><em>$photographer</em><br />{$rows['notes']}<br />{$rows['date_entered']}</div><hr style=\"clear:both\" />\n";
		}
		else {
		echo "<div style=\"padding:2px\"><img src=\"{$rows['thumbnail']}\" style=\"float:left; margin-right:2px\" /><a name=\"".$anchor_name."\"><h5> {$rows['title']}</h5><em>$photographer</em><br />{$rows['notes']}<br />{$rows['date_entered']}</div><hr style=\"clear:both\" />\n";
		}
		$panelCounter++; 
	}

}
$dbc = null; // Close the database connection.
require ('footer.html');
ob_end_flush();
?>