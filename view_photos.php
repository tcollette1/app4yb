<?php 
// Address error handling.
ob_start();
require ('header.html');

// Connect and select.
require_once('mysql2.php');
$panelCounter = 0;
echo "<div id=\"title_list\">\n";
require ('list_photos.php');
echo "</div>\n<div id=\"story_list\" style=\"width:490px\">\n";
// Define the query.
$photo_query = $dbc->prepare("SELECT * FROM images".$year." ORDER BY date_entered DESC");
 // Run the query.
	$photo_query->execute();

	// Retrieve and print every record.
	while ($rows = $photo_query->fetch(PDO::FETCH_ASSOC)) {
		$photographer = ($rows['photographer']);
		$anchor_name = urlencode($rows['title']);
		$entry_date = strtotime($rows['date_entered']);
		$date_entered = date('l, F j, Y, g:i a', $entry_date);
		if ($panelCounter%2==0) { ?>
		<div class="evenrow">
        <img src="<?php echo $rows['thumbnail']; ?>" style="float:right" />
        <a name="<?php echo $anchor_name; ?>">
        <h5><?php echo $rows['title']; ?></h5></a>
        <em><?php echo $photographer; ?></em>
        <br /><a href="#" onclick="window.open('edit_note.php?id=<?php echo $rows['photo_id']; ?>','newwindow',config='width=399,height=249,left=200,top=100,screenX=200,screenY=100'); return false;" title="Click to Edit">Notes:</a> <?php echo $rows['notes']; ?><br /><?php echo $date_entered; ?></div><hr style="clear:both" />
		<?php }
		else { ?>
		<div style="padding:2px">
        <img src="<?php echo $rows['thumbnail']; ?>" style="float:right" />
        <a name="<?php echo $anchor_name; ?>">
        <h5><?php echo $rows['title']; ?></h5></a>
        <em><?php echo $photographer; ?></em>
        <br /><a href="#" onclick="window.open('edit_note.php?id=<?php echo $rows['photo_id']; ?>','newwindow',config='width=399,height=249,left=200,top=100,screenX=200,screenY=100'); return false;" title="Click to Edit">Notes:</a> <?php echo nl2br($rows['notes']); ?><br /><?php echo $date_entered; ?></div><hr style="clear:both" />
		<?php }
		$panelCounter++; 
}
$dbc = null;
require ('footer.html');
ob_end_flush();
?>