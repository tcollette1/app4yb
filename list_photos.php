<h3>Available Photos</h3>
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

	// Retrieve and print every record.
	while ($rows = $title_query->fetch(PDO::FETCH_ASSOC)) {
		$anchor_link = urlencode($rows['title']);
		print "<h5><a href=\"#".$anchor_link."\">{$rows['title']}</a></h5>\n";
	}
?>