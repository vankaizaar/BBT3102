<?php
session_start();
include 'DBConnector.php';
if (!isset($_SESSION['username'])) {
    header("Location:login.php");
}

function fetchUserApiKey(){
    $con = new DBConnector;
    $APIkey = NULL;
    $query = mysqli_query($con->conn, 'SELECT api_key  FROM api_keys WHERE user_id=(SELECT id FROM user WHERE username = "' . $_SESSION['username'] . '")') or die("Error");

    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $APIkey = $row['api_key'];
    }

    $con->closeDatabase();

    return $APIkey;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Private Page</title>
    <link rel="stylesheet" href="vendors/bootstrap/css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="style.css">-->
</head>

<body class="bg-dark">
    <div class="container pt-4">
        <div class="row mb-4">
            <div class="col-sm">
                <div class="float-right d-inline-block">
                    <a href="logout.php" class="btn btn-primary float-right ">Logout</a>
                </div>
            </div>
        </div>
        <div class="row my-5">
            <div class="col-sm">
                <h4 class="text-light">Click the button to generate an API key</h4>
                <button class="btn btn-group-lg btn-success" id="api-key-btn">Generate API Key</button>
            </div>
        </div>
        <div class="row my-5">
            <div class="col-sm">
                <h5 class="text-light">Your API Key:</h5>
                <textarea rows="3" class="form-control" id="api_key" name="api_key" readonly><?php echo fetchUserApiKey()?></textarea>
                <p class="text-light"><strong>NB: </strong> If your API key is already in use by already running applications, generating a new key will stop the application from functioning.</p>
            </div>
        </div>
        <div class="row my-5">
            <div class="col-sm">
                <h4 class="text-light">Service Description</h4>
                <p class="text-light">We have a service/API that allows external applications to order food and also pull all order status by using order id. </p>
            </div>
        </div>
    </div>
    <script src="vendors/jquery/jquery-3.4.1.min.js"></script>
    <script src="vendors/popper/popper.min.js"></script>
    <script src="vendors/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/apikey.js"></script>
</body>

</html>