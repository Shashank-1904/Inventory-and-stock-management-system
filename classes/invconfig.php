<?php
class Config
{
    private $host, $user, $pass, $dbName;
    public $connect;

    function __construct()
    {
        $this->host = "localhost";
        $this->user = "root";
        $this->pass = "";
        $this->dbName = "expandx_internship_project";

        $this->connect = mysqli_connect($this->host, $this->user, $this->pass, $this->dbName);
    }

    public function close()
    {
        if ($this->connect) {
            mysqli_close($this->connect);
        }
    }
}
