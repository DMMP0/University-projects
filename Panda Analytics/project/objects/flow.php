<?php
//include_once "objects/transaction.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";

class Flow
{
	 // database connection and table name
    public $conn;
    private $table_name = "FLOW";
 
    // object properties
    public $id;
    public $name;
	public $status;
	public $dmi;
    public $user_id;
	public $fondazione;

 
    // costruttore
    public function __construct($db)
	{
        $this->conn = $db;
    }
	
	function create()
	{
	
		
		$this->name=test_input($this->name);

		// var_dump($this);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
		$q = "INSERT INTO ". $this->table_name." VALUES(null,:name,:dmi,:status,:fondazione,:uid);";
		// var_dump($q);
		$stmt = $this->conn->prepare($q);
	
 
		$stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':dmi', $this->dmi);
		$stmt->bindParam(':status', $this->status);
		$stmt->bindParam(':fondazione', $this->fondazione);
		$stmt->bindParam(':uid', $this->user_id);
		
		if($stmt->execute())
		{
			$this->id=$this->conn->lastInsertId();
				
			return true;
		}	
		else
		{	
			showError($stmt);
		  	return false;
	   	}
	}
	
	function get($id)
	{
		$query = "SELECT * FROM ".$this->table_name." WHERE id = ?";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt;
	}
	function getFromUsername($username)
	{
		$query = "SELECT f.id,f.nome,fondazione FROM FLOW as f INNER JOIN UTENTE as u ON f.ID_UTENTE=u.id WHERE u.nome='$username'";
		$stmt = $this->conn->prepare( $query );
		//$stmt->bindParam(1, $username, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt;
	}
	function update($id,$nome,$user,$dmi,$pado,$f)
	{
		$query = "UPDATE FLOW SET nome=:name,id_utente=:uid,dmi=:dmi,status=:pado, fondazione=:f WHERE id = :id";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':name', $nome);
		$stmt->bindParam(':uid', $user);
		$stmt->bindParam(':pado', $pado);
		$stmt->bindParam(':dmi', $dmi);
		$stmt->bindParam(':f', $f);
		if($stmt->execute())
		{
			return true;
		}	
		else
		{	
			showError($stmt);
		  	return false;
	   	}	  
	}
	
	public function NuclearOption($flowID)
	{
		//TBOH
		$q = "DELETE FROM ". $this->table_name." WHERE id = :id";
		// var_dump($q);
		$stmt = $this->conn->prepare($q);
		$stmt->bindParam(':id', $flowID);
		if($stmt->execute())
		{
			return true;
		}	
		else
		{	
			showError($stmt);
		  	return false;
	   	}	  
	}
	
	public function showError($stmt)
	{
		echo "<pre>";
        print_r($stmt->errorInfo());
		echo "</pre>";
	}
	
	//legge tutti i record
	function readAll($from_record_num, $records_per_page)
	{
		//limit per la pagina
		$query = "SELECT
                id,nome,dmi,status,id_utente
               
				FROM ".$this->table_name." 
            ORDER BY id DESC
            LIMIT ?, ?";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt;
	}
	
	function readAllID($from_record_num, $records_per_page,$ID)
	{
		//limit per la pagina
		$query = "SELECT id,nome,dmi,status,fondazione,id_utente
				FROM FLOW
				WHERE ID_UTENTE = ?
            ORDER BY id DESC
            LIMIT ?, ?";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $ID, PDO::PARAM_INT);
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
        return $stmt->rowCount();
	}
	
	function readAllPTransactions()
	{
		//limit per la pagina
		$query = "SELECT
                t.id,
                t.tipo,
                t.amount, 
                t.macroarea,
				t.anno,
				p.T_DATE
				FROM TRANSAZIONi AS t INNER JOIN TPRECISA AS p ON t.id = p.ID_TRANSAZIONE
				WHERE t.FLOW_ID = ?
            ORDER BY t.id DESC
            LIMIT ?, ?";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id, PDO::PARAM_INT);
		$stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt;
	}
	function readAllBTransactions()
	{
		//limit per la pagina
		$query = "SELECT
                t.id,
                t.tipo,
                t.amount, 
                t.macroarea,
				t.anno,
				b.T_MESE
				FROM TRANSAZIONi AS t INNER JOIN TBOH AS b ON b.id = p.ID_TRANSAZIONE
				WHERE t.FLOW_ID = ?
            ORDER BY t.id DESC
            LIMIT ?, ?";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id, PDO::PARAM_INT);
		$stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt;
	}
	
	
	
}
