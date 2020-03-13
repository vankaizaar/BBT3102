<?php

include_once 'apiHandler.php';
include_once 'DBConnector.php';

$api = new ApiHandler();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $api_key_correct = false;
    $headers = apache_request_headers();
    $header_api_key = $headers['Authorization'];
    $api->setUserApiKey($header_api_key);

    $api_key_correct = $api->checkApiKey();


    if ($api_key_correct) {
        $name_of_food = $_POST['name_of_food'];
        $number_of_units = $_POST['number_of_units'];
        $unit_price = $_POST['unit_price'];
        $order_status = $_POST['order_status'];

        $con = new DBConnector();

        $api->setMealName($name_of_food);
        $api->setMealUnits($number_of_units);
        $api->setUnitPrice($unit_price);
        $api->setStatus($order_status);

        $res = $api->createOrder();

        if ($res) {
            $response_array = ['success' => 1, 'message' => 'Order has been placed'];
            header('Content-type: application/json');
            echo json_encode($response_array);
        } else {
            $response_array = ['success' => 0, 'message' => 'Wrong API Key'];
            header('Content-type: application/json');
            echo json_encode($response_array);
        }


    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $api_key_correct = false;
    $headers = apache_request_headers();
    $header_api_key = $headers['Authorization'];
    $api->setUserApiKey($header_api_key);

    $api_key_correct = $api->checkApiKey();


    if ($api_key_correct) {
        $order_id = $_GET['order_id'];

        $res = $api->checkOrderStatus($order_id);

        if ($res) {
            $response_array = ['success' => 1, 'message' => $res];
            header('Content-type: application/json');
            echo json_encode($response_array);
        } else {
            $response_array = ['success' => 0, 'message' => $res];
            header('Content-type: application/json');
            echo json_encode($response_array);
        }

    }
} else {
    $response_array = ['success' => 0, 'message' => 'There was an error processing your request'];
    header('Content-type: application/json');
    echo json_encode($response_array);
}