/*
    sortEnglishDateTime
    -------------------

    This function sorts English dateTime vaues such as:

    1st January 2003, 23:32:01
    23/03/1972 � 10:22:22
    1970/13/03 at 23:22:01
    
    The function is "safe" i.e. non-dateTime data (like the word "Unknown") can be passed in and is sorted properly.
    
    UPDATE 08/01/2009: 1. Full or Short-hand english month names (e.g. "March" or "Mar") now require a space
                       or a comma after them to be properly parsed.
                       2. If no timestamp is given, a fake timestamp "00:00:00" is added to the string this enables
                       the function to parse both date and datetime data.
*/
var sortEnglishDateTime = fdTableSort.sortNumeric;

function sortEnglishDateTimePrepareData(tdNode, innerText) {
        // You can localise the function here
        var months = ['january','february','march','april','may','june','july','august','september','october','november','december','jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];

        // Lowercase the text
        var aa = innerText.toLowerCase();         
        
        // Replace the longhand and shorthand months with an integer equivalent
        for(var i = 0; i < months.length; i++) {                 
                aa = aa.replace(new RegExp(months[i] + '([\\s|,]{1})'), (i+13)%12 + " ");
        };

        // Replace multiple spaces and anything that is not valid in the parsing of the date, then trim
        aa = aa.replace(/\s+/g, " ").replace(/([^\d\s\/-:.])/g, "").replace(/^\s\s*/, '').replace(/\s\s*$/, '');
        
        // REMOVED: No timestamp at the end, then return -1
        //if(aa.search(/(\d){2}:(\d){2}(:(\d){2})?$/) == -1) { return -1; };

        // No timestamp at the end, then create a false one         
        if(aa.search(/(\d){2}:(\d){2}(:(\d){2})?$/) == -1) { aa += " 00:00:00"; };
        
        
        // Grab the timestamp
        var timestamp = aa.match(/(\d){2}:(\d){2}(:(\d){2})?$/)[0].replace(/:/g, "");

        // Make the timestamp 6 characters by default
        if(timestamp.length == 4) { timestamp += "00"; };

        // Remove it from the string to assist the date parser, then trim
        aa = aa.replace(/(\d){2}:(\d){2}(:(\d){2})?$/, "").replace(/\s\s*$/, '');

        // If you want the parser to favour the parsing of European dd/mm/yyyy dates then leave this set to "true"
        // If you want the parser to favour the parsing of American mm/dd/yyyy dates then set to "false"
        var favourDMY = true;

        // If you have a regular expression you wish to add, add the Object to the end of the array
        var dateTest = [
                       { regExp:/^(0?[1-9]|1[012])([- \/.])(0?[1-9]|[12][0-9]|3[01])([- \/.])((\d\d)?\d\d)$/, d:3, m:1, y:5 },  // mdy
                       { regExp:/^(0?[1-9]|[12][0-9]|3[01])([- \/.])(0?[1-9]|1[012])([- \/.])((\d\d)?\d\d)$/, d:1, m:3, y:5 },  // dmy
                       { regExp:/^(\d\d\d\d)([- \/.])(0?[1-9]|1[012])([- \/.])(0?[1-9]|[12][0-9]|3[01])$/, d:5, m:3, y:1 }      // ymd
                       ];

        var start,y,m,d;
        var cnt = 0;
        var numFormats = dateTest.length;
        while(cnt < numFormats) {
               start = (cnt + (favourDMY ? numFormats + 1 : numFormats)) % numFormats;
               if(aa.match(dateTest[start].regExp)) {
                       res = aa.match(dateTest[start].regExp);
                       y = res[dateTest[start].y];
                       m = res[dateTest[start].m];
                       d = res[dateTest[start].d];
                       if(m.length == 1) m = "0" + String(m);
                       if(d.length == 1) d = "0" + String(d);
                       if(y.length != 4) y = (parseInt(y) < 50) ? "20" + String(y) : "19" + String(y);

                       return y+String(m)+d+String(timestamp);
               };
               cnt++;
        };
        return -1;
};
/*   sortEnglishLonghandDateFormat
   -----------------------------

   This custom sort function sorts dates of the format:

   "12th April, 2006" or "12 April 2006" or "12-4-2006" or "12 April" or "12 4" or "12 Apr 2006" etc

   The function expects dates to be in the format day/month/year. Should no year be stipulated,
   the function treats the year as being the current year.

   The function is "safe" i.e. non-date data (like the word "Unknown") can be passed in and is sorted properly.
*/
var sortEnglishLonghandDateFormat = fdTableSort.sortNumeric;

function sortEnglishLonghandDateFormatPrepareData(tdNode, innerText) {
        var months = ['january','february','march','april','may','june','july','august','september','october','november','december'];

        var aa = innerText.toLowerCase();

        // Replace the longhand months with an integer equivalent
        for(var i = 0; i < 12; i++) {
                aa = aa.replace(months[i], i+1).replace(months[i].substring(0,3), i+1);
        }

        // If there are still alpha characters then return -1
        if(aa.search(/a-z/) != -1) return -1;

        // Replace multiple spaces and anything that is not numeric
        aa = aa.replace(/\s+/g, " ").replace(/[^\d\s]/g, "");

        // If were left with nothing then return -1
        if(aa.replace(" ", "") == "") return -1;

        // Split on the (now) single spaces
        aa = aa.split(" ");

        // If something has gone terribly wrong then return -1
        if(aa.length < 2) return -1;

        // If no year stipulated, then add this year as default
        if(aa.length == 2) {
                aa[2] = String(new Date().getFullYear());
        }

        // Equalise the day and month
        if(aa[0].length < 2) aa[0] = "0" + String(aa[0]);
        if(aa[1].length < 2) aa[1] = "0" + String(aa[1]);

        // Deal with Y2K issues
        if(aa[2].length != 4) {
                aa[2] = (parseInt(aa[2]) < 50) ? '20' + aa[2] : '19' + aa[2];
        }

        // YMD (can be used as integer during comparison)
        return aa[2] + String(aa[1]) + aa[0];
}
