<?php // 

// Address error handling.
ini_set ('display_errors', 1);
error_reporting (E_ALL & ~E_NOTICE);

require ('header.html');
require_once ('mysql2.php');
	
	if (is_numeric ($_GET['id']) ) {
	
		// Define the query.
		$query = $dbc->prepare("SELECT * FROM captions".$year." WHERE caption_id={$_GET['id']}");		
		$query->execute();		
		$row = $query->fetch(PDO::FETCH_ASSOC); // Retrieve the information.
		$entry = str_replace ("\n","<br />\n&nbsp;&nbsp;", $row['entry']);
		$author = $row['author']; ?>
		<h3> <?php echo $author.' —&gt; '.$row['title']; ?> Captions</h3><br />
        <a href="JavaScript:window.print();">Print this page</a>
        <br /><br />
        <div class="text"><?php echo $entry; ?>
        </div>
        <?php
	} else { // No ID set.
		print '<p><b>You must have made a mistake in accessing this page.</p>';
	}
$dbc = null; // Close the database connection.

?>