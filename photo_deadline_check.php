<?php

ob_start();
require ('header.html');
require_once('mysql2.php');
$photo_deadline_check = $dbc->prepare("SELECT * FROM photo_ladder".$year." a LEFT OUTER JOIN images".$year." b ON (a.page_name = b.title) WHERE b.photo_id IS null AND a.due_date BETWEEN DATE_SUB(NOW( ), INTERVAL 21 DAY) AND DATE_SUB(NOW( ), INTERVAL 1 DAY) ORDER BY due_date ASC");
$photo_deadline_check->execute();
$row_photo_ladder = $photo_deadline_check->fetch(PDO::FETCH_ASSOC);
$count = $photo_deadline_check->rowCount();
echo "<h3>There are ". $count ." missed photo deadlines</h3>";
?>
<table border="1" cellspacing="0" cellpadding="3">
  <tr>
    <th scope="col" class="sortable">Page #</th>
    <th scope="col" class="sortable">Section</th>
    <th scope="col" class="sortable">Page Name</th>
    <th scope="col" class="sortable-sortEnglishLonghandDateFormat">Due Date</th>
    <th scope="col" class="sortable">Editor</th>
    <th scope="col" class="sortable">Photographer</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_photo_ladder['page_number']; ?></td>
      <td><?php echo $row_photo_ladder['section_name']; ?></td>
      <td><?php echo $row_photo_ladder['page_name']; ?></td>
      <td><?php $ddate = strtotime($row_photo_ladder['due_date']);
		if ($ddate == "") {
			echo "No late deadlines found!"; 
		}
		else {
			$due_date = date('j F, Y', $ddate);
			echo "<span style=\"color:red\">".$due_date."</span>";
		} ?> </td>
      <td><?php $editor = stripslashes($row_photo_ladder['editor']);
		echo $editor; ?></td>
      <td><?php $photographer = nl2br ($row_photo_ladder['photog']);
		$photographer = stripslashes ($photographer);
		echo $photographer; ?></td>
    </tr>
    <?php } while ($row_photo_ladder = $photo_deadline_check->fetch(PDO::FETCH_ASSOC)); ?>
</table>
<?php
$dbc = null;
require ('footer.html');
ob_end_flush();
?>