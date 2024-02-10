<?php
// Address error handling.
ob_start();
require ('header.html');
	// Connect and select.
require_once ('mysql2.php');
$class="";
$message='';
$error=0;
$target_dir = dirname(__FILE__)."/Uploads/";
if(isset($_POST["import"]) && !empty($_FILES)) {
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$file_name = basename($_FILES["fileToUpload"]["name"]);
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
	if($fileType != "csv")  // here we are checking for the file extension. We don’t allow types other than (.csv) extension .
	{
		$message .= "Sorry, only a CSV file is allowed.<br>";
		$error=1;
	}
	else
	{
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			$message .="Your $file_name ladder file uploaded successfully.<br /><br />";
 
			if (($getdata = fopen($target_file, "r")) !== FALSE) {
			   fgetcsv($getdata);   
      				if ($_POST["ladder"] =="Section") {
						$ladder = $_POST["ladder"];
			   while (($data = fgetcsv($getdata)) !== FALSE) {
					$fieldCount = count($data);
					for ($c=0; $c < $fieldCount; $c++) {
					  $columnData[$c] = $data[$c];
					}
			 $page_number = $columnData[1];
			 $section_name = $columnData[2];
			 $page_name = $columnData[3];
			 $due_date = $columnData[4];
			 $editor = $columnData[5];
			 $email = $columnData[6];
			 $import_data[]="('".$page_number."','".$section_name."','".$page_name."','".$due_date."','".$editor."','".$email."')";
			 }
			 $import_data = implode(",", $import_data);
			 $trunc = $dbc->prepare("TRUNCATE TABLE `section_ladder".$year."`");
			 $trunc->execute();
			 $query = $dbc->prepare("INSERT INTO section_ladder".$year."(page_number, section_name, page_name, due_date, editor, email) VALUES $import_data ;");
			 $query->execute(array($import_data));
			 $offset = $_POST["offset"];
					}
					else if ($_POST["ladder"] =="Copy") {
							$ladder = $_POST["ladder"];
		   while (($data = fgetcsv($getdata)) !== FALSE) {
					$fieldCount = count($data);
					for ($c=0; $c < $fieldCount; $c++) {
					  $columnData[$c] = $data[$c];
					}
			 $page_number = $columnData[1];
			 $section_name = $columnData[2];
			 $page_name = $columnData[3];
			 $due_date = $columnData[4];
			 $editor = $columnData[5];
			 $writer = $columnData[6];
			 $email = $columnData[7];
	$import_data[]="('".$page_number."','".$section_name."','".$page_name."','".$due_date."','".$editor."','".$writer."','".$email."')";
			 }
			 $import_data = implode(",", $import_data);
			 $trunc = $dbc->prepare("TRUNCATE TABLE `copy_ladder".$year."`");
			 $trunc->execute();
			 $query = $dbc->prepare("INSERT INTO copy_ladder".$year."(page_number, section_name, page_name, due_date, editor, writer, email) VALUES $import_data ;");
			 $query->execute(array($import_data));
			 $offset = $_POST["offset"];
			 if ($offset > 0) {
				 $offset_query = $dbc->prepare("UPDATE `copy_ladder".$year."` SET `due_date` = DATE_SUB(`due_date`, INTERVAL $offset DAY)");
				 $offset_query->execute();
			 }
					}
					else {
						$ladder = $_POST["ladder"];
			   while (($data = fgetcsv($getdata)) !== FALSE) {
					$fieldCount = count($data);
					for ($c=0; $c < $fieldCount; $c++) {
					  $columnData[$c] = $data[$c];
					}
			 $page_number = $columnData[1];
			 $section_name = $columnData[2];
			 $page_name = $columnData[3];
			 $due_date = $columnData[4];
			 $editor = $columnData[5];
			 $photog = $columnData[6];
			 $email = $columnData[7];
			 $import_data[]="('".$page_number."','".$section_name."','".$page_name."','".$due_date."','".$editor."','".$photog."','".$email."')";
			   }
			 $import_data = implode(",", $import_data);
			 $trunc = $dbc->prepare("TRUNCATE TABLE `photo_ladder".$year."`");
			 $trunc->execute();
			 $query = $dbc->prepare("INSERT INTO photo_ladder".$year."(page_number, section_name, page_name, due_date, editor, photog, email) VALUES $import_data ;");
			 $query->execute(array($import_data));
			 $offset = $_POST["offset"];
			 if ($offset > 0) {
				 $offset_query = $dbc->prepare("UPDATE `photo_ladder".$year."` SET `due_date` = DATE_SUB(`due_date`, INTERVAL $offset DAY)");
				 $offset_query->execute();
			 }
					}
			// SQL Query to insert data into DataBase
 
			 $message .="Data imported successfully into the $ladder Ladder. Due dates offset $offset days…";
			 fclose($getdata);
			}
 
		} else {
			$message .="Sorry, there was an error uploading your file.";
			$error=1;
		}
	}
}
$class="warning";
if($error!=1)
{
	$class="success";
}
?>
<h3>Ladder File Upload/Download</h3>
<h5>If you are uploading ladders for a new year, <a href="create_tables.php">click here</a> to create the necessary tables</h5>
<form role="form" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data">
<fieldset class="form-group"><legend>Choose a ladder file to upload</legend>
      <select name="ladder">
        <option>Choose the ladder</option>
        <option selected="selected">Copy</option>
        <option>Photo</option>
        <option>Section</option>
      </select>
	<label for="offset">&nbsp;Offset number of days before page deadlines.</label>
        <select name="offset">
  <script>
  for (var i=0; i<15; i++) {
		document.write("<option value=\"" + i + "\">" + i + "</option>");
  }
  </script>
  </select><br />
	<p>
 <label for="fileToUpload" class="button">Choose file</label>
	<input name="fileToUpload" type="file" id="fileToUpload" style="display:none" onchange="myFunction()" />
    <img src="images/pages.png" width="25" height="18" /> 
    <span id="file_message"> Only a .csv file is allowed</span></p>
<script>
function myFunction(){
    var up = document.getElementById("fileToUpload");
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
    <input type="submit" class="button" value="Import Data" name="import" onclick="return confirm('Note: Clicking OK will erase any ladder that currently exists! Do you want to submit the form?')";>
  </fieldset>
    </form>
 
<?php
	if(!empty($message))
	{
?>
<div id="message">
<?php
		echo $message;
 ?>
</div>
<?php }
 ?>
 <form name="download" method="post" action="download-csv.php">
<fieldset><legend>Ladder File Download</legend>
       <select name="laddertype">
        <option>Choose the ladder</option>
        <option value="copy_ladder" selected="selected">Copy</option>
        <option value="photo_ladder">Photo</option>
        <option value="section_ladder">Section</option>
      </select><br />
<input name="submit" type="submit" class="button" value="Download Ladder File" />
</fieldset>
</form>
</div>
<?php
$dbc = null;
require ('footer_plain.html');
ob_end_flush();
?>