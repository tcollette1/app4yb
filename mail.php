<?php
include("mysql2.php");
ob_start();
require ("header.html");
$quotes = file('quotes.txt');
$from = $admin_email;
$headers = "From: ". $admin . "\r\n";
$headers .= "Reply-To: ". $from . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
echo "<h3>The following emails were sent:</h3>\n";
echo "<h4>Page Deadlines</h4>\n";
$x = $dbc->prepare("SELECT * FROM section_ladder".$year." WHERE (page_number = 1) || (page_number%2 = 0) AND due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 11 DAY)");
$x->execute();
        while ($rows = $x->fetch(PDO::FETCH_ASSOC)) {
		$page = $rows['page_number'];
		$page_name = $rows['page_name'];
		$ddate = strtotime($rows['due_date']);
		$due_date = date('l, F j, Y', $ddate);
		$editor = $rows['editor'];
		$quote = array_rand($quotes, 1);
		$mail = str_replace("\n",",", $rows['email']);
		$to = $mail;
		$subject = $rows['section_name']." - ".$page." - ".$rows['page_name'];
		$c = $dbc->prepare("SELECT writer FROM copy_ladder".$year." JOIN section_ladder".$year." ON (copy_ladder".$year.".page_name = '$page_name') WHERE section_ladder".$year.".due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 11 DAY)");
		$c->execute();
			if ($c->rowCount() == 0) {
			$writer = "Not assigned";
			}
			else {
			$writer = $c->fetchColumn();
			}
		$p = $dbc->prepare("SELECT photog FROM photo_ladder".$year." JOIN section_ladder".$year." ON (photo_ladder".$year.".page_name = '$page_name') WHERE section_ladder".$year.".due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 11 DAY)");
		$p->execute();
			if ($p->rowCount() == 0) {
			$photographer = "Not assigned";
			}
			else {
			$photographer = $p->fetchColumn();
			$photographer = str_replace("\n","<br / >",$photographer);
			}
		$message = $admin . " reminds you the page deadline for " . $rows['section_name']." - ".$page." - ".$page_name . " is $due_date.<br /><br />\r\n\r\n
This is official notification of your deadline, but you don't need to reply unless you have a problem that needs my attention.<br /><br />\r\n\r\n
Contributors of copy and photos to your page, if any, are:<br />\r\n
Copy: <br /><b>".  $writer. "</b><br />\r\n
Photos: <br /><b>". $photographer. "</b><br /><br />\r\n\r\n
Remember, a complete page will:
<ul><li>Have all graphics placed, with no missing links to their files</li>
<li>Have all captions written, with all subjects identified</li>
<li>Contain a complete story, or quotes from individuals as needed</li></ul>
\r\n\r\n" . $admin . "<br />\r\n Yearbook Adviser<br /><br />\r\n\r\n<em>".$quotes[$quote]."</em>";
mail($to,$subject,$message,$headers);
echo "To: <b>" . $editor . "</b> || Re: <b>". $subject . "</b> || On: <b>". $due_date . "</b><br />\n";
	}
echo "<h4>Copy Deadlines</h4>\n";
$y = $dbc->prepare("SELECT * FROM copy_ladder".$year." WHERE (page_number%2 = 0) AND due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 11 DAY)");
$y->execute();
        while ($rows = $y->fetch(PDO::FETCH_ASSOC)) {
		$ddate = strtotime($rows['due_date']);
		$due_date = date('l, F j, Y', $ddate);
		$writer = $rows['writer'];
		$editor = str_replace("\n","<br />", $rows['editor']);
		$quote = array_rand($quotes, 1);
		$mail = str_replace("\n",",", $rows['email']);
		$to = $mail;
		$subject = $rows['section_name']." - ".$rows['page_name'];
		$message = $admin . " reminds you the copy deadline for " . $rows['section_name']." - ".$rows['page_name'] . " is $due_date.<br /><br />\r\n\r\n
This is official notification of your deadline, but you don't need to reply unless you have a problem that needs my attention.<br /><br />\r\n\r\n
The editor of the page to which you are contributing this copy: <br /><b>".  $editor. "</b><br /><br />\r\n
";
if ($domain != "") {
	echo "Remember to review the procedure for completing a copy assignment <a href=\"http://" . $domain . "copy.php\">here</a>.<br /><br />
\r\n\r\n";
}
echo $admin . "<br />\r\n Yearbook Adviser<br />\r\n<br />\r\n\r\n<em>".$quotes[$quote]."</em>";
mail($to,$subject,$message,$headers);
echo "To: <b>" . $writer . "</b> || Re: <b>". $subject . "</b> || On: <b>". $due_date ."</b><br />\n";
	}
echo "<h4>Photo Deadlines</h4>\n";
$z = $dbc->prepare("SELECT * FROM photo_ladder".$year." WHERE (page_number = 1) || (page_number%2 = 0) AND due_date BETWEEN DATE_ADD(NOW( ), INTERVAL 5 DAY) AND DATE_ADD(NOW( ), INTERVAL 11 DAY)");
$z->execute();
        while ($rows = $z->fetch(PDO::FETCH_ASSOC)) {
		$ddate = strtotime($rows['due_date']);
		$due_date = date('l, F j, Y', $ddate);
		$photographer = $rows['photog'];
		$editor = str_replace("\n","<br />", $rows['editor']);
		$quote = array_rand($quotes, 1);
		$mail = str_replace("\n",",", $rows['email']);
		$to = $mail;
		$subject = $rows['section_name']." - ".$rows['page_name'];
		$message = $admin . " reminds you the photo deadline for " . $rows['section_name']." - ".$rows['page_name'] . " is $due_date.<br /><br />\r\n\r\n
This is official notification of your deadline, but you don't need to reply unless you have a problem that needs my attention.<br /><br />\r\n\r\n
The editor(s) of the page to which you are contributing these photos: <br /><b>".  $editor. "</b><br /><br />\r\n";
if ($domain != "") {
	$message .=
	"Remember to review the techniques for photographing your particular assignment <a href=\"http://" . $domain . "/app4yb/photohints.html\">here</a>, and the procedure for completing a photo assignment <a href=\"http://" . $domain . "/app4yb/photo.php\">here</a>.<br /><br />\r\n\r\n";
}
$message .= $admin."<br />\r\n Yearbook Adviser<br />\r\n<br />\r\n\r\n<em>".$quotes[$quote]."</em>";
mail($to,$subject,$message,$headers);
echo "To: <b>" . $photographer. "</b> || Re: <b>". $subject . "</b> || On: <b>". $due_date ."</b><br />\n";
	}
$dbc = null;
require ("footer.html");
ob_end_flush();
?>