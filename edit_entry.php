<?php // edit_entry.php 

ob_start();
require ('header.html');
require_once ('mysql2.php');

$author_check = $dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
$author_check->execute();
$author = $author_check->fetchColumn();
if (isset ($_POST['submit'])) { // Handle the form.
$title1 = $_POST['title'];
$anchor_link = urlencode($_POST['title']);
$entry2 = ($_POST['paragraph']);
$entry1 = implode ("\n  ",$entry2);
$entry = stripslashes($entry1);
	
	// Define the query.
	$copy_query = $dbc->prepare("UPDATE yb_copy".$year." SET `title`=?, `entry`=?, `date_revised`= NOW() WHERE `copy_id`=?"); // Execute the query.
	$copy_query->execute(array($_POST['title'], $entry, $_POST['id']));
	// Report on the result.
	$count = $copy_query->rowCount();
	if ($count == 1) {
		$query2 = $dbc->prepare("SELECT * FROM yb_copy".$year." WHERE title = '$title1' AND author = '$author'");
		$query2->execute(); // Run the query.
		echo "<h3>Update successful</h3>\n<p>The copy entry <b>".$title1."</b> by ".$author." has been updated.</p>
		<p><a href=\"view_entry.php#" .$anchor_link. "\">Go to Stories Page</a></p>";
		while ($rows = $query2->fetch(PDO::FETCH_ASSOC)) {
		$entry = ($rows['entry']);
		// Handle correct word count when including curly quotes
		$convert_entry = iconv('UTF-8', 'ASCII//TRANSLIT', $entry);
		$count_entry = str_word_count($convert_entry);
		$entry = nl2br($rows['entry']);
		echo "<div class=\"gradientBox\" style=\"width:600px\"><h4>".$title1."</h4>".$entry."</div>";
		echo "Word count: ".$count_entry;
		}
	} else {
		print "<p>Could not update the entry.</p>";
	}

} else { // Display the entry in a form.

	// Check for a valid entry ID in the URL.
	if (is_numeric ($_GET['id']) ) {
	
		// Define the query.
		$id_query = $dbc->prepare("SELECT * FROM yb_copy".$year." WHERE copy_id={$_GET['id']}");
		$id_query->execute(); // Run the query.
		$row = $id_query->fetch(PDO::FETCH_ASSOC); // Retrieve the information.
		$paragraphs = stripslashes($row['entry']);
		$paragraph1 = explode("\n  ",$paragraphs);
		$paragraph = str_replace("\n  ","",$paragraph1);
// Make the form.
        ?>
<h3>Story Revision Editor</h3>
<form action="edit_entry.php" method="post">
<p>Entry Title: <input type="hidden" name="title" size="40" maxsize="100" value="<?php echo $row['title'] ?>" /><?php echo $row['title'] ?></p>
<p>Entry Author:<input type="hidden" name="author" value="<?php echo $author; ?>">
<?php $author = stripslashes($author);
echo $author; ?></p>
<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
  <table width="900" cellpadding="6">
    <tr>
      <td valign="top" bgcolor="#cccccc">
<h4>Paragraph #1</h3>
<h5>Lead-in</h5>
        <textarea name="paragraph[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"><?php echo $paragraph[0] ?></textarea>
</td>
      <td valign="top" bgcolor="#cccccc">
<h4>Paragraph #2</h3>
        <h5>Quote</h5>
        <textarea name="paragraph[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"><?php echo $paragraph[1] ?></textarea></td><td><h4><font color="#000000">Did 
          you remember to:</font></h4>
        <h5>1) Write all verbs in past tense, unless the story describes something in the future?<br />
          2) Enclose quotes with quotation marks, with no spaces between them and the quote?</h5>
</td>
    </tr>
<tr>
      <td valign="top" bgcolor="#cccccc">
<h4>Paragraph #3</h3>
        <h5>Lead-in</h5>
        <textarea name="paragraph[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"><?php echo $paragraph[2] ?></textarea></td>
      <td valign="top" bgcolor="#cccccc">
<h4>Paragraph #4</h3>
        <h5>Quote</h5>
        <textarea name="paragraph[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"><?php echo $paragraph[3] ?></textarea></td>
<td valign="top"><h5>3) Attribute the quote to someone with a full name and title?<br />
          4) End the sentence before the attribution with a comma and quotation mark <strong>( ,&quot;</strong>)?</h5>
      </td>
</tr>
<tr>
      <td valign="top" bgcolor="#cccccc">
<h4>Paragraph #5</h3>
        <h5>Lead-in</h5>
        <textarea name="paragraph[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"><?php echo $paragraph[4] ?></textarea></td>
      <td bgcolor="#cccccc">
<h4>Paragraph #6</h4>
        <h5>Quote</h5>
        <textarea name="paragraph[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"><?php echo $paragraph[5] ?></textarea></td></tr>
</table>
<br />
<input type="submit" name="submit" class="button" value="Edit My Story!" />
<br />
</form>
<?php
        }  else { // No ID set.
		print '<p><b>You have accessed this page in error.</p>';
	}
}
$dbc = null;
require ('footer_plain.html');
ob_end_flush();
?>
