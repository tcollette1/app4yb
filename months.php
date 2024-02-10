<?php
    $pdate_query=$dbc->prepare("SELECT due_date FROM photo_ladder$year WHERE due_date >= CURDATE() ORDER BY due_date LIMIT 1");
    $pdate_query->execute();
    $pdeadline = $pdate_query->fetchColumn();
    $objDateTime = date("Y-m-d");
    if ($objDateTime == $pdeadline) {
        echo "<span style=\"font-size: 11px; color:red;\">Photos due TODAY!</span><br />";
    }
    else if ($objDateTime > $pdeadline) {
        echo "<span style=\"font-size: 11px\">Next photos due: </span><span style=\"font-size: 11px; color:red;\">None</span><br />";
    }
    else {
        echo "<span style=\"font-size: 11px\">Next photos due: ". date('j M', strtotime($pdeadline)) . "</span><br />";
    }
    $cdate_query=$dbc->prepare("SELECT due_date FROM copy_ladder$year WHERE due_date >= CURDATE() ORDER BY due_date LIMIT 1");
    $cdate_query->execute();
    $cdeadline = $cdate_query->fetchColumn();
    if ($objDateTime == $cdeadline) {
        echo "<span style=\"font-size: 11px; color:red;\">Copy due TODAY!</span><br />";
    }
    else if ($objDateTime > $cdeadline) {
        echo "<span style=\"font-size: 11px\">Next copy due: </span><span style=\"font-size: 11px; color:red;\">None</span><br />";
    }
    else {
        echo "<span style=\"font-size: 11px\">Next copy due: ". date('j M', strtotime($cdeadline)) . "<br /></span>";
    }
    $pgdate_query=$dbc->prepare("SELECT due_date FROM section_ladder$year WHERE due_date >= CURDATE() ORDER BY due_date LIMIT 1");
    $pgdate_query->execute();
    $pgdeadline = $pgdate_query->fetchColumn();
    if ($objDateTime == $pgdeadline) {
        echo "<span style=\"font-size: 11px; color:red;\">Pages due TODAY!</span><br />";
    }
    else if ($objDateTime > $pgdeadline) {
        echo "<span style=\"font-size: 11px\">Next pages due: </span><span style=\"font-size: 11px; color:red;\">None</span><br />";
    }
    else {
        echo "<span style=\"font-size: 11px\">Next pages due: ". date('j M', strtotime($pgdeadline)) . "</span><br />";
    }
    $objDeadline = date($dateFile);
    if ($objDateTime < $objDeadline) {
        echo "<span style=\"font-size: 11px\">Deadline to choose assignments: ". date('j F', $dueDate) . "</span>";
    }
    else {
        echo "<span style=\"font-size: 11px; color:red;\">Deadline to choose assignments has passed</span>";
    }
?>
        <button id="change">Change</button>
        <form action="index.php" method="POST">
  <select name="months">
  <option>Month</option>
<?php
    for ($x = 1; $x <= 12; $x++) {
        $month = str_pad($x, 2, "0", STR_PAD_LEFT);
        $date = DateTime::createFromFormat('!m', $x);
        $monthName = $date->format('F');
        echo '<option value="'.$month.'">'.$monthName.'</option>';
    }
?>
  </select>
  <select name="dates">
  <script>
  for (var i=1; i<32; i++) {
	  if (i<10) {
	  document.write("<option value=\"0" + i + "\">" + i + "</option>");
	  }
	  else {
		document.write("<option value=\"" + i + "\">" + i + "</option>");
	  }
  }
  </script>
  </select><br />
  <input type="submit" name="submit" value="Set the date" />
  </form>
