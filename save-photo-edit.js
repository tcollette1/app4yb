// JavaScript Document
	var formatCounter = 0
function myDateFormat(){
	if (formatCounter == 0) {
  alert('Write out new date as written, or use mm/dd/yyyy or yyyy-mm-dd formatting. Invalid dates read as “31 December 1969”…');
    formatCounter++
	}
}

	var photogCounter = 0
function myReminder(){
	if (photogCounter == 0) {
  alert('Reminder: Changing assigned students before deadline is better done on the "Finalize Photo Assignments" page…');
    photogCounter++
	}
}

function highlightEdit(editableObj) {
	$(editableObj).css("background","#FFC");
}
 
function saveInlineEdit(editableObj,column,id) {
// no change change made then return false
//if($(editableObj).attr('data-old_value') === editableObj.innerHTML)
//return false;
// send ajax to update value
$(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
$.ajax({
url: "saveInlinePhotoEdit.php",
type: "POST",
data:'column='+column+'&editvalue='+editableObj.innerHTML+'&id='+id,
success: function(data) {
// set updated value as old value
//$(editableObj).attr('data-old_value',editableObj.innerHTML);
$(editableObj).css("background","#3F3");
},
});
}