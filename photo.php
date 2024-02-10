<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
$nextid = 1;
function start_link( $text )
{
  global $nextid;
  $idtext = "a"+$nextid++;
?>
<a href="javascript: void drop( '<?php echo($idtext); ?>' );"><span id="a_<?php echo($idtext); ?>"><?php echo($text); ?></span></a><div id="<?php echo($idtext); ?>" class="drop" style="visibility:hidden;">
<table cellspacing="0" cellpadding="0" width="270"><tr>
<td width="10">
<a href="javascript: void close(<?php echo($idtext); ?>)"><img src="images/close.gif" border="0"></a>
</td>
<td width="150">
<?php
}

function end_link()
{
?>
</td>
</tr></table>
</div>
<?php
}

function link_header()
{
?>
<style type="text/css">
body { font-family: arial, verdana; }
.drop { 
  padding: 3px;
  font-size: small;
  background: #c00;
  border: 1px solid black;
  position: absolute;
}
a:link {
	background:none;
	color: #c00;
	text-decoration:none
}
a:link.greenlink {
	background:none;
	color: #0c0;
	text-decoration:none
}
a:visited.greenlink {
	background:none;
	color: #0c0;
	text-decoration:none
}
</style>
<script language="Javascript">
function drop( sid )
{
  aobj = document.getElementById( "a_"+sid );
  divobj = document.getElementById( sid );
  divobj.style.top = aobj.offsetTop-15;
  divobj.style.left = aobj.offsetLeft+300;
  divobj.style.visibility = "visible";
}
function close( sid )
{
  divobj = document.getElementById( sid );
  divobj.style.visibility = "hidden";
}
</script>
<?php
}
?>
<?php
function start_section( $id, $title )
{
?>
<table cellspacing="0" cellpadding="0">
<tr>
<td width="30" valign="top">
<a href="javascript: void twist('<?php echo($id); ?>');">
<IMG src="images/up.gif" border="0" id="img_<?php echo($id); ?>"/>
</a>
</td>
<td width="90%">
<h2><?php echo( $title ); ?></h2>
</td>
</tr>
</table>
<div STYLE="height:0px;"
  id="<?php echo($id); ?>" class="spin-content">
<?php
}
function end_section()
{
?>
</div>
<?php
}
function spinner_header()
{
?>
<STYLE type="text/css">
body { font-family: arial, verdana; }
h1 { font-size: 18pt}
h2 { font-size: medium; border-bottom: 1px solid black; }
.spin-content { font-size: small; overflow:hidden; }
</STYLE>
<script language="Javascript">
function twist( sid )
{
  imgobj = document.getElementById( "img_"+sid );
  divobj = document.getElementById( sid );
  if ( imgobj.src.match( "up.gif" ) )
  {
    imgobj.src = "images/down.gif";
    divobj.style.height = "auto";
  }
  else
  {
    imgobj.src = "images/up.gif";
    divobj.style.height = "0px";
  }
}
</script>
<?php
}
?>
<html>
<head>
<title>Photo Assignment Flow Chart</title>
<?php spinner_header() ?>
<?php link_header(); ?>
</head>
<body>
<h1>Photo Assignment Flow Chart</h1>
<?php start_section( "one", "Get the Assignment" ) ?>
Are you aware of the subject, the deadline, and the section editor that needs the photos?<br/><br/><?php start_link( "No" ); ?>
Get the information from the head photographer.<?php end_link(); ?>
<?php end_section() ?>
<?php start_section( "two", "What Kind of Pictures?" ) ?>
Is the assignment for the sports section?<br/><br/><?php start_link( "Yes" ); ?>
<a href="photohints.html#sports" target=_blank class="greenlink">Read the guidelines</a> for sports photography.<?php end_link(); ?><br /><br />
Is the assignment for the people/clubs section?<br/><br/><?php start_link( "Yes" ); ?><a href="photohints.html"  target=_blank class="greenlink">Read the guidelines</a> for classroom photography.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "four", "Group Shots?" ) ?>
Will the assignment include a group shot?<br/><br/><?php start_link( "Yes" ); ?>
Have the sponsor/coach/club officer identify everyone as soon as possible.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "five", "Plan for Lighting Conditions" ) ?>
Will your photos be taken at night or under indoor lighting?<br/><br/><?php start_link( "Yes" ); ?>
<a href="photohints.html#exposure" target=_blank class="greenlink">Read the guidelines</a> for setting exposure.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "six", "Borrowing Equipment" ) ?>
Are you borrowing a school camera for the assignment?<br/><br/><?php start_link( "Yes" ); ?>
Complete and turn in the checkout form, and return the camera the following day.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "seven", "Critiquing Your Photos" ) ?>
Have you seen your photos and critiqued your work?<br/><br/><?php start_link( "No" ); ?>
You're a genius! Apply for photographer job at NY Times while you still know it all.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "eight", "Finishing the Assignment" ) ?>
Does the section editor have your photos? Have you helped identify subjects or written captions as needed? <a href="form.php" target=_blank class="greenlink">Did you upload</a> a 4 x 3 crop of your best photo?<br/><br/><?php start_link( "No" ); ?>FIND THEM! IDENTIFY THEM! WRITE THEM! UPLOAD!<?php end_link(); ?><?php end_section() ?>
<br /><br />
<a href="copy.php" class="greenlink">Copy Assignment Flow Chart</a>
</body>
</html>
