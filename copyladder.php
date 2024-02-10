<?php
ob_start();
require_once('mysql2.php');
require ('header-ladder.html');
$copy_ladder = $dbc->prepare("SELECT * FROM copy_ladder".$year." LEFT OUTER JOIN yb_copy".$year." ON (copy_ladder".$year.".page_name = yb_copy".$year.".title) WHERE page_number<2 OR page_number%2=0 ORDER BY page_number ASC");
$copy_ladder->execute();
$row_copy_ladder = $copy_ladder->fetch(PDO::FETCH_ASSOC);
$author_check=$dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
$author_check->execute();
$author = $author_check->fetchColumn();
    if ($author == $admin) {
		echo "<script type=\"text/javascript\" src=\"save-copy-edit.js\"></script>";
}
?>
<div style="float:left"><h3>Copy Assignments</h3></div>
<div style="float:right"><a href="copyfilter.php">Show only my assignments</a></div><br />
<table width="955" border="1" cellspacing="0" cellpadding="3">
  <tr>
    <th scope="col" class="sortable">Page #</th>
    <th scope="col" class="sortable">Section</th>
    <th scope="col" class="sortable">Page Name</th>
    <th scope="col" class="sortable-sortEnglishLonghandDateFormat">Due Date</th>
    <th scope="col" class="sortable-sortEnglishLonghandDateFormat">Date Completed</th>
    <th scope="col" class="sortable">Editor</th>
    <th scope="col" class="sortable">Writer</th>
  </tr>
  <?php do { ?>
    <tr>
      <td contenteditable="true" oninput="saveInlineEdit(this,'page_number','<?php echo $row_copy_ladder["id"]; ?>')" onClick="highlightEdit(this);"><?php echo $row_copy_ladder['page_number']; ?></td>
      <td><?php echo $row_copy_ladder['section_name']; ?></td>
      <td contenteditable="true" oninput="saveInlineEdit(this,'page_name','<?php echo $row_copy_ladder["id"]; ?>')" onClick="highlightEdit(this);"><?php echo $row_copy_ladder['page_name']; ?></td>
      <td contenteditable="true" oninput="saveInlineEdit(this,'due_date','<?php echo $row_copy_ladder["id"]; ?>')" onClick="highlightEdit(this); myDateFormat();"><?php $ddate = strtotime($row_copy_ladder['due_date']);
				$due_date = date('j F Y', $ddate);
		if ($ddate < date(time())) {
			echo "<span style=\"color:red\">".$due_date."</span>";
			}
		else {
		echo $due_date; } ?> </td>
      <td><?php $cdate = strtotime($row_copy_ladder['date_revised']);
				$complete_date = date('j F Y', $cdate);
				if ($cdate != "") {
				echo $complete_date; }?></td>
      <td><?php $editor = nl2br($row_copy_ladder['editor']);
		echo $editor; ?></td>
      <td contenteditable="true" oninput="saveInlineEdit(this,'writer','<?php echo $row_copy_ladder["id"]; ?>')" onClick="highlightEdit(this); myReminder();"><?php $writer = nl2br($row_copy_ladder['writer']);
		echo $writer; ?></td>
    </tr>
    <?php } while ($row_copy_ladder = $copy_ladder->fetch(PDO::FETCH_ASSOC)); ?>
</table>
<?php
$dbc = null;
require ('footer.html');
ob_end_flush();
?>