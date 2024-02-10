<?php
require('mysql2.php');
include ('config.php');
$page = $_GET['choice2'];
		$photog_query = $dbc->prepare("SELECT photog FROM photo_ladder".$year." WHERE page_name = '$page'");
		$photog_query->execute();
		echo "<option>Choose a photographer</option>";
		$photogs = $photog_query->fetchColumn();
		$photogs = explode("\n", $photogs);
		foreach ($photogs as $photog_link) {
		print "<option name=\"photog\" value=\"".$photog_link."\">".$photog_link."</option>\n";
	}
?>