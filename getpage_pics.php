<?php
require('mysql2.php');
$section = $_GET['choice'];
		$page_query = $dbc->prepare("SELECT * FROM photo_ladder".$year." WHERE section_name = '$section' AND photog REGEXP '\n$'");
		$page_query->execute();
		echo "<option>Choose a page</option>";
		while ($myrows = $page_query->fetch(PDO::FETCH_ASSOC)) {
		$photog_count = count(explode("\n",$myrows['photog']));
		if ($myrows['email']=="") {
		print "<option name=\"page\" value=\"".$myrows['page_name']."\">".$myrows['page_name']."</option>\n";
		}
		else {
		print "<option name=\"page\" value=\"".$myrows['page_name']."\">*".$myrows['page_name']."</option>\n";
		}
	}
?>