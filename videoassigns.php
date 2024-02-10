<?php
$tbl_name="video_ladder2013"; // Table name 
require ('mysql2.php'); 
// Connect to server and select databse.
$author_check=$dbc->prepare("SELECT author FROM users WHERE username = '$username'");
$author_check->execute();
$author = $author_check->fetchColumn();
if(isset($_POST['checkbox'])){$checkbox = $_POST['checkbox'];
if(isset($_POST['activate'])?$activate = $_POST["activate"]:$deactivate = $_POST["deactivate"])
 
$id = "('" . implode( "','", $checkbox ) . "');" ;
$sql=$dbc->prepare("UPDATE video_ladder2013 SET videographer = CONCAT(videographer, '$author', '\n') WHERE id IN $id");
$sql->execute();
}
 
$table_query=$dbc->prepare("SELECT * FROM $tbl_name");
$table_query->execute();
$count=$table_query->rowCount();
?>
 
<? require ('header.html');
echo "<h3>Welcome, ". $author."</h3>"; ?>
<table width="900" border="0" cellspacing="0">
<tr>
<td><form name="frmactive" method="post" action="">
<table width="900" border="1" cellpadding="3" cellspacing="0">
<tr>
  <td colspan="6"><input name="activate" type="submit" id="activate" value="Accept Choices" />
  </td>
  </tr>
<tr>
<td colspan="6"><strong>Select the required number of video assignments</strong> </td>
</tr>
<tr>
    <th>Section</th>
    <th>Video Name</th>
    <th>Due Date</th>
</tr>
<?php
while($rows=$table_query->fetch(PDO::FETCH_ASSOC)){
?>
<tr>
      <td><?php echo $rows['section_name']; ?></td>
      <td><?php echo $rows['video_name']; ?><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $rows['id']; ?>"></td>
      <td><?php $ddate = strtotime($rows['due_date']);
				$due_date = date('j F, Y', $ddate);
				echo $due_date ?></td>
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
<? require ('footer.html'); ?>