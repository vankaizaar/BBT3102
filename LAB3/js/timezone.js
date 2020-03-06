$(document).ready(function () {
    let utcTimestamp = Math.floor(Date.now()/1000);
    let clientTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    console.log(utcTimestamp);
    console.log(clientTimeZone);
    $('#time_zone_offset').val(clientTimeZone);
    $('#utc_timestamp').val(utcTimestamp);
});

/**
 Rewrote function so that timestamp and user timezone e.g "Africa/Nairobi" is sent in case js is available. So that only PHP computes time difference.

 Reason being js computes the offset differently . e.g.

 For Nairobi it will state -180 mins(from the users perspective) while if js is off PHP will compute 180(from GMT perspective). hence to standardize this we compute from GMT as opposed from client side.

 **/