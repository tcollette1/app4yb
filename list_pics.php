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
            $('#select3').show("slide", { direction:  "up"}); 
        } else {
            $('#select3').hide("slide", { direction: "up" });
        }
	});
});
</script>
<script type="text/javascript">
$(function() {
$("#sect_name").change(function() {
  $("#page").load(encodeURI("getpage_pics.php?choice=" + $("#sect_name").val()));
  $('#message').empty();
  var first = $(this).val();
  $('#section').val(first);
	});
$("#page").change(function() {
  $("#photog").load(encodeURI("getphotog.php?choice2=" + $("#page").val()));
  $("#photogmult").load(encodeURI("getphotog.php?choice2=" + $("#page").val()));
  $('#message').empty();
  var second = $(this).val();
  $('#mypage').val(second);
	});
$("#photog").change(function() {
$(this).find(':selected').val()
  //$(this).val()
  var fourth = $(this).val();
  $('#myphotog').val(fourth);
  var var1 = document.getElementById("section").value;
  var var2 = document.getElementById("mypage").value;
  $('#message').hide();
  $('#message').empty();
  if (fourth == "") {
  $('#message').append("<span style=\"color:red;padding: 9px; display:block;\">You are about to clear the " + var2 + " assignment in the " + var1 + " section. <br />Click Assign to complete or Reset to start over…</span>").slideDown("slow");
  }
  else if (fourth == "Choose a photographer") {
  $('#message').append("<span style=\"color:red;padding: 9px; display:block;\">Your choice <b>" + fourth + "</b> is invalid. Please correct it…</span>").slideDown("slow");
  }
  else {
	$('#message').append("<span style=\"color:green;padding: 9px; display:block;\">You are about to choose <b>" + fourth + "</b> for the " + var2 + " assignment in the " + var1 + " section. <br />Click Assign to complete or Reset to start over…</span>").slideDown("slow");
  }
});	
$("#photogmult").change(function() {
$(this).find(':selected').val()
  //$(this).val()
  var third = $(this).val();
  $('#myphotog').val(third);
  var var1 = document.getElementById("section").value;
  var var2 = document.getElementById("mypage").value;
  $('#message').hide();
  $('#message').empty();
  if (third == "") {
  $('#message').append("<span style=\"color:red;padding: 9px; display:block;\">You are about to clear the " + var2 + " assignment in the " + var1 + " section. <br />Click Assign to complete or Reset to start over…</span>").slideDown("slow");
  }
  else if (third.indexOf("Choose a photographer") >= 0) {
	thirdplus = (third+"").replace(/,/g, " and ")  
  $('#message').append("<span style=\"color:red;padding: 9px; display:block;\">Your choice <b>" + thirdplus + "</b> is invalid. Please correct it…</span>").slideDown("slow");
  }
  else {
  thirdplus = (third+"").replace(/,/g, " and ");
	$('#message').append("<span style=\"color:green;padding: 9px; display:block;\">You are about to choose <b>" + thirdplus + "</b> for the " + var2 + " assignment in the " + var1 + " section. <br />Click Assign to complete or Reset to start over…</span>").slideDown("slow");
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
print "<h3>Assign Photographers</h3>";

$section_query = $dbc->prepare("SELECT DISTINCT section_name FROM photo_ladder".$year."");
$section_query->execute();
echo "<div class=\"storymenu\"><form action=\"list_pics.php\" method=\"POST\">";
echo "<div id=\"select1\">\n<fieldset><legend>Select a book section…</legend>\n<select name=\"section\" id=\"sect_name\">\n<option>Choose section</option>\n";
	while ($myrows = $section_query->fetch(PDO::FETCH_ASSOC)) {
		$section_link = ($myrows['section_name']);
		print "<option name=\"section\" value=\"".$section_link."\">".$section_link."</option>\n";
	}
	print "</select></fieldset></div>\n";
	echo "<div id=\"select2\">\n<fieldset><legend>Select a page…</legend>";
		echo "<select name=\"page\" id=\"page\" title=\"Photographers already assigned where marked by a *\">\n<option>Choose page name</option>";
echo "</select></fieldset>\n</div>\n";		
		echo "<div id=\"select3\">\n<fieldset><legend>Select a photographer…</legend>";
	print "<div id=\"accordion\"><h4>Need One Photographer</h4><div><select name=\"photog\" id=\"photog\" title=\"Assign one photographer to a page. Choosing a blank will clear the assignment\">\n";
		echo "</select>\n</div>\n";
		echo "<h4>Need Multiple Photographers</h4><div><select name=\"photog[]\" id=\"photogmult\" multiple=\"multiple\" size=\"7\" style=\"height:100%\" title=\"Assign photographers to a page. Choosing a blank will clear the assignment\">\n";
		echo "</select></div>\n</div>\n";
    	echo "<input type=\"hidden\" name=\"section\" id=\"section\" value=\"\" />";
    	echo "<input type=\"hidden\" name=\"page\" id=\"mypage\" value=\"\" />";
        echo "<input type=\"hidden\" name=\"photog\" id=\"myphotog\" value=\"\" />";
		$section = $_POST['section'];
		$page = $_POST['page'];
		$photog = str_replace(",","\n",$_POST['photog']);
		echo "<div id=\"message\" style=\"display:none\"></div><br />";
		echo "<input type=\"submit\" class=\"button\" name=\"submit\" value=\"Assign\" />&emsp;&emsp;<input type=\"reset\" class=\"button\" name=\"reset\" value=\"Reset\" onClick=\"location.reload();\"  />\n</fieldset>\n</div>\n</form><br /><br />";
if (isset ($_POST['submit'])) { // Handle the form.
		if ($photog == "") {
		$sql=$dbc->prepare("UPDATE photo_ladder".$year." SET photog = '' WHERE page_name = '$page'");
		$sql->execute();
			echo "<h4>The assignment has been cleared…</h4>";
		}
		else {
			$photog = explode ("\n",$photog);
			foreach ($photog as $photogs) {
		$email = $dbc->prepare("SELECT email FROM users".$year." WHERE author = '$photogs'");
		$email->execute();
		$email=$email->fetchColumn();
		$photographer .= "$photogs\n";
		$emails .= "$email\n";
		$sql=$dbc->prepare("UPDATE photo_ladder".$year." SET photog=?, email=? WHERE page_name = '$page'");
		$sql->execute(array($photographer,$emails));
			}
		echo "<h4>".$photographer. " has been assigned as photographer of ". $page. " in the ".$section." section</h4>";
		}
}
	echo "</div>\n<div style=\"float:left; width:49%; margin-right: 17px;\">\n";
$assign_query = $dbc->prepare("SELECT * FROM photo_ladder".$year." WHERE (email NOT LIKE '')");
$assign_query->execute();
	while ($row = $assign_query->fetch(PDO::FETCH_ASSOC)) {
		$name_count = count(explode("\n",$row['photog']));
		echo $row['page_name']." in ".$row['section_name']." — ". preg_replace('~\\n(\w)~', " & $1", $row['photog']). "<br />";
	}
	echo "</div>\n<div style=\"margin-left: 17px;\">\n";
$number_query = $dbc->prepare("SELECT photog, COUNT(*) AS photogCount FROM `photo_ladder".$year."` WHERE (email NOT LIKE '') GROUP BY photog");
$number_query->execute();
	while ($row = $number_query->fetch(PDO::FETCH_ASSOC)) {
		if ($row['photog'] == "") {
			echo $row['photogCount']." photo assignments are still unclaimed…<br />";
		}
		else {
		$number_count = count(explode("\n",$row['photog']));
		#if ($number_count<3) {
		echo preg_replace('~\\n(\w)~', " & $1", $row['photog'])." — ".$row['photogCount']." photo assignments…". "<br />";
		#}
		}
	}
	echo "</div>";
$dbc = null;
require ('footer_plain.html');
ob_end_flush();
?>