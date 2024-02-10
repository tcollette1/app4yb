<?php // This script retrieves student names from the database.

// Address error handling.

require_once ('mysql2.php');
ob_start();
require ('header-jq.html');

// Connect and select.
?>
<h3>Student Locator and Spell Check</h3>
<form method="post" action="locator.php">
  <p>Enter the student’s last name:
    <label>
      <input type="text" name="surname" class="auto2" spellcheck="false" />
    </label>
  </p>
  <p>
    <label>
      <input type="submit" name="submit1" class="button" value="Find that student!" />
    </label>
  </p>
</form>
<hr />
<h4>Or:</h4>
<form method="post" action="locator.php">
  <p>Enter the student’s first name:
    <label>
      <input type="text" name="firstname" class="auto" spellcheck="false" />
    </label>
  </p>
  <p>
    <label>
      <input type="submit" name="submit2" class="button" value="Find that student!" />
    </label>
  </p>
</form>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script type="text/javascript">
$(function() {
    
    //autocomplete
    $(".auto2").autocomplete({
        source: "search2.php",
        minLength: 3
    });                

});
$(function() {
    
    //autocomplete
    $(".auto").autocomplete({
        source: "search.php",
        minLength: 3
    });                

});
</script>
<?php
if (isset ($_POST['submit1'])) { // Handle the form.
	$surname = trim($_POST['surname']);
	echo "<h4>Your search for ".$surname." found:</h4>\n";
	$surname = addslashes($surname);
print "<table border=1 cellspacing=0 width=960><tr><th>Last</th><th>First</th><th>Period</th><th>ID</th><th>Grade</th><th>Teacher</th><th>Room</th></tr>\n";
$surname_query = $dbc->prepare("SELECT * FROM student_locator WHERE last_name LIKE '%$surname%'");
$surname_query->execute();
	// Retrieve and print every record.
	while ($row = $surname_query->fetch(PDO::FETCH_ASSOC)) {
	print "<tr><td align=center>{$row['last_name']}</td>\n<td align=center>{$row['first_name']}</td>\n<td align=center>{$row['period']}</td>\n<td align=center>{$row['student_ID']}</td>\n<td align=center>{$row['grade']}</td>\n<td align=center>{$row['teacher_name']}</td>\n<td align=center>{$row['room']}</td></tr>\n";
	}
print "</table>";
}
if (isset ($_POST['submit2'])) { // Handle the form.
	$firstname = trim($_POST['firstname']);
	echo "<h4>Your search for ".$firstname." found:</h4>\n";
	$firstname = addslashes($firstname);
print "<table border=1 cellspacing=0 width=800><tr><th>Last</th><th>First</th><th>Period</th><th>ID</th><th>Grade</th><th>Teacher</th><th>Room</th></tr>\n";
$firstname_query =  $dbc->prepare("SELECT * FROM student_locator WHERE first_name LIKE '%$firstname%'");
$firstname_query->execute();
	while ($row = $firstname_query->fetch(PDO::FETCH_ASSOC)) {
	print "<tr><td align=center>{$row['last_name']}</td>\n<td align=center>{$row['first_name']}</td>\n<td align=center>{$row['period']}</td>\n<td align=center>{$row['student_ID']}</td>\n<td align=center>{$row['grade']}</td>\n<td align=center>{$row['teacher_name']}</td>\n<td align=center>{$row['room']}</td></tr>\n";
	}
print "</table>";
}
$dbc = null;
require ('footer.html');
ob_end_flush();
?>