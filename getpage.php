<?php
require('mysql2.php');
$section = $_GET['choice'];
		$page_query = $dbc->prepare("SELECT * FROM copy_ladder".$year." WHERE section_name = '$section' AND writer REGEXP '\n$'");
		$page_query->execute();
		echo "<option>Choose a page</option>";
		while ($myrows = $page_query->fetch(PDO::FETCH_ASSOC)) {
		$writer_count = count(explode("\n",$myrows['writer']));
		if ($writer_count<3) {
		print "<option name=\"page\" value=\"".$myrows['page_name']."\">".$myrows['page_name']."</option>\n";
		}
		else {
		print "<option name=\"page\" value=\"".$myrows['page_name']."\">*".$myrows['page_name']."</option>\n";
		}
	}
?>