<?php 
// Turn on output buffering.
ob_start();
 require ('header.html');
 // Connects to your Database 
 require_once ('mysql2.php');
if (isset ($_POST['submit'])) {
	$curyear = new DateTime();
	$curyear = $curyear->format('Y');
	$month = $_POST['months'];
	$date = $_POST['dates'];
	$date_contents = $curyear."-".$month."-".$date;
	$date_file   = 'date.txt';
if (is_writable($date_file)) {
  if (!$handle = fopen($date_file, 'w')) {
     echo "Cannot open file ($date_file)\n\n";
     exit;
  }
 
  # Write $date_contents to opened file
  if (fwrite($handle, $date_contents) === FALSE) {
    echo "Cannot write to file ($date_file)\n\n";
    exit;
  }
 
  #echo "Success, wrote to <b>date.txt</b><br>\n\n";

  fclose($handle);
 
} else {
  echo "The file <b>$date_file</b> is not writable\n\n";
}
}
 $dateFile = file_get_contents('date.txt');
 $dueDate = strtotime($dateFile);
 ?>
<script>
$(function() {
    $('form').hide(); 
    $('#change').click(function(){
    $('form').show("slide", { direction: "up" });
	$('#change').hide();
	});
});
  </script>
<?php
 //checks cookies to make sure they are logged in 
 	while($info = $check->fetch(PDO::FETCH_ASSOC)) 	 
 		{ 
 		$author = $info['author']; 
 		$username = ($info['username']);
 //if the cookie has the wrong password, they are taken to the login page 
 		if ($pass != $info['password']) 
 			{
 			header("Location: login.php"); 
 			} 
 
 //otherwise they are shown the admin area	 
 		if ($author == $admin)
		 {
		echo "<h3 style=\"float:left; margin-top:1.1em\">Welcome, ".$author."</h3>\n"; 
?>
<div>
  <div class="textfield" style="background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
    <h4><a href=ladder_upload.php title="Upload ladder files to the database or download ladder files to edit or backup">Ladder Admin</a></h4>
  </div>
  <div class="textfield" style="background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
    <h4><a href=calendar_update.php title="Update web calendars to reflect ladder changes">Update Web Cals</a></h4>
  </div>
</div>
<div style="clear:both">
  <div class="textfield">
    <h4><a href=deadline_email.php title="Send staffers emails reminders about imminent deadlines">Deadline Emails</a></h4>
  </div>
  <div class="textfield" style="background: url(images/check.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
    <h4><a href=copy_deadline_check.php>Check Missed Copy Deadlines</a></h4>
    <h4><a href=photo_deadline_check.php>Check Missed Photo Deadlines</a></h4>
  </div>
  <div class="textfield" style="background: url(images/quill.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
    <h4><a href=add_entry.php title="Begin writing a new copy assignment">Add a story</a></h4>
    <h4><a href=view_entry.php title="Find and edit an existing copy assignment">View and Edit a story</a></h4>
  </div>
  <div class="textfield" style="background: url(images/camera.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
    <h4><a href=form.php title="Submit a cropped photo to complete an assignment">Upload a photo</a></h4>
    <h4><a href=view_photos.php title="Review submitted photos">View Completed Photo Assignments</a></h4>
  </div>
  <div class="textfield" style="background: url(images/quill.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
    <h4><a href=captions.php title="Begin writing captions for photos selected from your assignment">Write captions</a></h4>
    <h4><a href=view_captions.php title="Edit existing captions for photos selected from your assignment">View captions</a></h4>
  </div>
</div>
<div style="clear:both">
<div class="first_textfield" style="background: url(images/find.png) center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
  <h4><a href=locator.php title="Locate students and spell-check their names">Student Locator</a></h4>
</div>
<div class="textfield" style="background: url(images/pages.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
  <h4><a href=pageladder.php>Page Assignments</a></h4>
  <h4><a href=list_sects.php title="Adviser selects students for section editorships">Finalize Page Assignments</a></h4>
</div>
<div class="textfield" style="background: url(images/quill.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
  <h4><a href=copyassigns.php title="Students select desired copy assignments">Choose Copy Assignments</a></h4>
  <h4><a href=list_copy_jq.php title="Adviser selects students for copy assignments">Finalize Copy Assignments</a></h4>
  <h4><a href=copyladder.php>Copy Assignments</a></h4>
</div>
<div class="textfield" style="background: url(images/camera.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
  <h4><a href=photoassigns.php title="Students select desired photo assignments">Choose Photo Assignments</a></h4>
  <h4><a href=list_pics.php title="Adviser selects students for photo assignments">Finalize Photo Assignments</a></h4>
  <h4><a href=photoladder.php>Photo Assignments</a></h4>
</div>
<div class="textfield" style="background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5)); clear:right">
  <h4><a href="app_tutorial.html" title="Learn how to use this app">App Tutorial</a></h4>
</div>
<div class="textfield">
  <?php
		include ('months.php');
 		echo "</div></div>\n";
		//echo "<h4><a href=videoassigns.php>Video Ladder</a></h4>\n";
 	}
		else {
		echo "<h3 style=\"float:left; margin-top:1.1em\">Welcome, ".$author."</h3>\n";
?>
<div>
  <div class="textfield" style="background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
    <h4><a href=phpicalendar-2.4/index.php title="Web calendars to read deadlines">Web Calendars</a></h4>
  </div>
</div>
  <div style="clear:both">
    <div class="first_textfield" style="background: url(images/quill.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
      <h4><a href=add_entry.php title="Begin writing a new copy assignment">Add a story</a></h4>
      <h4><a href=view_entry.php title="Find and edit an existing copy assignment">View and Edit a story</a></h4>
    </div>
    <div class="textfield" style="background: url(images/camera.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
      <h4><a href=form.php title="Submit a cropped photo to complete an assignment">Upload a photo</a></h4>
      <h4><a href=view_photos.php title="Review submitted photos">View Completed Photo Assignments</a></h4>
    </div>
    <div class="textfield" style="background: url(images/quill.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
      <h4><a href=captions.php title="Begin writing captions for photos selected from your assignment">Write captions</a></h4>
      <h4><a href=view_captions.php title="Edit existing captions for photos selected from your assignment">View and Edit captions</a></h4>
    </div>
    <div class="textfield" style="background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5)); clear:right">
      <h4><a href="app_tutorial.html" title="Learn how to use this app">App Tutorial</a></h4>
    </div>
    <div class="textfield">
      <?php
		include ('months2.php');
?>
    </div>
  </div>
  <div style="clear:both">
    <div class="first_textfield" style="background: url(images/find.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
      <h4><a href=locator.php title="Locate students and spell-check their names">Student Locator</a></h4>
    </div>
    <div class="textfield" style="background: url(images/pages.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
      <h4><a href=pageladder.php title="Review section editors, photo and copy assignments">Page/Section Assignments</a></h4>
    </div>
    <div class="textfield" style="background: url(images/quill.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
      <h4><a href=copyassigns.php title="Students select desired copy assignments">Choose Copy Assignments</a></h4>
      <h4><a href=copyladder.php>Copy Assignments</a></h4>
    </div>
    <div class="textfield" style="background: url(images/camera.png) no-repeat center, -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(15%, white), color-stop(100%, #D7E9F5));">
      <h4><a href=photoassigns.php title="Students select desired photo assignments">Choose Photo Assignments</a></h4>
      <h4><a href=photoladder.php>Photo Assignments</a></h4>
    </div>
  </div>
  <?php
		//echo "<h4><a href=videoladder.php>Video Assignments</a></h4>\n";
		//echo "<h4><a href=videoassigns.php>Video Ladder</a></h4>\n";
		}
 	}
?>
  <script type="text/javascript">
$(document).tooltip({
  tooltipClass: "tooltipclass", position: { collision:"none", my: "left top+15", at: "left bottom" },
  open: function (event, a) {
            setTimeout(function () {
                $(a.tooltip).hide('fade');
            }, 4900);
        }
});
</script>
  <div style="clear:both"></div>
</div>
</body>
</html>
<?php // 
$dbc = null;
// Send the buffer to the browser and turn off buffering.
ob_end_flush();
?>
