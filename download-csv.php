<?php
    require ('mysql2.php');
    // output headers so that the file is downloaded rather than displayed
    $laddertype = $_POST['laddertype'].$year;
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=$laddertype.csv");
    $ldrows = $dbc->prepare("SELECT * FROM $laddertype");
    $ldrows->execute();
    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');
    $copy = array($laddertype,'Page #', 'Section Name', 'Page Name','Due Date', 'Editor', 'Writer','Email');
    $photo = array($laddertype,'Page #', 'Section Name', 'Page Name','Due Date', 'Editor', 'Photographer','Email');
    $section = array($laddertype,'Page #', 'Section Name', 'Page Name','Due Date', 'Editor','Email');
    if ($laddertype == "copy_ladder".$year) {
        fputcsv($output, $copy);
        while($row=$ldrows->fetch(PDO::FETCH_ASSOC)) fputcsv($output, $row);
    }
    else if ($laddertype == "photo_ladder".$year) {
        fputcsv($output, $photo);
        while($row=$ldrows->fetch(PDO::FETCH_ASSOC)) fputcsv($output, $row);
    }
    else {
        fputcsv($output, $section);
        while($row=$ldrows->fetch(PDO::FETCH_ASSOC)) fputcsv($output, $row);
    }
?>
