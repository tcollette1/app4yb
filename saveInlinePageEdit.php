<?php
require_once('mysql2.php');
if ($_POST["column"] == "due_date") {
	$editvalue = strip_tags($_POST["editvalue"]);
	$editvalue = date("Y-m-d", strtotime($editvalue));
$copy_update = $dbc->prepare("UPDATE section_ladder".$year." set ".$_POST["column"]." = '$editvalue' WHERE id =".$_POST["id"]);
$copy_update->execute();
}
else {
	$editvalue = strip_tags($_POST["editvalue"], '<br>');
$photo_update = $dbc->prepare("UPDATE section_ladder".$year." set " . $_POST["column"] . " = '$editvalue' WHERE id =".$_POST["id"]);
$photo_update->execute();
}
?>