<?php //

// Address error handling.
ob_start();

require ('header.html');
require_once ('mysql2.php');
// Connect and select.
echo "<div id=\"title_list\">\n";
$author_query = $dbc->prepare("SELECT DISTINCT author FROM captions".$year." ORDER BY author ASC");
$author_query->execute();
?> 
Search by caption writer:<br />
<form action="view_caption_author.php" method="POST"><select name="writer">
<option>Choose caption writer</option>
<?php
	while ($myrows = $author_query->fetch(PDO::FETCH_ASSOC)) {
		$author = stripslashes($myrows['author']);
		print "<option name=\"writer\" value=\"".$myrows['author']."\">".$author."</option>";
	}
?> 
</select>
<input type="submit" name="submit" value="Go" />
</form>
<a href="view_captions.php">Return to all entries</a><br /><br />
</div>
<div id="story_list">
<?php
// Define the query.
if (isset ($_POST['submit'])) {
 // Run the query.
$writer = addslashes($_POST['writer']);
	$list_query = $dbc->prepare("SELECT * FROM captions".$year." WHERE author = '$writer'");
	$list_query->execute();
$writer = stripslashes($_POST['writer']);
echo "<h3>Captions by ".$writer."</h3>\n";
	// Retrieve and print every record.
	while ($row = $list_query->fetch(PDO::FETCH_ASSOC)) {
		$author = stripslashes($row['author']);
		$anchor_name = urlencode($row['title']);
		$entry_date = strtotime($row['date_entered']);
		$date_entered = date('l, F j, Y, g:i a', $entry_date);
		$revision_date = strtotime($row['date_revised']);
		$date_revised = date('l, F j, Y, g:i a', $revision_date);
		echo "<div class=\"gradientBox\"><a name=\"".$anchor_name."\"><h4>{$row['title']}</h4></a>\n
		$author<br /><br />\n";
		$count_entry = str_word_count($row['entry']);
		$entry = nl2br ($row['entry'], true);
		$entry = str_replace ("\n","\n&nbsp;&nbsp;", $entry);
		print "&nbsp;&nbsp;".$entry."<br /><br />\n
		Required word count: ". $cap_min_words .". Your word count: ";
		if ($count_entry < $cap_min_words) {
			echo "<span style=\"color:red\">".$count_entry."</span>\n";
		}
			else {
			echo $count_entry;
			}
		echo "<br /><br />\n
		Started: $date_entered | Last revised: $date_revised<br /><br />\n";
		$author_check = $dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
		$author_check->execute();
        if ($author_check->fetchColumn() == $row['author']) {
		echo "<a href=\"edit_captions.php?id={$row['caption_id']}\">Edit Now</a><br />
<a href=\"print_caption.php?id={$row['caption_id']}\">Print</a><br />
<a href=\"#top\">Return to top</a></div>\n";
}
        else {
        echo "No Access<br />\n<a href=\"print_story.php?id={$row['caption_id']}\">Print</a><br />\n
<a href=\"#top\">Return to top</a></div>\n";
        }
		echo "<hr />\n";
        }
}
$dbc = null;
require ('footer.html');
ob_end_flush();
?>