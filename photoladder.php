<?php
require_once('mysql2.php');
ob_start();
require ('header-ladder.html');
$photo_ladder = $dbc->prepare("SELECT * FROM photo_ladder".$year." a LEFT OUTER JOIN images".$year." b ON (a.page_name = b.title) WHERE page_number<2 OR page_number%2=0 ORDER BY page_number ASC");
$photo_ladder->execute();
$row_photo_ladder = $photo_ladder->fetch(PDO::FETCH_ASSOC);
$author_check=$dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
$author_check->execute();
$author = $author_check->fetchColumn();
    if ($author == $admin) {
		echo "<script type=\"text/javascript\" src=\"save-photo-edit.js\"></script>";
	}
?>
<div style="float:left"><h3>Photo Assignments</h3></div>
<div style="float:right"><a href="photofilter.php">Show only my assignments</a></div><br />
<table width="955" border="1" cellspacing="0" cellpadding="3">
  <tr>
    <th scope="col" class="sortable">Page #</th>
    <th scope="col" class="sortable">Section</th>
    <th scope="col" class="sortable">Page Name</th>
    <th scope="col" class="sortable-sortEnglishLonghandDateFormat">Due Date</th>
    <th scope="col" class="sortable-sortEnglishLonghandDateFormat">Date Completed</th>
    <th scope="col" class="sortable">Editor</th>
    <th scope="col" class="sortable">Photographer</th>
  </tr>
  <?php do { ?>
    <tr>
      <td contenteditable="true" oninput="saveInlineEdit(this,'page_number','<?php echo $row_photo_ladder["id"]; ?>')" onClick="highlightEdit(this);"><?php echo $row_photo_ladder['page_number']; ?></td>
      <td><?php echo $row_photo_ladder['section_name']; ?></td>
      <td contenteditable="true" oninput="saveInlineEdit(this,'page_name','<?php echo $row_photo_ladder["id"]; ?>')" onClick="highlightEdit(this);"><?php echo $row_photo_ladder['page_name']; ?></td>
      <td contenteditable="true" oninput="saveInlineEdit(this,'due_date','<?php echo $row_photo_ladder["id"]; ?>')" onClick="highlightEdit(this); myDateFormat();"><?php $ddate = strtotime($row_photo_ladder['due_date']);
				$due_date = date('j F Y', $ddate);
		if ($ddate < date(time())) {
			echo "<span style=\"color:red\">".$due_date."</span>";
			}
		else {
		echo $due_date; } ?> </td>
      <td><?php $cdate = strtotime($row_photo_ladder['date_entered']);
				$complete_date = date('j F Y', $cdate);
				if ($cdate != "") {
				echo $complete_date; }?></td>
      <td><?php $editor = nl2br($row_photo_ladder['editor']);
		echo $editor; ?></td>
      <td contenteditable="true" oninput="saveInlineEdit(this,'photog','<?php echo $row_photo_ladder["id"]; ?>')" onClick="highlightEdit(this); myReminder();"><?php $photographer = nl2br ($row_photo_ladder['photog']);
		echo $photographer; ?></td>
    </tr>
    <?php } while ($row_photo_ladder = $photo_ladder->fetch(PDO::FETCH_ASSOC)); ?>
</table>
<?php
$dbc = null;
require ('footer.html');
ob_end_flush();
?>