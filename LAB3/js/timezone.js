$(document).ready(function () {
    var x = new Date();
    // Get minutes UTC
    var offset = x.getTimezoneOffset()*60*1000;
    // Get current time in linux epoch
    var timestamp = x.getTime();
    // Convert time to UTC
    //var utc_timestamp = timestamp + (60000 * offset);
    // The lines below are used to set the forms hidden fields by referencing their DOM ids

    var UTC = Math.floor(timestamp + offset);

    $('#time_zone_offset').val(x.getTimezoneOffset());
    $('#utc_timestamp').val(UTC);
     console.log(UTC);
    console.log(x.getTimezoneOffset());
    /**
     const now = new Date();
     console.log(now.getHours());
     console.log(now.getUTCHours());
     console.log(now.getTimezoneOffset());
     **/

    //var tt = new Date();
    //console.log(Math.floor((tt.getTime() - tt.getTimezoneOffset()*60*1000)))
});