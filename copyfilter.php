<?php
ob_start();
require_once('mysql2.php');
require ('header-ladder.html');
$author_check=$dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
$author_check->execute();
$author = $author_check->fetchColumn();
$copy_ladder = $dbc->prepare("SELECT * FROM copy_ladder".$year." LEFT OUTER JOIN yb_copy".$year." ON (copy_ladder".$year.".page_name = yb_copy".$year.".title) WHERE writer LIKE '%$author%'");
$copy_ladder->execute();
$row_copy_ladder=$copy_ladder->fetch(PDO::FETCH_ASSOC);
?>
<div style="float:left"><h3>Copy Assignments for <?php echo $author?></h3></div>
<div style="float:right"><a href="copyladder.php">Show all assignments</a></div><br />
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
      <td><?php echo $row_copy_ladder['page_number']; ?></td>
      <td><?php echo $row_copy_ladder['section_name']; ?></td>
      <td><?php echo $row_copy_ladder['page_name']; ?></td>
      <td><?php $ddate = strtotime($row_copy_ladder['due_date']);
				$due_date = date('j F, Y', $ddate);
		if ($ddate < date(time())) {
			echo "<span style=\"color:red\">".$due_date."</span>";
			}
		else {
		echo $due_date; } ?> </td>
      <td><?php $cdate = strtotime($row_copy_ladder['date_revised']);
				$complete_date = date('j F, Y', $cdate);
				if ($cdate != "") {
				echo $complete_date; }?></td>
      <td><?php echo $row_copy_ladder['editor']; ?></td>
      <td><?php $writer = str_replace ("\n","<br />\n", $row_copy_ladder['writer']);
				echo $writer; ?></td>
    </tr>
    <?php } while ($row_copy_ladder = $copy_ladder->fetch(PDO::FETCH_ASSOC)); ?>
</table>
<!--<h2>Deadline Assessment</h2>
<p>1. Multiply number of assignments by 20 ____</p>
<p>2. Check date stamp of assignment folder's creation date. Subtract 5 points for each deadline missed by greater than 7 days _____</p>
<p>3. Subtract 5 points for each upload date more than 7 days after completion date _____</p>
<p>Add amounts in lines b and c. _____</p>
<p>Subtract this total from line a._____</p>
<p>If result is one-half or less the amount on line a, you are liable for $10 toward cost of your yearbook.</p>-->
<?php
$dbc = null;
require ('footer.html');
ob_end_flush();
?>