<?php
ob_start();
require ('header.html');

	// Connect and select.
require('mysql2.php');
$author_check=$dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
$author_check->execute();
$author = $author_check->fetchColumn();
$story_check = $dbc->prepare("SELECT * FROM photo_ladder".$year." LEFT OUTER JOIN images".$year." ON (photo_ladder".$year.".page_name = images".$year.".title) WHERE images".$year.".photo_id IS null AND photo_ladder".$year.".photog LIKE '%$author%'");
$story_check->execute();
?>
<h3>Cropped Photo Upload</h3>
<?php
$author = stripslashes($author);
echo "<p>Photographer: $author </p>";
?>
<form enctype="multipart/form-data" action="upload.php" method="POST">
	<p><div class="storymenu">Assignment: <select name="image_name"><option>Choose assignment title</option>
<?php
	if ($author == $admin) {
		echo "<option>LOGO UPLOAD</option>";
	}
	while ($myrows = $story_check->fetch(PDO::FETCH_ASSOC)) {
		$title = ($myrows['page_name']);
		print "<option name=\"image_name\" value=\"".$title."\">".$title."</option>";
	}
?>
</select></div></p>
Notes? (100 characters max): <input type="text" size="77" name="image_caption" id="image_caption" />
	<br /><br />
<h4>Remember: Don’t upload any photos not cropped to a 4 x 3 width-to-height ratio!</h4>
	<br />
	<label for="user_image" class="button">Select image file</label>
    <img src="images/pages.png" width="25" height="18" /> 
    <input name="user_image" id="user_image" style="display:none" type="file" onchange="myFunction()" />
    <span id="file_message"> Only .jpg or .png files are allowed</span>
<script>
function myFunction(){
    var up = document.getElementById("user_image");
    var txt = "";
    if ('files' in up) {
        if (up.files.length == 0) {
            txt = "Select a file";
        } else {
            var file = up.files[0];
			if ('name' in file) {
                    txt += file.name;
                }
		}
	}
    else {
            txt += "Select a file";
	}
document.getElementById("file_message").innerHTML = txt;
}
</script>
	<br /><br />
	<input type="hidden" name="author" value="<?php echo $author ?>"> 
	<input type="submit" class="button" value="Upload" />
</form>
</div>
<div style="clear:both"></div>
</div>
</body>
</html>
<?php // 
$dbc = null;
// Send the buffer to the browser and turn off buffering.
ob_end_flush();
?>