<h3>Available Stories</h3>
Search by author:<br />
<form action="view_entry_author.php" method="POST">
<select name="writer">
<option>Choose author</option>
<?php 

$author_query = $dbc->prepare("SELECT DISTINCT author FROM yb_copy".$year." ORDER BY author ASC");
$author_query->execute();
	while ($myrows = $author_query->fetch(PDO::FETCH_ASSOC)) {
		$author = stripslashes($myrows['author']);
		echo "<option name=\"writer\" value=\"".$myrows['author']."\">".$author."</option>";
	}
?>
</select>
<input type="submit" name="submit" value="Go" />
</form>
<a href="view_entry.php">Return to all entries</a>
<?php
$title_query = $dbc->prepare("SELECT * FROM yb_copy".$year." ORDER BY date_entered DESC");
 // Run the query.
$title_query->execute();

	// Retrieve and print every record.
	while ($rows = $title_query->fetch(PDO::FETCH_ASSOC)) {
		$anchor_link = urlencode($rows['title']);
		print "<h5><a href=\"#".$anchor_link."\">{$rows['title']}</a></h5>\n";
	}

?>