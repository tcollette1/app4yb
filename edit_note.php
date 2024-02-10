<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Photo Note Editor</title>
<link href="app-styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script type="text/javascript">
counter = function() {
    var value = $('textarea').val();

    if (value.length == 0) {
        $('#totalChars').html(0);
        return;
    }

    var totalChars = value.length;

    $('#totalChars').html(totalChars);
	if (totalChars > 210) {
		$( this ).css( "color", "red" );
		$( '#totalChars' ).css( "color", "red" );
	}
	else {
		$( this ).css( "color", "black" );
		$( '#totalChars' ).css( "color", "black" );

	}
};

$(document).ready(function() {
    $('textarea').change(counter);
    $('textarea').keydown(counter);
    $('textarea').keypress(counter);
    $('textarea').keyup(counter);
    $('textarea').blur(counter);
    $('textarea').focus(counter);
});
</script>
</head>
<body>
<?php
require_once ('mysql2.php');
if (isset ($_POST['submit'])) {
	$note = $_POST['note'];
	$note_query = $dbc->prepare("UPDATE images".$year." SET `notes`=? WHERE `photo_id`=?");
	$note_query->execute(array($note, $_POST['id']));
	?> <p>The note was updated! Reload the Available Photos page to confirm…</p>
    <br />
    <a href="#" onclick="window.close()";>Close</a>
<?php }
// Handle the form.
else {
	if (is_numeric ($_GET['id']) ) {
		$id_query = $dbc->prepare("SELECT * FROM images".$year." WHERE photo_id={$_GET['id']}");
		$id_query->execute(); // Run the query.
		$row = $id_query->fetch(PDO::FETCH_ASSOC); // Retrieve the information.
?>
<h5>Edit Note for <?php echo $row['title']; ?></h5>
<form action="edit_note.php" method="post" >
<textarea autofocus name="note" cols="40" rows="7" id="note" style="font-size:14px;"><?php echo $row['notes'];?>
</textarea>
Characters: <span id="totalChars"></span><br />
Limit: 210<br />
<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
<input name="submit" type="submit" class="button" value="Update Note" />
</form>
<?php
	}
	else {
		echo "You have accessed this page in error…";
	}
}
    ob_end_flush();
?>
</body>
</html>