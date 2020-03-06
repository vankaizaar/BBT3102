$(document).ready(function () {
    //confirm generation of api key
    $('#api-key-btn').click(function (event) {
        var confirm_key = confirm("You are about to generate a new API key");
        if (!confirm_key) {
            return;
        }
        $.ajax({
                url: "Apikey.php",
                method: "POST",
                dataType: 'JSON'
            })
            .done(function (data) {
                $('#api_key').val(data.key);
                console.log(data);
            })
            .fail(function (jqXHR, textStatus) {
                alert("Something went wrong, Please try again");
                console.log("Request failed: " + textStatus);
            });
    });
});