<?php
require_once('mysql2.php');
ob_start();
require ('header-ladder.html');
$page_ladder = $dbc->prepare("SELECT * FROM section_ladder".$year." WHERE page_number<2 OR page_number%2=0 ORDER BY page_number ASC");
$page_ladder->execute();
$author_check=$dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
$author_check->execute();
$author = $author_check->fetchColumn();
    if ($author == $admin) {
		echo "<script type=\"text/javascript\" src=\"save-page-edit.js\"></script>";
	}
?>
<div style="float:left"><h3>Page Assignments</h3></div><br />
<table width="955" border="1" cellspacing="0" cellpadding="3">
  <tr>
    <th scope="col" class="sortable">Page #</th>
    <th scope="col" class="sortable">Section</th>
    <th scope="col" class="sortable">Page Name</th>
    <th scope="col" class="sortable-sortEnglishLonghandDateFormat">Due Date</th>
    <th scope="col" class="sortable">Editor</th>
    <th scope="col" class="sortable">Copy</th>
    <th scope="col" class="sortable">Photo</th>
  </tr>
  <?php while ($row_page_ladder = $page_ladder->fetch(PDO::FETCH_ASSOC)){
		$page_name = $row_page_ladder['page_name'];
		$page_number = $row_page_ladder['page_number'];
		$c = $dbc->prepare("SELECT writer FROM copy_ladder".$year." JOIN section_ladder".$year." ON (copy_ladder".$year.".page_number = '$page_number')");
		$c->execute();
			if ($c->fetchColumn() == "") {
			$writer = "<span style=\"color:red\">Not assigned</span>";
			}
			else {
			$writer = $c->fetchColumn();
			}
		$p = $dbc->prepare("SELECT photog FROM photo_ladder".$year." JOIN section_ladder".$year." ON (photo_ladder".$year.".page_number = '$page_number')");
		$p->execute();
			if ($p->fetchColumn() == "") {
			$photographer = "<span style=\"color:red\">Not assigned</span>";
			}
			else {
			$photographer = $p->fetchColumn();
			}
 ?>
    <tr>
      <td contenteditable="true" oninput="saveInlineEdit(this,'page_number','<?php echo $row_page_ladder["id"]; ?>')" onClick="highlightEdit(this);"><?php echo $row_page_ladder['page_number']; ?></td>
      <td><?php echo $row_page_ladder['section_name']; ?></td>
      <td contenteditable="true" oninput="saveInlineEdit(this,'page_name','<?php echo $row_page_ladder["id"]; ?>')" onClick="highlightEdit(this);"><?php echo $page_name; ?></td>
      <td contenteditable="true" oninput="saveInlineEdit(this,'due_date','<?php echo $row_page_ladder["id"]; ?>')" onClick="highlightEdit(this); myDateFormat();"><?php $ddate = strtotime($row_page_ladder['due_date']);
		$due_date = date('j F Y', $ddate);
		if ($ddate < date(time())) {
			echo "<span style=\"color:red\">".$due_date."</span>";
			}
		else {
		echo $due_date; } ?> </td>
      <td><?php $editor = nl2br($row_page_ladder['editor']);
		echo $editor; ?></td>
      <td><?php echo nl2br($writer); ?></td>
      <td><?php echo nl2br($photographer); ?></td>
    </tr>
    <?php } 
 ?>
</table>
</body>
</html>
<?php
$dbc = null;
require ('footer.html');
ob_end_flush();
?>