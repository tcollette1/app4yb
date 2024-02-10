<?php
require ('mysql2.php'); 
// Connect to server and select databse.
$tbl_name="copy_ladder".$year; // Table name
$author_check=$dbc->prepare("SELECT author FROM users".$year." WHERE username = '$username'");
$author_check->execute();
$author = $author_check->fetchColumn();
if(isset($_POST['checkbox'])){$checkbox = $_POST['checkbox'];
if(isset($_POST['activate'])?$activate = $_POST["activate"]:$deactivate = $_POST["deactivate"])
 
$id = "('" . implode( "','", $checkbox ) . "');" ;
$sql=$dbc->prepare("UPDATE $tbl_name SET writer = CONCAT(writer, '$author', '\n') WHERE id IN $id");
$sql->execute();
}
$dateFile = file_get_contents('date.txt');
$objDateTime = new DateTime('NOW');
$objDeadline = new DateTime($dateFile);
if ($objDateTime < $objDeadline) {
	$table_query=$dbc->prepare("SELECT * FROM $tbl_name");
	$table_query->execute();
	}
	else {
$table_query=$dbc->prepare("SELECT * FROM $tbl_name WHERE email = ''");
$table_query->execute();
	}
$count=$table_query->rowCount();
ob_start();
require ('header.html');
$author = stripslashes($author);
echo "<h3>Choose copy assignments for ". $author."</h3>"; ?>
<table width="900" border="0" cellspacing="0">
<tr>
<td><form name="frmactive" method="post" action="">
<table width="900" border="1" cellpadding="3" cellspacing="0">
<tr>
  <td colspan="6"><input name="activate" type="submit" class="button" value="Accept Choices" />
  </td>
  </tr>
<tr>
<td colspan="6"><strong>Select the required number of copy assignments</strong> </td>
</tr>
<tr>
    <th>Page #</th>
    <th>Section</th>
    <th>Page Name</th>
    <th>Due Date</th>
    <th>Editor</th>
</tr>
<?php
while($rows=$table_query->fetch(PDO::FETCH_ASSOC)){
?>
<tr>
      <td><?php echo $rows['page_number']; ?></td>
      <td><?php echo $rows['section_name']; ?></td>
      <td><?php echo $rows['page_name']; ?><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['id']; ?>"></td>
      <td><?php $ddate = strtotime($rows['due_date']);
				$due_date = date('j F, Y', $ddate);
				echo $due_date ?></td>
      <td><?php echo nl2br($rows['editor']); ?></td>
</tr>
<?php
}
?>
<tr>
<td colspan="5" align="center">&nbsp;</td>
</tr>
</table>
</form>
</td>
</tr>
</table>
<?php $dbc = null;
require ('footer.html');
ob_end_flush();
 ?>