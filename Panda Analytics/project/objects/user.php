<?php
// 'user' object
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/pbkdf2.php";
class User
{
 
    // database connection and table name
    public $conn;
    private $table_name = "UTENTE";
 
    // object properties
    public $id;
    public $name;
    public $salt;
    public $email;
    public $password;
    public $access_level;
	public $status;
	public $access_code;
 
    // costruttore
    public function __construct($db)
	{
        $this->conn = $db;
    }
	
	// controlla se la mail esiste già
	function emailExists()
	{
 
		$query = "SELECT id, nome, salt, access_level, password
            FROM " . $this->table_name . "
            WHERE mail = ?
            LIMIT 0,1";
		$stmt = $this->conn->prepare( $query );
		$this->email=test_input($this->email);
		$stmt->bindParam(1, $this->email);
		$stmt->execute();
 
		$num = $stmt->rowCount();
 
		// se esiste, assegna il valore alla property
		if($num>0)
		{
 
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
 
		
			$this->id = $row['id'];
			$this->name = $row['nome'];
			$this->access_level = $row['access_level'];
			$this->password = $row['password'];
			$this->salt = $row['salt'];
 
		
			return true;
		}
 
		
		return false;
	}
	
	function nameExists()
	{
 
		$query = "SELECT id, nome, salt, access_level, password
            FROM " . $this->table_name . "
            WHERE mail = ?
            LIMIT 0,1";
		$stmt = $this->conn->prepare( $query );
		$this->name=htmlspecialchars(strip_tags($this->name));
		$stmt->bindParam(1, $this->name);
		$stmt->execute();
 
		$num = $stmt->rowCount();
 
		// se esiste, assegna il valore alla property
		if($num>0)
		{
 
		
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
 
		
			$this->id = $row['id'];
			$this->name = $row['nome'];
			$this->access_level = $row['access_level'];
			$this->password = $row['password'];
			$this->salt = $row['salt'];
 
			
			return true;
		}
 
		
		return false;
	}

	function create()
	{
 
		$salt=random_bytes(10);
		$this->salt=bin2hex($salt);
		$salt = $this->salt;
		
		$this->name=test_input($this->name);
		$this->email=test_input($this->email);
		$this->password=test_input($this->password);
		$this->access_level=test_input($this->access_level);
		$this->status=test_input($this->status);
		
		
		$this->password = pbkdf2('sha3-512' , $this->password,$salt,1000,100);
		  
		// var_dump($this);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//considerando che non sarà una query ripetuta in successione per ottimizzare si hexa concatenando invece di usare il prepared statement
		$username= bin2hex($this->name);
		$password= $this->password;
		 
		$mail= bin2hex($this->email);
		$access_level = bin2hex($this->access_level);
		$status= $this->status;
		// var_dump($salt);var_dump($password); var_dump($username);var_dump($mail);
		 
		$q = "INSERT INTO UTENTE VALUES(null,0x$username,'$salt','$password',0x$mail,0x$access_level,$status);";
		// var_dump($q);
		$this->conn->query($q);
	 
		return true;
 
	}
	public function showError($stmt)
	{
		echo "<pre>";
        print_r($stmt->errorInfo());
		echo "</pre>";
	}
	
	//legge tutti i record degli utenti
	function readAll($from_record_num, $records_per_page)
	{
		//limit per la pagina
		$query = "SELECT
                id,
                nome,
                mail, 
                access_level,status
            FROM " . $this->table_name . "
            ORDER BY id DESC
            LIMIT ?, ?";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt;
	}
	
	function readAllFLows($from_record_num, $records_per_page)
	{
		//limit per la pagina
		$query = "SELECT
                id,
                nome
				FROM FLOW AS f
				WHERE f.ID_UTENTE = ?
            ORDER BY t.id DESC
            LIMIT ?, ?";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id, PDO::PARAM_INT);
		$stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt;
	}
	
	public function countAll()
	{
		$query = "SELECT id FROM " . $this->table_name . "";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$num = $stmt->rowCount();
		return $num;
	}
	
	public function countAllFlows($username)
	{
		$query = "SELECT f.id FROM UTENTE AS u INNER JOIN FLOW AS f ON f.id_utente = u.id where u.nome = :username";
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$num = $stmt->rowCount();
		return $num;
	}
	
	// used in email verification feature
	function updateStatusByAccessCode()
	{
	
		$query = "UPDATE " . $this->table_name . "
				SET status = :status
				WHERE access_code = :access_code";
		$stmt = $this->conn->prepare($query);
		$this->status=test_input($this->status);
		$this->access_code=test_input($this->access_code);
		$stmt->bindParam(':status', $this->status);
		$stmt->bindParam(':access_code', $this->access_code);
	 
		if($stmt->execute())
		{
			return true;
		}
	 
		return false;
	}


	function updateAccessCode()
	{
		$query = "UPDATE
					" . $this->table_name . "
				SET
					access_code = :access_code
				WHERE
					email = :email";
	 
		
		$stmt = $this->conn->prepare($query);
	 
	   
		$this->access_code=test_input($this->access_code);
		$this->email=test_input($this->email);
	 
		$stmt->bindParam(':access_code', $this->access_code);
		$stmt->bindParam(':email', $this->email);
	 
		if($stmt->execute())
		{
			return true;
		}
	 
		return false;
	}


	
	function accessCodeExists()
	{
 

		$query = "SELECT id
				FROM " . $this->table_name . "
				WHERE access_code = ?
				LIMIT 0,1";
	 
		
		$stmt = $this->conn->prepare( $query );
		$this->access_code=test_input($this->access_code);
		$stmt->bindParam(1, $this->access_code);
		$stmt->execute();
		$num = $stmt->rowCount();

		if($num>0){
			return true;
		}

		return false;
 
	}
	
	function updatePassword()
	{
 

		$query = "UPDATE " . $this->table_name . "
				SET password = :password
				WHERE access_code = :access_code";
	 

		$stmt = $this->conn->prepare($query);
	 
		$this->password=test_input($this->password);
		$this->access_code=test_input($this->access_code);
	 

		$password_hash = pbkdf2('sha3-512' , $this->password,$this->salt,1000,100);
		$stmt->bindParam(':password', $password_hash);
		$stmt->bindParam(':access_code', $this->access_code);
	 
		// execute the query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	
}
?>