<?php // This script retrieves a list of entries from the database.

require_once('mysql2.php');
ob_start();
require ('header-jq.html');
?>
<script type="text/javascript">
$(function() {
    $('#select2').hide(); 
    $('#sect_name').change(function(){
        if($('#sect_name').val() != 'Choose section') {
            $('#select2').show("slide", { direction: "up" }); 
        } else {
            $('#select2').hide("slide", { direction: "up" }); 
            $('#select3').hide("slide", { direction: "up" });
        }
	});
});
$(function() {
    $('#select3').hide(); 
    $('#page').change(function(){
		if($('#page').val() != 'Choose a page') {
            $('#select3').show("slide", { direction: "up" }); 
        } else {
            $('#select3').hide("slide", { direction: "up" });
        }
	});
});
</script>
<script type="text/javascript">
$(function() {
$("#sect_name").change(function() {
  $("#page").load(encodeURI("getpage.php?choice=" + $("#sect_name").val()));
  $('#message').empty();
  var first = $(this).val();
  $('#section').val(first);
	});
$("#page").change(function() {
  $("#writer").load(encodeURI("getwriter.php?choice2=" + $("#page").val()));
  $('#message').empty();
  var second = $(this).val();
  $('#mypage').val(second);
	});
$("#writer").change(function() {
$(this).find(':selected').val()
  var third = $(this).val();
  $('#mywriter').val(third);
  var var1 = document.getElementById("section").value;
  var var2 = document.getElementById("mypage").value;
  $('#message').hide();
  $('#message').empty();
  if (third == "") {
  $('#message').append("<span style=\"color:red;padding: 9px; display:block;\">You are about to clear the " + var2 + " assignment in the " + var1 + " section. <br />Click Assign to complete or Reset to start over…</span>").slideDown("slow");
  }
  else if (third == "Choose a writer") {
  $('#message').append("<span style=\"color:red;padding: 9px; display:block;\">Your  choice <b>" + third + "</b> is invalid. Please correct it…</span>").slideDown("slow");
  }
  else {
	$('#message').append("<span style=\"color:green;padding: 9px; display:block;\">You are about to choose <b>" + third + "</b> for the " + var2 + " assignment in the " + var1 + " section. <br />Click Assign to complete or Reset to start over…</span>").slideDown("slow");
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
echo "<h3>Assign Writers</h3>";

$section_query = $dbc->prepare("SELECT DISTINCT section_name FROM copy_ladder".$year."");
$section_query->execute();
echo "<div class=\"storymenu\"><form action=\"list_copy_jq.php\" method=\"POST\">\n";
echo "<div id=\"select1\">\n<fieldset><legend>Select a book section…</legend>\n<select name=\"section\" id=\"sect_name\">\n<option>Choose section</option>\n";
	while ($myrows = $section_query->fetch(PDO::FETCH_ASSOC)) {
		$section_link = ($myrows['section_name']);
		print "<option name=\"section\" value=\"".$section_link."\">".$section_link."</option>\n";
	}
	print "</select></div>\n";
	echo "<div id=\"select2\">\n<fieldset><legend>Select a page…</legend>";
		echo "<select name=\"page\" id=\"page\" title=\"Multiple writers are currently assigned where marked by a *\">\n<option>Choose page name</option>";
		echo "</select>\n</fieldset>\n</div>\n";
	echo "<div id=\"select3\"><fieldset><legend>Select a writer…</legend>";
		print "<select name=\"writer\" id=\"writer\" title=\"Assign one writer to a page. Choosing a blank will clear the assignment\">\n<option>Choose writer</option>";
		echo "</select>\n<br />\n";
    echo "<input type=\"hidden\" name=\"section\" id=\"section\" value=\"\" />";
    echo "<input type=\"hidden\" name=\"page\" id=\"mypage\" value=\"\" />";
        echo "<input type=\"hidden\" name=\"writer\" id=\"mywriter\" value=\"\" />";
		$section = $_POST['section'];
		$page = $_POST['page'];
		$writer = $_POST['writer'];
		echo "<div id=\"message\" style=\"display:none\"></div>";
		echo "<input type=\"submit\" class=\"button\" name=\"submit\" value=\"Assign\" />&emsp;&emsp;<input type=\"reset\" class=\"button\" name=\"reset\" value=\"Reset\" onClick=\"location.reload();\"  /><br /><br />\n</fieldset>\n</div>\n</form><br />";
if (isset ($_POST['submit'])) { // Handle the form.
		if ($writer == "") {
		$sql=$dbc->prepare("UPDATE copy_ladder".$year." SET writer = '' WHERE page_name = '$page'");
		$sql->execute();
			echo "<h4>The assignment has been cleared…</h4>";
		}
		else {
		$email=$dbc->prepare("SELECT email FROM users".$year." WHERE author = '$writer'");
		$email->execute();
		$email=$email->fetchColumn();
		$sql=$dbc->prepare("UPDATE copy_ladder".$year." SET writer=?, email=? WHERE page_name = '$page'");
		$sql->execute(array($writer,$email));
		echo "<h4>".$writer." has been assigned as writer of ". $page. " in the ".$section." section</h4>";
		}
}
	echo "</div>\n<div style=\"float:left\">\n";
$assign_query = $dbc->prepare("SELECT * FROM copy_ladder".$year." WHERE writer NOT LIKE ''");
$assign_query->execute();
	while ($row = $assign_query->fetch(PDO::FETCH_ASSOC)) {
		$name_count = count(explode("\n",$row['writer']));
		if ($name_count<3) {
		echo $row['page_name']." in ".$row['section_name']." — ". $row['writer']. "<br />";
		}
	}
	echo "</div>\n<div style=\"float:left; margin-left: 17px;\">\n";
$number_query = $dbc->prepare("SELECT writer, COUNT(*) AS writerCount FROM `copy_ladder".$year."` GROUP BY writer");
$number_query->execute();
	while ($row = $number_query->fetch(PDO::FETCH_ASSOC)) {
		if ($row['writer'] == "") {
			echo $row['writerCount']." copy assignments are still unclaimed…<br />";
		}
		else {
		$number_count = count(explode("\n",$row['writer']));
		if ($number_count<3) {
		echo $row['writer']." — ".$row['writerCount']."  stories…". "<br />";
		}
		}
	}
	echo "</div>";
$dbc = null;
require ('footer_plain.html');
ob_end_flush();
?>