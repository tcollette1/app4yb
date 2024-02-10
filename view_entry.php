<?php // view_entry.php 
// This script retrieves entries from the database.

ob_start();
require ('header.html');
require_once ('mysql2.php');
print "<div id=\"title_list\">\n";
require ('list_titles.php');
echo "</div><div id=\"story_list\">\n";
$author_check = $dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
$author_check->execute();
$writer = $author_check->fetchColumn();
    if ($writer == $admin) {?>
        <form action="" method="post">
		Set required word count: 
  <select name="words">
  <script>
  for (var i=150; i<301; i=i+25) {
		document.write("<option value=\"" + i + "\">" + i + "</option>");
  }
  </script>
  </select> 
  <input type="submit" name="submit" class="button"
value="Change" />
  </form>
	<?php
	if (isset($_POST['submit'])) {
	if (!$min_words) {
		$min_words = array('min_words' => $_POST['words']);
		$info2 = array_merge($info, $min_words);
		$fp = fopen('configtree.txt','w');
		fwrite($fp,json_encode($info2));
	}
	else {
		$info['min_words'] = $_POST['words'];
		$json = json_encode($info);
		file_put_contents('configtree.txt', $json);
		$min_words = $_POST['words'];
	}
	}
	}
// Define the query.
		if (!$min_words) {
			$min_words = 200;
		}
		else {
			$infotxt = file_get_contents('configtree.txt'); 
			$info = json_decode($infotxt, true);
			$min_words = $info['min_words'];
		}
$copy_query = $dbc->prepare("SELECT * FROM yb_copy".$year." ORDER BY date_entered DESC");
 // Run the query.
	$copy_query->execute();
	// Retrieve and print every record.
	while ($row = $copy_query->fetch(PDO::FETCH_ASSOC)) {
		$author = stripslashes($row['author']);
		$anchor_name = urlencode($row['title']);
		$entry_date = strtotime($row['date_entered']);
		$date_entered = date('l, F j, Y, g:i a', $entry_date);
		$revision_date = strtotime($row['date_revised']);
		$date_revised = date('l, F j, Y, g:i a', $revision_date);
		echo "<div class=\"gradientBox\"><a name=\"".$anchor_name."\"><h4>{$row['title']}</h4></a>\n
		$author<br /><br />\n";
		$entry = ($row['entry']);
		// insure proper word count when including curly quotes
		$convert_entry = iconv('UTF-8', 'ASCII//TRANSLIT', $entry);
		$count_entry = str_word_count($convert_entry);
		$entry = nl2br ($row['entry'], true);
		$entry = str_replace ("\n","\n&nbsp;&nbsp;", $entry);
		echo "&nbsp;&nbsp;".$entry."<br /><br />\n";
		echo "Required word count: ".$min_words.". Your word count: ";
		if ($count_entry < $min_words) {
			echo "<span style=\"color:red\">".$count_entry."</span>\n";
		}
			else {
			echo $count_entry;
			}
		echo "<br /><br />\n
		Started: $date_entered | Last revised: $date_revised<br /><br />\n";
        if ($writer == $row['author']) {
		print "<a href=\"edit_entry.php?id={$row['copy_id']}\">Edit Now</a><br />
<a href=\"print_story.php?id={$row['copy_id']}\">Print</a><br />
<a href=\"#top\">Return to top</a></div>\n";
}
        else {
        print "No Access<br />\n<a href=\"print_story.php?id={$row['copy_id']}\">Print</a><br />\n
<a href=\"#top\">Return to top</a></div>\n";
        }
		print "<hr />\n";
}
$dbc = null; // Close the database connection.
require ('footer.html');
ob_end_flush();
?>