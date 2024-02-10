// JavaScript Document
	var counter = 0
function myDateFormat(){
	if (counter == 0) {
  alert('Write out new date as written, or use mm/dd/yyyy or yyyy-mm-dd formatting…');
    counter++
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
url: "saveInlinePageEdit.php",
type: "POST",
data:'column='+column+'&editvalue='+editableObj.innerHTML+'&id='+id,
success: function(data) {
// set updated value as old value
//$(editableObj).attr('data-old_value',editableObj.innerHTML);
$(editableObj).css("background","#3F3");
},
});
}