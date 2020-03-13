<?php

include_once 'DBConnector.php';

class ApiHandler
{
    private $meal_name;
    private $meal_units;
    private $unit_price;
    private $status;
    private $user_api_key;


    public function setMealName($meal_name)
    {
        $this->meal_name = $meal_name;
    }

    public function getMealName()
    {
        return $this->meal_name;
    }

    public function setMealUnits($meal_units)
    {
        $this->meal_units = $meal_units;
    }

    public function getMealUnits()
    {
        return $this->meal_units;
    }

    public function setUnitPrice($unit_price)
    {
        $this->unit_price = $unit_price;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setUserApiKey($user_api_key)
    {
        $this->user_api_key = $user_api_key;
    }

    public function getUserApiKey()
    {
        return $this->user_api_key;
    }

    public function createOrder()
    {
        $con = new DBConnector();

        $res = mysqli_query($con->conn, "INSERT into orders (order_name,units,unit_price,order_status) 
                                                    VALUES ('$this->meal_name','$this->meal_units','$this->unit_price', '$this->status')")
        or die("Error: " . mysql_errro());

        return $res;
    }

    public function checkOrderStatus($order_id)
    {
        $con = new DBConnector();

        $query = "SELECT order_status FROM orders WHERE order_id=" . $order_id;

        $res = mysqli_query($con->conn, $query) or die("Error");

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $message = $row['order_status'];
            }
        } else {
            $message = "No record exists by that id";
        }

        return $message;
    }

    public function fetchAllOrders()
    {

    }

    /**
     * Check if user has valid api key
     * @return bool
     */
    public function checkApiKey(): bool
    {

        $authorization = explode("Basic", $this->getUserApiKey());
        $key = trim($authorization[1]);
        $con = new DBConnector;

        $query = mysqli_query($con->conn, 'SELECT api_key FROM api_keys WHERE api_key = "' . $key . '"') or die("Error");

        if (mysqli_num_rows($query) > 0) {
            $found = TRUE;
        } else {
            $found = FALSE;
        }

        $con->closeDatabase();

        return $found;
    }


}