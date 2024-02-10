<?php
include("mysql2.php");
ob_start();
require ("header.html");
echo "Reply to address: ". $admin_email;
echo "<h3>The following email deadline notifications were found:</h3>\n";
echo "<h4>Page Deadlines</h4>\n";
$x = $dbc->prepare("SELECT * FROM section_ladder".$year." WHERE (page_number = 1) AND due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 21 DAY) || (page_number%2 = 0) AND due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 21 DAY)");
$x->execute();
        while ($rows = $x->fetch(PDO::FETCH_ASSOC)) {
		$page = $rows['page_number'];
		$page_name = $rows['page_name'];
		$ddate = strtotime($rows['due_date']);
		$due_date = date('l, F j, Y', $ddate);
		$editor = $rows['editor'];
		$subject = $rows['section_name']." - ".$page." - ".$rows['page_name'];
		$c = $dbc->prepare("SELECT writer FROM copy_ladder".$year." JOIN section_ladder".$year." ON (copy_ladder".$year.".page_name = '$page_name') WHERE section_ladder".$year.".due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 21 DAY)");
		$c->execute();
			if ($c->rowCount() == 0) {
			$writer = "Not assigned";
			}
			else {
			$writer = $c->fetchColumn();
			}
		$p = $dbc->prepare("SELECT photog FROM photo_ladder".$year." JOIN section_ladder".$year." ON (photo_ladder".$year.".page_name = '$page_name') WHERE section_ladder".$year.".due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 21 DAY)");
		$p->execute();
			if ($p->rowCount() == 0) {
			$photographer = "Not assigned";
			}
			else {
			$photographer = $p->fetchColumn();
			}
echo "<p>To: Editor -<b> " . $editor . "</b> || Re: <b>". $subject . "</b> || On: <b>". $due_date . "</b> || Copy: <b>". $writer . "</b> || Photos: <b>". $photographer . "</b></p>\n";
	}
echo "<h4>Copy Deadlines</h4>\n";
$y = $dbc->prepare("SELECT * FROM copy_ladder".$year." WHERE due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 21 DAY)");
$y->execute();
        while ($rows = $y->fetch(PDO::FETCH_ASSOC)) {
		$ddate = strtotime($rows['due_date']);
		$due_date = date('l, F j, Y', $ddate);
		$writer = $rows['writer'];
		$editor = $rows['editor'];
		$subject = $rows['section_name']." - ".$rows['page_name'];
echo "<p>To: Writer - <b>" . $writer . "</b> || Re: <b>". $subject . "</b> || On: <b>". $due_date ."</b> || Editor: <b>". $editor . "</b></p>\n";
	}
echo "<h4>Photo Deadlines</h4>\n";
$z = $dbc->prepare("SELECT * FROM photo_ladder".$year." WHERE due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 21 DAY)");
$z->execute();
        while ($rows = $z->fetch(PDO::FETCH_ASSOC)) {
		$ddate = strtotime($rows['due_date']);
		$due_date = date('l, F j, Y', $ddate);
		$photographer = $rows['photog'];
		$editor = $rows['editor'];
		$subject = $rows['section_name']." - ".$rows['page_name'];
echo "<p>To: Photographer - <b>". $photographer . "</b> || Re: <b>". $subject . "</b> || On: <b>". $due_date . "</b> || Editor: <b>". $editor . "</b></p>\n";
	}
echo '<form action="mail.php" method="post">
	<input type="submit" name="submit" class="button" value="OK to Send" />
	</form>';
$dbc = null;
require ("footer.html");
ob_end_flush();
?>