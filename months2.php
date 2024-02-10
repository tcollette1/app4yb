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
?>
