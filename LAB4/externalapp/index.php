<?php

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link rel="stylesheet" href="vendors/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-dark">

<div class="container pt-4">
    <div class="row justify-content-center mb-4">
        <div class="col-6">

        </div>
    </div>
</div>

<div class="container pt-4">
    <div class="row justify-content-center mb-4">
        <div class="col-6">
            <h1 class="text-light">Placing an Order</h1>
            <form name="order_form" id="order_form">
                <div class="form-group">
                    <label for="name_of_food" class="text-light">Food Name</label>
                    <input type="text" class="form-control" id="name_of_food" name="name_of_food"
                           placeholder="Name of food" required>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="number_of_units" class="text-light">Number of units</label>
                            <input type="number" class="form-control" id="number_of_units" name="number_of_units"
                                   placeholder="Number of units" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="unit_price" class="text-light">Price per unit</label>
                            <input type="number" class="form-control" id="unit_price" name="unit_price"
                                   placeholder="Unit price"  min="1" max="9.99" step="0.1" required>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="status" id="status" value="order_placed" readonly>

                <button type="submit" class="btn btn-primary" id="btn-place-order">Submit</button>
            </form>
        </div>
        <div class="col-6">
            <h1 class="text-light">Checking Order Status</h1>
            <form name="order_status_form" id="order_status_form" method="POST" action="<?=$_SERVER['PHP_SELF'] ?>">
                <div class="form-group">
                    <label for="number_of_units" class="text-light">Check order status</label>
                    <input type="number" class="form-control" id="order_id" name="order_id"
                           placeholder="Order ID" required>
                </div>
                <button type="submit" class="btn btn-primary" id="btn-check-order">Check Order Status</button>
            </form>
        </div>
    </div>
</div>

<script src="vendors/jquery/jquery-3.4.1.min.js"></script>
<script src="vendors/popper/popper.min.js"></script>
<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="js/placeorder.js"></script>
</body>
</html>
