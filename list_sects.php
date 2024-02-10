<?php // This script retrieves a list of entries from the database.

require_once('mysql2.php');
ob_start();
require ('header-jq.html');
?>
  <script>
  $( function() {
    $( "#accordion" ).accordion();
  } );
  </script>
<script type="text/javascript">
$(function() {
$("#sect_name").change(function() {
$(this).find(':selected').val()
  //$(this).val()
  var second = $(this).val();
  $('#section').val(second);
  $('#message').hide();
  $('#message').empty();
});
    $('#select2').hide(); 
    $('#sect_name').change(function(){
        if($('#sect_name').val() != 'Choose section') {
            $('#select2').show("slide", { direction: "up" }); 
        } else {
            $('#select2').hide("slide", { direction: "up" }); 
        }
	});
$("#editor").change(function() {
$(this).find(':selected').val()
  //$(this).val()
  var third = $(this).val();
  $('#editors').val(third);
 var val1 = document.getElementById("section").value;
  $('#message').hide();
  $('#message').empty();
  if (third == "") {
  $('#message').append("<span style=\"color:red;padding: 9px; display:block;\">You are about to clear the " + val1 + " section assignment. <br />Click Assign to complete or Reset to start over…</span>").slideDown("slow");
  }
  else if (third == 'Choose editor') {
	$('#message').append("<span style=\"color:red;padding: 9px; display:block;\">Your choice <b>" + third + "</b> as editor is invalid. Please correct it…</span>").slideDown("slow");
  }
  else {
	$('#message').append("<span style=\"color:green;padding: 9px; display:block;\">You are about to choose <b>" + third + "</b> as editor for the " + val1 + " section. <br />Click Assign to complete or Reset to start over…</span>").slideDown("slow");
  }
});	
$("#editormult").change(function() {
$(this).find(':selected').val()
  //$(this).val()
  var fourth = $(this).val();
  $('#editors').val(fourth);
 var val2 = document.getElementById("section").value;
  $('#message').hide();
  $('#message').empty();
  if (fourth == "") {
  $('#message').append("<span style=\"color:red;padding: 9px; display:block;\">You are about to clear the " + val2 + " section assignment. <br />Click Assign to complete or Reset to start over…</span>").slideDown("slow");
  }
  else if (fourth.indexOf("Choose editor") >= 0) {
	fourthplus = (fourth+"").replace(/,/g, " and ")  
  $('#message').append("<span style=\"color:red;padding: 9px; display:block;\">Your choice <b>" + fourthplus + "</b> is invalid. Please correct it…</span>").slideDown("slow");
  }
  else {
  fourthplus = (fourth+"").replace(/,/g, " and ");
	$('#message').append("<span style=\"color:green;padding: 9px; display:block;\">You are about to choose <b>" + fourthplus + "</b> as editor for the " + val2 + " section. <br />Click Assign to complete or Reset to start over…</span>").slideDown("slow");
  }
});	
});
</script>
<script type="text/javascript">
$(document).tooltip({
  tooltipClass: "tooltipclass", position: { collision:"none", my: "left top+5", at: "right" },
  open: function (event, option) {
            setTimeout(function () {
                $(option.tooltip).hide('fade');
            }, 4900);
        }
});
</script>
<?php
echo "<h3>Assign Editors</h3>";

$section_query = $dbc->prepare("SELECT DISTINCT section_name, editor FROM section_ladder".$year."");
$section_query->execute();
$author_query = $dbc->prepare("SELECT author FROM users".$year." ORDER BY author ASC");
 // Run the query.
$author_query->execute();
$authors_query = $dbc->prepare("SELECT author FROM users".$year." ORDER BY author ASC");
 // Run the query.
$authors_query->execute();
?>
<div class="storymenu">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<div id="select1">
<fieldset><legend>Select book section</legend>
<select name="section" id="sect_name" title="Editors already assigned where marked by a *">
<option>Choose section</option>
<?php
	while ($myrows = $section_query->fetch(PDO::FETCH_ASSOC)) {
		$editor_query = ($myrows['editor']);
		$section_link = ($myrows['section_name']);
		if ($editor_query == "") {
		echo "<option value=\"".$section_link."\">".$section_link."</option>";
		}
		else {
		echo "<option value=\"".$section_link."\">".$section_link."*</option>";
		}
	}
$rows = $author_query->fetch(PDO::FETCH_ASSOC);
?>
	</select>
    </fieldset></div>
		<div id="select2">
        <fieldset>
        <legend>Select section editor</legend>
        <div id="accordion">
    <h4>Need One Editor</h4>
    <div>
            <select name="editor" id="editor">
        <option>Choose editor</option>
        <?php do { // Retrieve and print every record ?>
        <option value="<?php echo $rows['author'] ?>"><?php echo $rows['author'] ?></option>
        <?php } while ($rows = $author_query->fetch(PDO::FETCH_ASSOC)); ?>
        <option></option>
      </select>
          </div>
    <h4>Need Multiple Editors</h4>
    <div>
    <?php
$row = $authors_query->fetch(PDO::FETCH_ASSOC);
  ?>
    <select name="editor[]" id="editormult" multiple="multiple" size="7" style="height:100%" title="Assign editors to a page. Choosing a blank will clear the assignment">
    <option>Choose editor</option>
    <?php do { // Retrieve and print every record ?>
    <option value="<?php echo $row['author'] ?>"><?php echo $row['author'] ?></option>
    <?php } while ($row = $authors_query->fetch(PDO::FETCH_ASSOC)); ?>
        <option></option>
    </select>
    <br />
    </div>
  </fieldset>
  </div>
		<input type="hidden" name="section" id="section" value="" />
		<input type="hidden" name="editor" id="editors" value="" />
<div id="message" style="display:none"></div><br />
<input type="submit" class="button" name="submit" value="Assign" onClick="location.reload();" />&emsp;&emsp;
<input type="reset" class="button" name="reset" value="Reset"  onClick="location.reload();"/></form><br />
  </div>
    <?php
		$editor = str_replace(",","\n", $_POST['editor']);
		$section = $_POST['section'];
if (isset ($_POST['submit'])) {
		if ($editor == "") {
		$sql=$dbc->prepare("UPDATE section_ladder".$year." SET editor = '' WHERE section_name = '$section'");
		$sql->execute();
		$sql2=$dbc->prepare("UPDATE copy_ladder".$year." SET editor = '' WHERE section_name = '$section'; UPDATE photo_ladder".$year." SET editor = '' WHERE section_name = '$section'");
		$sql2->execute();
		$sql2->closeCursor();
			echo "<h4>The assignment has been cleared…</h4>";
		}
		else {
		$editor = explode ("\n", $editor);
			foreach ($editor as $editors) {
		$email = $dbc->prepare("SELECT email FROM users".$year." WHERE author = '$editors'");
		$email->execute();
		$email=$email->fetchColumn();
		$sect_editor .= "$editors\n";
		$emails .= "$email\n";
			}
		echo "<h4>".$sect_editor. " has been assigned as editor of ". $section. "</h4>";
		}
		$sql=$dbc->prepare("UPDATE section_ladder".$year." SET editor=?, email=? WHERE section_name = '$section'");
		$sql->execute(array($sect_editor,$emails));
		$sql2=$dbc->prepare("UPDATE copy_ladder".$year." SET editor = ? WHERE section_name = '$section'; UPDATE photo_ladder".$year." SET editor = '$sect_editor' WHERE section_name = '$section'");
		$sql2->execute(array($sect_editor));
		$sql2->closeCursor();
}
	echo "\n<div style=\"float:left; width:49%; margin-right: 17px;\">\n";
$section_edit_query = $dbc->prepare("SELECT DISTINCT section_name, editor FROM section_ladder".$year." WHERE email NOT LIKE ''");
$section_edit_query->execute();
	while ($sect_row = $section_edit_query->fetch(PDO::FETCH_ASSOC)) {
		echo $sect_row['section_name']. " has been assigned to ". preg_replace('~\\n(\w)~', " & $1",  $sect_row['editor'])."<br />";
	}
	echo "</div>";
$dbc = null;
require ('footer_plain.html');
ob_end_flush();
?>