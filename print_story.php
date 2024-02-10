<?php // 

// Address error handling.
ob_start();
require_once('mysql2.php');

require ('header.html');
	
	if (is_numeric ($_GET['id']) ) {
	
		// Define the query.
		$query = $dbc->prepare("SELECT * FROM yb_copy".$year." WHERE copy_id={$_GET['id']}");
		$query->execute();		
		$row = $query->fetch(PDO::FETCH_ASSOC); // Retrieve the information.
		$entry = str_replace ("\n","<br />\n&nbsp;&nbsp;", $row['entry']);
		$author = $row['author']; ?>
		<h3> <?php echo $author.' —&gt; '.$row['title']; ?> Story</h3><br />
        <a href="JavaScript:window.print();">Print this page</a>
        <br /><br />
        <div class="text"><?php echo $entry; ?>
        </div>
        <?php
	} else { // No ID set.
		echo '<p><b>You have accessed this page in error.</p>';
	}
$dbc = null; // Close the database connection.
ob_end_flush();
?>