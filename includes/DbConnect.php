<?php
class DbConnect
{
    private $con;

    function __construct()
    {
    }

    function connect()
    {
        include_once dirname(__FILE__) . '/Constants.php';
        $this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$this->con->set_charset("utf8mb4");
        if (mysqli_connect_errno()) {
            echo "Nie udało się połączyć z bazą danych" . mysqli_connect_error();
        }
        return $this->con;
    }
}
