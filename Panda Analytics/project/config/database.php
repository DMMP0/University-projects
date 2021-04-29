<?php
class Database
{
    private $host = "localhost";
    private $db_name = "FLOW_DB";
    private $username = "root";
    private $password = "";
    public $conn;
 
    public function getConnection()
	{
 
        $this->conn = null;
 
        try{
				$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password,array(PDO::ATTR_PERSISTENT => true));
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
		catch(PDOException $e)
			{
				error_log('PDOException - ' . $e->getMessage(), 0);
				http_response_code(500);
				die('Internal Server Error');
			}
 
        return $this->conn;
    }
}
