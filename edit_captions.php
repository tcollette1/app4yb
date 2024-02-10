<?php // 
    require ('header.html');
    require_once ('mysql2.php');
    
    $author_check = $dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
    $author_check->execute();
    $author = $author_check->fetchColumn();
    if (isset ($_POST['submit'])) { // Handle the form.
        
        // Define the query.
        $entry = ($_POST['entry']);
        $title = ($_POST['title']);
        $anchor_link = urlencode($_POST['title']);
		$query =  $dbc->prepare("UPDATE captions".$year." SET title=?, entry=?, date_revised=NOW() WHERE caption_id=?");
        $query->execute(array($title, $entry, $_POST['id'])); // Execute the query.
        
        // Report on the result.
        $count = $query->rowCount();
        if ($count == 1) {
            echo "<h3>Update successful</h3>\n<p>The captions <b>" .$title. "</b> by " .$author. " have been updated.</p><p><a href=\"view_captions.php#" .$anchor_link. "\" />Return to Captions Page</a></p>";
        } else {
            echo "<p>Could not update the entry.</p>";
        }
        
    } else { // Display the entry in a form.
        
        // Check for a valid entry ID in the URL.
        if (is_numeric ($_GET['id']) ) {
            
            // Define the query.
            $query =  $dbc->prepare("SELECT * FROM captions".$year." WHERE caption_id={$_GET['id']}");
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC); // Retrieve the information.
            $entry = ($row['entry']);
            // Make the form.
            ?>
<h3>Caption Revision Editor</h3>
<form action="" method="post">
<p>Entry Title: <input type="hidden" name="title" size="40" maxsize="100" value="<?php echo $row['title'] ?>" /><?php echo $row['title'] ?></p>
<p>Entry Text:<br /><textarea name="entry" cols="70" rows="21" style="font-size:17px;text-indent:11px"><?php echo $entry ?></textarea></p>
<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
<input type="submit" name="submit" class="button" value="Update These Captions!" />
</form>
<?php
    } else { // No ID set.
        echo '<p><b>You have accessed this page in error.</b></p>';
    }
    
    } // End of main IF.
    
    $dbc = null; // Close the database connection.
    
    ?>
