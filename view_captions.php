<?php
ob_start();

require ('header.html');
require_once ('mysql2.php');
echo "<div id=\"title_list\">\n";
require ('list_captions.php');
echo"</div>\n<div id=\"story_list\">\n";
$author_check = $dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
$author_check->execute();
$writer = $author_check->fetchColumn();
    if ($writer == $admin) {
		?>
        <form action="" method="post">
		Set required word count: 
  <select name="words">
  <script>
  for (var i=100; i<201; i=i+20) {
		document.write("<option value=\"" + i + "\">" + i + "</option>");
  }
  </script>
  </select> 
  <input type="submit" name="submit" class="button"
value="Change" />
  </form>
	<?php
	if (isset($_POST['submit'])) {
	if (!$cap_min_words) {
		$cap_min_words = array('cap_min_words' => $_POST['words']);
		$info2 = array_merge($info, $cap_min_words);
		$fp = fopen('configtree.txt','w');
		fwrite($fp,json_encode($info2));
	}
	else {
		$info['cap_min_words'] = $_POST['words'];
		$json = json_encode($info);
		file_put_contents('configtree.txt', $json);
		$cap_min_words = $_POST['words'];
	}
	}
	}
// Define the query.
		if (!$cap_min_words) {
			$cap_min_words = 120;
		}
		else {
			$infotxt = file_get_contents('configtree.txt'); 
			$info = json_decode($infotxt, true);
			$cap_min_words = $info['cap_min_words'];
		}
// Define the query.
$query = $dbc->prepare("SELECT * FROM captions".$year." ORDER BY date_entered DESC");
 // Run the query.
$query->execute();
	// Retrieve and print every record.
	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
		$author = stripslashes($row['author']);
		$anchor_name = urlencode($row['title']);
		$entry_date = strtotime($row['date_entered']);
		$date_entered = date('l, F j, Y, g:i a', $entry_date);
		$revision_date = strtotime($row['date_revised']);
		$date_revised = date('l, F j, Y, g:i a', $revision_date);
		print "<div class=\"gradientBox\"><a name=\"".$anchor_name."\"><h4>{$row['title']}</h4></a>
		$author<br /><br />\n";
		$count_entry = str_word_count($row['entry']);
		$entry = nl2br ($row['entry'], true);
		echo "&nbsp;&nbsp;".$entry."<br /><br />
		Required word count: ". $cap_min_words .". Your word count: ";
				if ($count_entry< $cap_min_words) {
			echo "<span style=\"color:red\">".$count_entry."</span>\n";
		}
			else {
			echo $count_entry;
			}
		echo "<br /><br />
		Started: $date_entered | Last revised: $date_revised<br /><br />";
        if ($writer == $row['author']) {
		echo "<a href=\"edit_captions.php?id={$row['caption_id']}\">Edit Now</a><br />
<a href=\"print_caption.php?id={$row['caption_id']}\">Print</a><br />
<a href=\"#top\">Return to top</a></div>";
}
        else {
        echo "No Access<br /><a href=\"print_caption.php?id={$row['caption_id']}\">Print</a><br />
<a href=\"#top\">Return to top</a></div>";
        }
		echo "<hr />\n";
}

$dbc = null;
require ('footer.html');
ob_end_flush();
?>