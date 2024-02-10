<?php
ob_start();

require ('header.html');
// Connect and select.
require_once ('mysql2.php');
$author_check = $dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
$author_check->execute();
$author = $author_check->fetchColumn();
$story_check = $dbc->prepare("SELECT * FROM copy_ladder".$year." LEFT OUTER JOIN yb_copy".$year." ON (copy_ladder".$year.".page_name = yb_copy".$year.".title) WHERE yb_copy".$year.".copy_id IS null AND copy_ladder".$year.".writer LIKE '%$author%'");
$story_check->execute();
if (isset ($_POST['submit'])) { // Handle the form.
    if ($_POST['title']=="Choose story title" ) {
        die('<h3>Oops!</h3> You did not select a story title. <a href="javascript:history.go(-1)">Undo</a>');
    }
    $title1 = $_POST['title'];
    $entry2 = ($_POST['paragraph']);
    $entry1 = implode ("\n  ",$entry2);
    $entry = stripslashes($entry1);
    
    // Define the query.
    $copy_query = $dbc->prepare("INSERT INTO yb_copy".$year." (copy_id, title, author, entry, date_entered, date_revised) VALUES (:copy_id, :title, :author, :entry, NOW(), NOW())");
    $copy_query->execute(array(':copy_id'=>0, ':title'=>$_POST['title'], ':author'=> $_POST['author'], ':entry'=> $entry));
    
    // Execute the query.
    $count = $copy_query->rowCount();
    if ($count == 1) {
        $query2 = $dbc->prepare("SELECT * FROM yb_copy".$year." WHERE title = '$title1' AND author = '$author'");
        $query2->execute(); // Run the query.
        print "<h3>Story entry successful</h3><p>The story <b>".$title1."</b> has been added.</p>
        <p><a href=\"view_entry.php#" .$anchor_link. "\">Go to Stories Page</a></p>";
        while ($rows = $query2->fetch(PDO::FETCH_ASSOC)) {
            $entry = nl2br(($rows['entry']));
            echo "<div class=\"gradientBox\" style=\"width:600px\">".$entry."</div>";
            $count_entry = str_word_count($entry);
            echo "Word count: ". $count_entry;
        }
    } else {
        print "<p>Could not add the entry.</p>";
    }
    
    $dbc = null;
}
else { // Display the form.
    ?>

<h3>First Draft Story Editor</h3>
<p> Total words: <span id="wordCount">0</span>
  <button class="button" id="go">Update word count</button>
</p>
<p>Minimum required word count: <?php echo $min_words; ?></p>
<script>
$('#go').click(function(){
		var wordsCount = 0;
    $('textarea').each(function(){
        var currentString = $(this).val();
		var regex = /\s/gi;
		if (currentString != "") {
		var wordCount = currentString.trim().replace(regex, ' ').match(/\S+/g).length;
		wordsCount += wordCount;
    	$('#wordCount').html(wordsCount);
		}
	});
 });
</script>
<form action="add_entry.php" method="post">
  <p>
  <div class="storymenu">Entry Title:
    <select name="title">
      <option>Choose story title</option>
      <?php
        while ($myrows = $story_check->fetch(PDO::FETCH_ASSOC)) {
            $title = ($myrows['page_name']);
            echo "<option name=\"title\" value=\"".$title."\">".$title."</option>\n";
        } ?>
    </select>
  </div>
  </p>
  <p>Entry Author:
    <input type="hidden" name="author" value="<?php echo $author ?>">
    <?php
        $author = stripslashes($author);
        echo $author; ?>
  </p>
  <table width="900" cellpadding="6">
    <tr>
      <td valign="top" bgcolor="#cccccc"><h4>Paragraph #1</h4>
        <h5>Lead-in</h5>
        <textarea name="paragraph[]" rows="11" cols="35" style="font-size:14px;text-indent:11px"></textarea></td>
      <td valign="top" bgcolor="#cccccc"><h4>Paragraph #2</h4>
        <h5>Quote</h5>
        <textarea name="paragraph[]" rows="11" cols="35" style="font-size:14px;text-indent:11px"></textarea></td>
      <td><h4><font color="#000000">Did you remember to:</font></h4>
        <h5>1) Write all verbs in past tense, unless the story describes something in the future?<br />
          2) Enclose quotes with quotation marks, with no spaces between them and the quote?</h5>
        <h5>3) End the quote before the attribution with a comma and quotation mark <strong>( ,&quot;</strong>)?<br />
          4) Attribute the quote in the style <em>title Full Name said</em>?</h5></td>
    </tr>
    <tr>
      <td valign="top" bgcolor="#cccccc"><h4>Paragraph #3</h4>
        <h5>Lead-in</h5>
        <textarea name="paragraph[]" rows="11" cols="35" style="font-size:14px;text-indent:11px"></textarea></td>
      <td valign="top" bgcolor="#cccccc"><h4>Paragraph #4</h4>
        <h5>Quote</h5>
        <textarea name="paragraph[]" rows="11" cols="35" style="font-size:14px;text-indent:11px"></textarea></td>
      <td valign="top"><h5>5) Write a second reference to a person as <em>Last Name only</em>?</h5></td>
    </tr>
    <tr>
      <td valign="top" bgcolor="#cccccc"><h4>Paragraph #5</h4>
        <h5>Lead-in</h5>
        <textarea name="paragraph[]" rows="11" cols="35" style="font-size:14px;text-indent:11px"></textarea></td>
      <td bgcolor="#cccccc"><h4>Paragraph #6</h4>
        <h5>Quote</h5>
        <textarea name="paragraph[]" rows="11" cols="35" style="font-size:14px;text-indent:11px"></textarea></td>
    </tr>
  </table>
  <br>
  <input type="submit" name="submit" class="button" value="Write My Story!" />
  <br>
</form>
<?php
    }
    $dbc = null;
    require ('footer_plain.html');
    ob_end_flush();
    ?>
