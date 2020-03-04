<?php

include "Crud.php";
include "Authenticator.php";

class User implements Crud, Authenticator
{
    private $user_id;
    private $first_name;
    private $last_name;
    private $city_name;
    private $username;
    private $password;
    private $utc_timestamp;
    private $offset;


    public function __construct($first_name, $last_name, $city_name, $username, $password, $utc_timestamp, $offset)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->city_name = $city_name;
        $this->username = $username;
        $this->password = $password;
        $this->utc_timestamp = $utc_timestamp;
        $this->offset = $offset;
    }

    /**
     * Static constructor
     */
    public static function create()
    {

        $instance = new self($first_name, $last_name, $city, $username = NULL, $password = NULL, $utc_timestamp = time(), $offset = static::getTimezoneDifference(time()));

        return $instance;
    }

    /**
     * Username Setter
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Username Getter
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Password Setter
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Password Getter
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set User ID
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Get User ID
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    public function save()
    {
        $fn = $this->first_name;
        $ln = $this->last_name;
        $city = $this->city_name;
        $uname = $this->username;
        $this->hashPassword();
        $pass = $this->password;
        $utc_timestamp = date("Y-m-d H:i:s", $this->utc_timestamp);
        $offset = $this->offset;

        #$userQuery = "INSERT INTO user (first_name,last_name,user_city,username,password) VALUES ('$fn','$ln','$city','$uname','$pass')";
        $userValues = ["first_name" => $fn, "last_name" => $ln, "user_city" => $city, "username" => $uname, "password" => $pass, "utc_timestamp" => $utc_timestamp, "tzoffset" => $offset];
        return $userValues;
    }

    public static function readAll()
    {
        $con = new DBConnector;

        $res = mysqli_query($con->conn, "SELECT * FROM user")
        or die("Error");

        $table = null;
        if (mysqli_num_rows($res) > 0) {
            $table = "<table class='user-list'>";
            $table .= "<thead><td>#</td><td>First Name</td><td>Last Name</td><td>City</td></thead>";
            $table .= "<tbody>";
            while ($row = mysqli_fetch_assoc($res)) {
                $table .= "<tr>";
                $table .= "<td>";
                $table .= "<p>" . $row["id"] . "</p>";
                $table .= "</td>";
                $table .= "<td>";
                $table .= "<p>" . $row["first_name"] . "</p>";
                $table .= "</td>";
                $table .= "<td>";
                $table .= "<p>" . $row["last_name"] . "</p>";
                $table .= "</td>";
                $table .= "<td>";
                $table .= "<p>" . $row["user_city"] . "</p>";
                $table .= "</td>";
                $table .= "</tr>";
            }
            $table .= "</tbody>";
            $table .= "</table>";
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

    public function validateForm()
    {
        //Return true if values are not empty
        $fn = $this->first_name;
        $ln = $this->last_name;
        $city = $this->city_name;

        if ($fn == "" || $ln == "" || $city == "") {
            return false;
        }
        return true;
    }

    /**
     * Set errors if username is already there
     */

    public function createFormErrorSessions()
    {
        session_start();
        $_SESSION['form_errors'] = "All fields are required";
    }

    /**
     * Calculate password hash
     */
    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }

    /**
     * Check if password is correct
     */
    public function isPasswordCorrect()
    {
        $con = new DBConnector;
        $found = FALSE;

        $res = mysqli_query($con->conn, "SELECT username,password FROM user") or die("Error");

        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            if (password_verify($this->getPassword(), $row["password"]) && $this->getUsername() == $row["username"]) {
                $found = TRUE;
            }
        }
        $con->closeDatabase();
        return $found;
    }

    /**
     * Login User
     */
    public function login()
    {
        if ($this->isPasswordCorrect()) {
            header("Location:private_page.php");
        }
    }

    /**
     * Start User session
     */
    public function createUserSession()
    {
        session_start();
        $_SESSION['username'] = $this->getUsername();
    }

    /**
     * Logout User
     */
    public function logout()
    {
        session_start();
        unset($_SESSION['username']);
        session_destroy();
        header("Location:lab1.php");
    }

    /**
     * Logout User
     */
    public function isUserExist()
    {

        $con = new DBConnector;

        $usernameFound = FALSE;

        $res = mysqli_query($con->conn, "SELECT username FROM user") or die("Error");

        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            if ($this->getUsername() == $row["username"]) {
                $usernameFound = TRUE;
            }
        }

        $con->closeDatabase();

        return $usernameFound;
    }

    /**
     * Set errors if username is already there
     */

    public function createUsernameErrorSessions()
    {
        session_start();
        $_SESSION['form_errors'] = "The selected username exists, choose a different name";
    }

    /**
     * @param $time
     * @return mixed
     */
    static function getTimezoneDifference($time)
    {
        date_default_timezone_set( "UTC" );
        //$ip = $_SERVER['REMOTE_ADDR']; use this on a real server.
        $ip = '197.232.37.10';
        $json = file_get_contents('http://ip-api.com/json/' . $ip);
        $ipData = json_decode($json, true);
        $timezone = new DateTimeZone($ipData['timezone']);
        var_dump($timezone);
        var_dump(new DateTime());
        $tzOffset = timezone_offset_get($timezone,new DateTime());
        $offset = $tzOffset/60;

        return $offset;
    }
}
