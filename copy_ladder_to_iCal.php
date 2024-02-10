<?php
ini_set ('display_errors', 1);
error_reporting (E_ALL & ~E_NOTICE);

require_once ('mysql2.php');
$sql      = "SELECT * FROM copy_ladder".$year." ORDER BY due_date ASC, id ASC";
$result   = $dbc->prepare($sql);
$result->execute();
$nresult  = $result->rowCount();
$ics_contents  = "BEGIN:VCALENDAR\n";
$ics_contents .= "VERSION:2.0\n";
$ics_contents .= "PRODID:PHP\n";
$ics_contents .= "METHOD:PUBLISH\n";
$ics_contents .= "X-WR-CALNAME:Copy ".$year."\n";
 
# Change the timezone as well daylight settings if need be
$ics_contents .= "X-WR-TIMEZONE:".$timezone."\n";
$ics_contents .= "BEGIN:VTIMEZONE\n";
$ics_contents .= "TZID:".$timezone."\n";
$ics_contents .= "BEGIN:DAYLIGHT\n";
$ics_contents .= "TZOFFSETFROM:-0500\n";
$ics_contents .= "TZOFFSETTO:-0600\n";
$ics_contents .= "DTSTART:20130311T020000\n";
$ics_contents .= "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU\n";
$ics_contents .= "TZNAME:MDT\n";
$ics_contents .= "END:DAYLIGHT\n";
$ics_contents .= "BEGIN:STANDARD\n";
$ics_contents .= "TZOFFSETFROM:-0600\n";
$ics_contents .= "TZOFFSETTO:-0700\n";
$ics_contents .= "DTSTART:20131104T020000\n";
$ics_contents .= "RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU\n";
$ics_contents .= "TZNAME:MST\n";
$ics_contents .= "END:STANDARD\n";
$ics_contents .= "END:VTIMEZONE\n";
$start_time = (sprintf("%06d",60000));
$id = $schedule_details['id'];
while ($schedule_details = $result->fetch(PDO::FETCH_ASSOC)) {
	  $id            = $schedule_details['id'];
	  $start_date    = $schedule_details['due_date'];
 //$end_date      = $schedule_details['EndDate'];
  //$end_time      = $schedule_details['EndTime'];
  //$category      = $schedule_details['Category'];
	  $name = $schedule_details['section_name']." - ".$schedule_details['page_name'];
  //$location      = $schedule_details['Location'];
	  $attends   = str_replace("\n", "", $schedule_details['writer']);
	  $email = $schedule_details['email'];
 
  # Remove '-' in $start_date and $end_date
  $estart_date   = str_replace("-", "", $start_date);
  //$eend_date     = str_replace("-", "", $end_date);
 
  # Remove ':' in $start_time and $end_time
  //$estart_time   = str_replace(":", "", $start_time);
  //$eend_time     = str_replace(":", "", $end_time);
 
  # Change TZID if need be
  $ics_contents .= "BEGIN:VEVENT\n";
  $ics_contents .= "DTSTART;VALUE=DATE:" . $estart_date . "T". $start_time . "\n";
  //$ics_contents .= "DTEND:"       . $eend_date . "T". $eend_time . "\n";
  $ics_contents .= "DTSTAMP:"     . date('Ymd')."\n";
  $ics_contents .= "DURATION:"    . "PT1H\n";
  //$ics_contents .= "LOCATION:"    . $location . "\n";
  $ics_contents .= "ATTENDEE;CN=\"" . $attends. "\";CUTYPE=INDIVIDUAL;PARTSTAT=ACCEPTED:
 mailto:".$email . "\n";
  $ics_contents .= "SUMMARY:"     . $name . "\n";
  $ics_contents .= "UID:"         . $id . "\n";
  $ics_contents .= "SEQUENCE:0\n";
  $ics_contents .= "END:VEVENT\n";
	}
	if ($start_time > 220000) {
	$start_time = (sprintf("%06d",60000));
		}
	else {
	$start_time = (sprintf("%06d",($start_time + 10000)));
	}
$ics_contents .= "END:VCALENDAR\n";
$ics_file   = "phpicalendar-2.4/calendars/Copy_".$year.".ics";
 
# File to write the contents
if (file_exists($ics_file)) {
 
if (is_writable($ics_file)) {
  if (!$handle = fopen($ics_file, 'w')) {
     echo "Cannot open file ($ics_file)\n\n";
     exit;
  }
 
  # Write $ics_contents to opened file
  if (fwrite($handle, $ics_contents) === FALSE) {
    echo "Cannot write to file ($ics_file)\n\n";
    exit;
  }
 
  echo "Success, wrote to <b>Copy_".$year.".ics</b><br>\n\n";

  fclose($handle);
 
} else {
  echo "The file <b>$ics_file</b> is not writable\n\n";
}
}
else {
	$handle = fopen($ics_file,'x');
	fwrite($handle, $ics_contents);
  echo "Success, wrote to <b>Copy_".$year.".ics</b><br>\n\n";

  fclose($handle);
}
$dbc = null;
?>
