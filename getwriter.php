<?php
require('mysql2.php');
$page = $_GET['choice2'];
		$writer_query = $dbc->prepare("SELECT writer FROM copy_ladder".$year." WHERE page_name = '$page'");
		$writer_query->execute();
		$writers = $writer_query->fetchColumn();
		$writers = explode("\n", $writers);
		echo "<option>Choose a writer</option>";
		foreach ($writers as $writer_link) {
		print "<option name=\"writer\" value=\"".$writer_link."\">".$writer_link."</option>\n";
	}
?>