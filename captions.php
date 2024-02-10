<?php
    ob_start();
    require ('header.html');
    require_once ('mysql2.php');
    $author_check = $dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
    $author_check->execute();
    $author = $author_check->fetchColumn();
    $story_check = $dbc->prepare("SELECT * FROM photo_ladder".$year." LEFT OUTER JOIN captions".$year." ON (photo_ladder".$year.".page_name = captions".$year.".title) WHERE captions".$year.".caption_id IS null AND photo_ladder".$year.".photog LIKE '%$author%'");
    $story_check->execute();
    if (isset ($_POST['submit'])) { // Handle the form.
        $entry1 = ($_POST['caption']);
        $caption1 = array($entry1[0],$entry1[1]);
        $caption1 = implode (" ", $caption1);
        $caption2 = array($entry1[2],$entry1[3]);
        $caption2 = implode (" ", $caption2);
        $caption3 = array($entry1[4],$entry1[5]);
        $caption3 = implode (" ", $caption3);
        $caption4 = array($entry1[6],$entry1[7]);
        $caption4 = implode (" ", $caption4);
        $caption5 = array($entry1[8],$entry1[9]);
        $caption5 = implode (" ", $caption5);
        $entry2 = array($caption1,$caption2,$caption3,$caption4,$caption5);
        $entry = implode ("\n&nbsp;&nbsp;",$entry2);
        
        // Define the query.
        $query = $dbc->prepare("INSERT INTO captions".$year." (caption_id, title, author, entry, date_entered, date_revised) VALUES (:caption_id, :title, :author, :entry, NOW(), NOW())");
        $query->execute(array(':caption_id'=>0, ':title'=>$_POST['title'], ':author'=> $_POST['author'], ':entry'=> $entry));
        
        // Execute the query.
        $count = $query->rowCount();
        if ($count == 1) {
            print "<p>The captions <br />".$entry."<br /> have been submitted.</p>";
        } else {
            print "<p>Could not add the entry.</p>";
        }
    }
    $dbc = null;
    // Display the form.
    ?>
<h3>Caption Writer</h3>
<p> Total words: <span id="wordCount">0</span> <button class="button" id="go">Update word count</button>
</p>
<p>Minimum required word count: <?php echo $cap_min_words; ?></p>
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
<form action="" method="post">
<?php
    echo '<p><div class="storymenu">Assignment: <select name="title"><option>Choose assignment title</option>';
    while ($myrows = $story_check->fetch(PDO::FETCH_ASSOC)) {
        $title = ($myrows['page_name']);
        print "<option value=\"".$title."\">".$title."</option>\n";
    }
    print '</select></div></p>
    <p>Caption Author:<input type="hidden" name="author" value="'.$author.'">';
    $author = stripslashes($author);
    print " $author</p>";
    ?>
<table width="900" cellpadding="6">
    <tr>
      <td valign="top" bgcolor="#cccccc"><h4>Caption #1</h4>
        <h5>Identify and Describe</h5>
        <textarea name="caption[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"></textarea></td>
      <td valign="top" bgcolor="#cccccc"><h4>Caption #1</h4>
        <h5>Elaborate</h5>
        <textarea name="caption[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"></textarea></td>
      <td width="60%" valign="top"><h4>Remember the 5 style rules:</h4>
        1) Identify everyone by first and last name.<br>
        2) A second reference to a person is by last name only.<br></td>
    </tr>
    <tr>
      <td valign="top" bgcolor="#cccccc"><h4>Caption #2</h4>
        <h5>Identify and Describe</h5>
        <textarea name="caption[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"></textarea></td>
      <td valign="top" bgcolor="#cccccc"><h4>Caption #2</h4>
        <h5>Elaborate</h5>
        <textarea name="caption[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"></textarea></td>
      <td width="60%" valign="top"> 3) Everyone gets a title. Nobody is Mr., Mrs., Ms.<br>
        4) Describe in present tense, elaborate in past tense.<br>
        5) For variety, use a gerund opening. </td>
    </tr>
    <tr>
      <td valign="top" bgcolor="#cccccc"><h4>Caption #3</h4>
        <h5>Identify and Describe</h5>
        <textarea name="caption[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"></textarea></td>
      <td valign="top" bgcolor="#cccccc"><h4>Caption #3</h4>
        <h5>Elaborate</h5>
        <textarea name="caption[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"></textarea></td>
      <td><h4>Did you remember to:</h4>
        1) Write description verbs in present tense, not present participle or past tense?<br>
        2) Write elaboration verbs in past tense?<br></td>
    </tr>
    <tr>
      <td valign="top" bgcolor="#cccccc"><h4>Caption #4</h4>
        <h5>Identify and Describe</h5>
        <textarea name="caption[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"></textarea></td>
      <td valign="top" bgcolor="#cccccc"><h4>Caption #4</h4>
        <h5>Elaborate</h5>
        <textarea name="caption[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"></textarea></td>
      <td>3) Write two captions with gerund openings?<br>
        4) Check punctuation and correct underlined spelling errors?<br>
        <a href="#" onClick="window.open('../../elab.html','newwindow',config='width=599,height=689,left=100,top=100,screenX=100,screenY=100'); return false;">What can I say in elaboration?</a></td>
    </tr>
    <tr>
      <td valign="top" bgcolor="#cccccc"><h4>Caption #5</h4>
        <h5>Identify and Describe</h5>
        <textarea name="caption[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"></textarea></td>
      <td valign="top" bgcolor="#cccccc"><h4>Caption #5</h4>
        <h5>Elaborate</h5>
        <textarea name="caption[]" rows="7" cols="40" style="font-size:14px;text-indent:11px"></textarea></td>
      <td>&nbsp;</td>
    </tr>
  </table>
<br>
<input type="submit" class="button" name="submit" value="Write My Captions!" />
</form>
<?php
    $dbc = null;
    require ('footer.html');
    ob_end_flush();
    ?>
