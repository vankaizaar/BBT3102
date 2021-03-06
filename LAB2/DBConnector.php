<?php
define('DB_SERVER', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'btc3205');

class DBConnector
{
    public $conn;

    public function __construct()
    {
        /**
         * mysql_connect function has been deprecated and removed from PHP v7.0.0.
         * https://www.php.net/manual/en/function.mysql-connect.php
         *
         */
        $this->conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die ("Could not connect to mysql");
    }


    public function closeDatabase()
    {
        mysqli_close($this->conn);
    }
}
