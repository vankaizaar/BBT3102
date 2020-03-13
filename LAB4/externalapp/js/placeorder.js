$(document).ready(function () {
    $('#btn-place-order').click(function (event) {
        event.preventDefault();

        var name_of_food = $('#name_of_food').val();
        var number_of_units = $('#number_of_units').val();
        var unit_price = $('#unit_price').val();
        var order_status = $('#status').val();

        $.ajax({
            url: "../api/v1/orders/index.php",
            method: "POST",
            dataType: 'JSON',
            type: 'post',
            data: {
                name_of_food: name_of_food,
                number_of_units: number_of_units,
                unit_price: unit_price,
                order_status: order_status
            },
            headers: {
                'Authorization': 'Basic d50e7571ed86485841a8f597c9f2dff0e8c98a40717e843ef44e630bb8da69c4'
            }
        })
            .done(function (data) {
                alert(data['message']);
                console.log(data);
                $('#order_form')[0].reset();
            })
            .fail(function (jqXHR, textStatus) {
                alert("Something went wrong, Please try again");
                console.log("Request failed: " + textStatus);
                $('#order_form')[0].reset();
            });

    });
    $('#btn-check-order').click(function (event) {
        event.preventDefault();

        var order_id = $('#order_id').val();

        $.ajax({
            url: "../api/v1/orders/index.php",
            method: "GET",
            dataType: 'JSON',
            type: 'get',
            data: {
                order_id: order_id
            },
            headers: {
                'Authorization': 'Basic d50e7571ed86485841a8f597c9f2dff0e8c98a40717e843ef44e630bb8da69c4'
            }
        })
            .done(function (data) {
                alert(data['message']);
                console.log(data);
                $('#order_status_form')[0].reset();
            })
            .fail(function (jqXHR, textStatus) {
                alert("Something went wrong, Please try again");
                console.log("Request failed: " + textStatus);
                $('#order_status_form')[0].reset();
            });

    });
});