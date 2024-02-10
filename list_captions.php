<h3>Available Captions</h3>
Search by caption writer:<br />
<form action="view_caption_author.php" method="POST">
<select name="writer">
<option>Choose caption writer</option>
<?php 
$author_query = $dbc->prepare("SELECT DISTINCT author FROM captions".$year." ORDER BY author ASC");
$author_query->execute();
	while ($myrows = $author_query->fetch(PDO::FETCH_ASSOC)) {
		$author = stripslashes($myrows['author']);
		print "<option name=\"writer\" value=\"".$myrows['author']."\">".$author."</option>";
	}
?>
</select>
<input type="submit" name="submit" value="Go" />
</form>
<a href="view_entry.php">Return to all entries</a>
<?php
$title_query = $dbc->prepare("SELECT * FROM captions".$year." ORDER BY date_entered DESC");
 // Run the query.
$title_query->execute();

	// Retrieve and print every record.
	while ($rows = $title_query->fetch(PDO::FETCH_ASSOC)) {
		$anchor_link = urlencode($rows['title']);
		echo "<h5><a href=\"#".$anchor_link."\">{$rows['title']}</a></h5>\n";
	}

?>