<?php

include "Crud.php";

class User implements Crud
{
    private $user_id;
    private $first_name;
    private $last_name;
    private $city_name;
    

    public function __construct($first_name, $last_name, $city_name)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->city_name = $city_name;
    }
    

    public function setUserId()
    {
        $this->user_id = $user_id;
    }


    public function getUserId()
    {
        return $this->$user_id;
    }

    public function save()
    {
        $fn = $this->first_name;
        $ln = $this->last_name;
        $city = $this->city_name;    
        
        $con = new DBConnector;
            
        $res = mysqli_query($con->conn, "INSERT INTO user (first_name,last_name,user_city) VALUES ('$fn','$ln','$city')")
            or die("Error");

        return $res;
        ;
    }

    public static function readAll()
    {
        $con = new DBConnector;        

        $res = mysqli_query($con->conn, "SELECT * FROM user")
            or die("Error");

        $table = null;
        if (mysqli_num_rows($res) > 0) {               
                $table ="<table>";
                $table .="<thead><td>#</td><td>First Name</td><td>Last Name</td><td>City</td></thead>";
                while($row = mysqli_fetch_assoc($res)) {
                    $table.="<tr>";
                    $table.="<td>";
                    $table.="<p>" . $row["id"]."</p>";
                    $table.="</td>";
                    $table.="<td>";
                    $table.="<p>" . $row["first_name"]."</p>";
                    $table.="</td>";
                    $table.="<td>";
                    $table.="<p>" . $row["last_name"]."</p>";
                    $table.="</td>";
                    $table.="<td>";
                    $table.="<p>" . $row["user_city"]."</p>";
                    $table.="</td>";
                    $table.="</tr>";
                }
                $table .="</table>";
            } else {
                $table = "There are no users";
            }                     

        $con->closeDatabase();
        return $table;
    } 
    public function readUnique()
    {
        return null;
    }
    public function search()
    {
        return null;
    }
    public function update()
    {
        return null;
    }
    public function removeOne()
    {
        return null;
    }
    public function removeAll()
    {
        return null;
    }
}
