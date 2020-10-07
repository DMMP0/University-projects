<?php
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";

abstract class Transaction
{
	

 
    // object properties
    public $id;
    public $tipo;
    public $amount;
	public $macroarea;
	public $anno;
	public $ripetizioni_annuali;
	public $flow_id;
	public $joinString;
 
	abstract function InsertSpecific();

	
	function create()
	{
	
		$this->tipo=test_input($this->tipo);
		$this->amount=test_input($this->amount);
		$this->macroarea=test_input($this->macroarea);
		$this->anno=test_input($this->anno);
		$this->ripetizioni_annuali=test_input($this->ripetizioni_annuali);
	
	

	  
		// var_dump($this);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      

     
		$q = "INSERT INTO ". $this.table_name." VALUES(null,:tipo,:amount,:macroarea,:anno,:ripetizioni_annuali);";
		// var_dump($q);
		$stmt = $this->conn->prepare($query);
	
 
		$stmt->bindParam(':tipo', $this->tipo);
		$stmt->bindParam(':amount', $this->amount);
		$stmt->bindParam(':macroarea', $this->macroarea);
		$stmt->bindParam(':anno', $this->anno);
		$stmt->bindParam(':ripetizioni_annuali', $this->ripetizioni_annuali);
		
		if(InsertSpecific())
		{
			if($stmt->execute())
			{
				return true;
			}
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
	
	public function del($ID)
	{
		//TBOH
		$q = "DELETE FROM TRANSAZIONI WHERE id = :id";
		// var_dump($q);
		$stmt = $this->conn->prepare($q);
		$stmt->bindParam(':id', $ID);
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
	
	public function update($id,$tipo,$macro,$amm,$y,$ra,$m,$fid,$flag)
	{
		$query = "UPDATE TRANSAZIONI SET tipo=:tipo,amount=:amm,macroarea=:macro,anno=:y,ripetizioni_annuali=:ra,id_flow=:fid WHERE id = :id";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':amm', $amm);
		$stmt->bindParam(':y', $y);
		$stmt->bindParam(':ra', $ra);
		$stmt->bindParam(':fid', $fid);
		$stmt->bindParam(':tipo', $tipo);
		$stmt->bindParam(':macro', $macro);
		$stmt->execute();
		if($flag)
		{
			$query = "UPDATE TPRECISA SET T_DATE=:m WHERE ID_TRANSAZIONE=:id";
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(':id', $id);
			$stmt->bindParam(':m', $m);
			
			$stmt->execute();
		}
		else
		{
			$query = "UPDATE TBOH SET T_MESE=:m WHERE ID_TRANSAZIONE=:id";
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(':id', $id);
			$stmt->bindParam(':m', $m);
			
			$stmt->execute();
		}
		   
	}
	
	//legge tutti i record
	function readAll($from_record_num, $records_per_page)
	{
		//limit per la pagina
		$query = "SELECT
                t.id,
                t.tipo,
                t.amount, 
                t.macroarea,
				t.anno,
				t.ripetizioni_annuali,
				f.nome,
				t.id_flow
				". $this->joinString. "INNER JOIN FLOW AS f ON f.id = t.ID_FLOW
            ORDER BY t.id DESC
            LIMIT ?, ?";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
		//var_dump($stmt);
		$stmt->execute();
		return $stmt;
	}
	
	function readAllID($from_record_num, $records_per_page,$ID)
	{
		//limit per la pagina
		$query = "SELECT
                t.id,
                t.tipo,
                t.amount, 
                t.macroarea,
				t.anno,
				t.ripetizioni_annuali,
				f.nome,
				t.id_flow
				". $this->joinString. " INNER JOIN FLOW AS f ON f.id = t.ID_FLOW
			WHERE t.ID_FLOW=?
            ORDER BY t.id DESC
            LIMIT ?, ?";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $ID, PDO::PARAM_INT);
		$stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);
		//var_dump($stmt);
		$stmt->execute();
		return $stmt;
	}
	
	public function countAll()
	{
		$query = "SELECT id_transazione FROM " . $this->table_name . "";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$num = $stmt->rowCount();
		return $num;
	}
	
	
}

class TPrecisa extends Transaction
{
	public $data;
	 // database connection and table name
    public $conn;
    public $table_name;
	
	
	public function __construct($db)
	{
        $this->conn = $db;
		$this->table_name =  "TPRECISA";
		$this->joinString=",p.T_DATE FROM TRANSAZIONI AS t INNER JOIN TPRECISA as p ON t.id = p.ID_TRANSAZIONE ";
    }
	
	function InsertSpecific()
	{
		$q = "INSERT INTO ". $this->table_name." VALUES(null,:data,:id;";
		// var_dump($q);
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':data', $this->data);
		$stmt->bindParam(':id', $this->id);
		if($stmt->execute())
			return true;
		else
		{
			showError($stmt);
			return false;
		}
	}
	
	
}

class TBoh extends Transaction
{
	public $mese;
	 // database connection and table name
    public $conn;
    public $table_name;
	
	
	public function __construct($db)
	{
        $this->conn = $db;
		$this->table_name =  "TBOH";
		$this->joinString = ",b.T_MESE FROM TRANSAZIONI AS t INNER JOIN TBOH as b ON t.id = b.ID_TRANSAZIONE ";
    }
	
	 function InsertSpecific()
	{
		$q = "INSERT INTO ". $this->table_name." VALUES(null,:mese,:id;";
		// var_dump($q);
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':mese', $this->mese);
		$stmt->bindParam(':id', $this->id);
		if($stmt->execute())
			return true;
		else
		{
			showError($stmt);
			return false;
		}
	}
	
}



?>