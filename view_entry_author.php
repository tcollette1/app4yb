<?php //

// Address error handling.
ob_start();

require ('header.html');
require_once ('mysql2.php');
// Connect and select.
echo "<div id=\"title_list\">\n";
?>
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
echo "</div>\n<div id=\"story_list\">\n";
		if (!$min_words) {
			$min_words = 200;
		}
// Define the query.
if (isset ($_POST['submit'])) {
 // Run the query.
$writer = addslashes($_POST['writer']);
	$list_query = $dbc->prepare("SELECT * FROM yb_copy".$year." WHERE author = '$writer'");
	$list_query->execute();
$writer = stripslashes($_POST['writer']);
echo "<h3>Stories by ".$writer."</h3>\n";
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
		echo "&nbsp;&nbsp;".$entry."<br /><br />\n
		Required word count: ".$min_words.". Your word count: ";
		if ($count_entry < $min_words) {
			echo "<span style=\"color:red\">".$count_entry."</span>\n";
		}
			else {
			echo $count_entry;
			}
		echo "<br /><br />\n
		Started: $date_entered | Last revised: $date_revised<br /><br />\n";
		$author_check = $dbc->prepare("SELECT author FROM users WHERE username = '$username'");
		$author_check->execute();
        if ($author_check->fetchColumn() == $row['author']) {
		echo "<a href=\"edit_entry.php?id={$row['copy_id']}\">Edit Now</a><br />
<a href=\"print_story.php?id={$row['copy_id']}\">Print</a><br />
<a href=\"#top\">Return to top</a></div>\n";
}
        else {
        print "No Access<br />\n<a href=\"print_story.php?id={$row['copy_id']}\">Print</a><br />\n
<a href=\"#top\">Return to top</a></div>\n";
        }
		print "<hr />\n";
        }
}
$dbc = null;
require ('footer.html');
ob_end_flush();
?>