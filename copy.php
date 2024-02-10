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
<title>Copy Assignment Flow Chart</title>
<?php spinner_header() ?>
<?php link_header(); ?>
</head>
<body>
<h1>Copy Assignment Flow Chart</h1>
<?php start_section( "one", "Get the Assignment" ) ?>
Are you aware of the subject, the deadline, and the section editor that needs the copy?<br/><br/><?php start_link( "No" ); ?>
Get the information from the copy editor.<?php end_link(); ?>
<?php end_section() ?>
<?php start_section( "two", "Writing Questionnaires" ) ?>
Have you written questions for the interview subject that ask for specific information, not clich&#233;s or yes or no answers?<br/><br/><?php start_link( "No" ); ?>
Rewrite your questionnaire.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "three", "Distributing Questionnaires" ) ?>
Have your distibuted at least five questionnaires to the proper teacher/coach/sponsor and gotten them all back three days before deadline?<br/><br/><?php start_link( "No" ); ?>
Follow up to insure quick response.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "four", "Starting Your Story" ) ?>
Have you started your story with an attention-getting lead sentence or paragraph?<br/><br/><?php start_link( "No" ); ?>
Review lead suggestions in your Copy handout.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "five", "Completing Your Story" ) ?>
Have you blocked your story into paragraphs, included at least three quotes in their own paragraphs, and written all verbs in <b>past</b> tense?<br/><br/><?php start_link( "No" ); ?>
Use the <a href="add_entry.php" target=_blank class="greenlink">Story Writer</a>! Get more quotes! Write in past tense!<?php end_link(); ?><?php end_section() ?>
<?php start_section( "six", "Printing Your Story" ) ?>
Is your story written with the <a href="add_entry.php" target=_blank class="greenlink">Story Writer application</a>, spell-checked, and printed for proofreading?<br/><br/><?php start_link( "No" ); ?>
Write it, spell-check it, print it for corrections, or risk a zero assignment grade.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "seven", "Correcting Your Story" ) ?>
Has the Copy Editor proofread the story and made necessary corrections?<br/><br/><?php start_link( "No" ); ?>
Find out why not or inform her/him or its existence.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "eight", "Rewriting Your Story" ) ?>
Did you do your rewrite?<br/><br/><?php start_link( "No" ); ?>
You're a genius! Apply for editorship at NY Times while you still know everything.<?php end_link(); ?><?php end_section() ?>
<?php start_section( "nine", "Signing Off on Your Story" ) ?>
Do you know the section editor has your story?<br/><br/><?php start_link( "No" ); ?>
FIND IT!<?php end_link(); ?><?php end_section() ?>
<br /><br />
<a href="photo.php" class="greenlink">Photo Assignment Flow Chart</a>
</body>
</html>
