<?php require_once('mysql2.php');
require ('header.html');

date_default_timezone_set('America/Denver');
$video_ladder = $dbc->prepare("SELECT * FROM video_ladder2013");
$video_ladder->execute();
$row_video_ladder = $video_ladder->fetch(PDO::FETCH_ASSOC);
$totalRows_video_ladder = $video_ladder->fetchColumn();
?>
<table border="1" cellspacing="0" cellpadding="3">
  <tr>
    <th scope="col" class="sortable">Section</th>
    <th scope="col" class="sortable">Video Name</th>
    <th scope="col" class="sortable-sortEnglishLonghandDateFormat">Due Date</th>
    <th scope="col" class="sortable">Videographer</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_video_ladder['section_name']; ?></td>
      <td><?php echo $row_video_ladder['video_name']; ?></td>
      <td><?php $ddate = strtotime($row_video_ladder['due_date']);
				$due_date = date('j F, Y', $ddate);
				echo $due_date ?></td>
      <td><?php $videographer = str_replace ("\n","<br />\n", $row_video_ladder['videographer']);
				echo $videographer; ?></td>
    </tr>
    <?php } while ($row_video_ladder = $video_ladder->fetch(PDO::FETCH_ASSOC)); ?>
</table>
<?php
$dbc = null;
require ('footer.html');
?>