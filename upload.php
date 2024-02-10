<?php
// Turn on output buffering.
ob_start();
require ('header.html');
// At this point you should check the size and type of the file supplied by the application.  Obviously because we are dealing with images".$year." here, the supplied file should
// be an image type.  I am restricting it to .jpeg but you will probably want to check more.
 if ($_POST['image_name']=="Choose assignment title" ) {
 		die('<h3>Oops!</h3> You did not select an assignment title. <a href="javascript:history.go(-1)">Undo</a>');
 	}
$size = $_SERVER['CONTENT_LENGTH'];
if (($_FILES['user_image']['size']) < 5000000) 
{
	if ($_FILES['user_image']['type'] == "image/jpeg" || $_FILES['user_image']['type'] == "image/jpg" || $_FILES['user_image']['type'] == "image/png") 
	{
	// The file supplied is valid...Set up some variables for the location and name of the file.
	$target_folder = 'images/'; // This is the folder to which the images will be saved
	$images_path = '/'; //database
	$upload_image = $target_folder.basename($_FILES['user_image']['name']); // The new file location
	
	// Now use the move_uploaded_file function to move the file from its temporary location to its new location as defined above.
		if(move_uploaded_file($_FILES['user_image']['tmp_name'], $upload_image)) 
		{
			include("mysql2.php"); // Include the mysql file so that we can strip SQL from the variables (And we need the SQL connection later...)
		
		// The following 2 variables depend on what was requested in the form - if it was only the image itself, they are unnecessary.
			$newname = stripslashes($_POST['image_name']); // Get the supplied image name and sanitize it
			$html_newname = urlencode($newname);
			$caption = ($_POST['image_caption']); // Get the supplied caption and sanitize it
			$photographer = ($_POST['author']);		
		// The following 2 variables specify the planned names for the 2 images".$year." (actual and thumbnail).  In this example, I specify .jpg as the extension, but if you are
		// allowing multiple file extensions you may need to extend this to deal with the possibilities.
		if ($_FILES['user_image']['type'] == "image/jpeg" ||$_FILES['user_image']['type'] == "image/jpg") {
			$thumbnail = $target_folder.$html_newname."_thumbnail.jpg"; // Set the thumbnail name
			$actual = $target_folder.$html_newname.".jpg"; // Set the actual image name
		}
		else {
		$thumbnail = $target_folder.$html_newname."_thumbnail.png"; // Set the thumbnail name
		$actual = $target_folder.$html_newname.".png";
		}
	    // Get new sizes
			list($width, $height) = getimagesize($upload_image);
			$newwidth = 77; // This can be a set value or a percentage of original size ($width)
			$newheight = floor( $height*(77/$width));		
		// Load the images".$year."
		if ($_FILES['user_image']['type'] == "image/jpeg" ||$_FILES['user_image']['type'] == "image/jpg") {
			$thumb = imagecreatetruecolor($newwidth, $newheight);
			$source = imagecreatefromjpeg($upload_image);
			imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			imagejpeg($thumb, $thumbnail, 100);
		}
		else {
			$thumb = imagecreate($newwidth, $newheight);
			$source = imagecreatefrompng($upload_image);
			imagecolortransparent($source, imagecolorallocatealpha($source, 0, 0, 0, 127));
			imagealphablending($source, FALSE);
			imagesavealpha($source, TRUE);
			imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			imagepng($thumb, $thumbnail);
		}
		// Resize the $thumb image.
		
		// Save the new file to the location specified by $thumbnail
		
		// Rename the original
		//note, this only needs to be done if a new filename is supplied in the form (As it is in this example).
		// note 2, if you don't mind starting the mysql connection earlier than the file move in order to sanitize $_POST['image_name'], $upload_image can use 
		// $newname from the beginning instead of $_FILES['user_image']['name'] in line 12.
			rename($upload_image, $actual);
		
		// Now we will try and insert the details into the database with the new actual and thumbnail files, and with the new caption...
		// We carry out the mysql query as part of an IF statement to evaluate if it worked properly.
			$upload_query = $dbc->prepare("INSERT INTO images".$year." (title, thumbnail, actual, photographer, date_entered, notes) VALUES(:title, :thumbnail, :actual, :photographer, NOW(), :notes)");
			$upload_query->execute(array(':title'=>$newname,':thumbnail'=>$thumbnail,':actual'=>$actual, ':photographer'=>$photographer,':notes'=>$caption));
			$count = $upload_query->rowCount();
			if($count == 1)
			{
			// ...it did work properly.  Tell the user and do anything else you particularly feel like doing.
			?>

<h3>Image Successfully uploaded!</h3>
  Actual image:
  <?=$actual;?>
  <br />
  Thumbnail image:
  <?=$thumbnail;?>
  <br />
  Notes:
  <?=$caption;?>
  <br />
  Size:
  <?=$size;?>
  bytes
<?php
			}
			else
			{
			// ...it didn't work properly.  Unlink (delete) the actual and thumbnail files and then notify the user.
			unlink($actual);
			unlink($thumbnail);
			?>
<p>There was an error storing your image in the database. All traces of it on the server have been deleted.</p>
<?php
			}
		}
		else
		{
		// There was a problem with the file and it wasn't moved to the server.
	    ?>
<p>Error processing file. It may be too large. Rescale your image smaller and save using the <b>Save for Web…</b> command in Photoshop and try again.</p>
<?php
		}
	}
	else
	{
		// The file was the wrong format or size
	    ?>
<p>Error processing file. It is the wrong format (.jpg or .png only). Resave your image using the <b>Save for Web…</b> command in Photoshop with JPEG or PNG preset and try again.</p>
<?php
	}
}
else
{
?>
<p>Your upload is too big (you are
  <?=($size-5000000);?>
  bytes over the limit). Rescale your image smaller and save using the <b>Save for Web…</b> command in Photoshop with JPEG or PNG preset and try again.</p>
<?php
}
$dbc = null;
?>
</div>
<div style="clear:both"></div>
</div>
</body></html><?php // 
$dbc = null;
// Send the buffer to the browser and turn off buffering.
ob_end_flush();
?>