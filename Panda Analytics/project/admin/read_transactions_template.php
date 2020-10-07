<?php
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";


if($num1>0 || $num2 > 0)
	{
		echo "<table class='table table-hover table-responsive table-bordered'>";
 
		// table headers
		echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Tipo</th>";
        echo "<th>Macroarea</th>";
		echo "<th>Ammontare</th>";
		echo "<th>Anno</th>";
		echo "<th>Ripetizioni annuali</th>";
		echo "<th>Mese</th>";
		echo "<th>Data precisa</th>";
		echo "<th>Flow</th>";
		echo "<th>Id Flow</th>";
		
		
		echo "</tr>";
		
		while ($row = $stmt1->fetch(PDO::FETCH_ASSOC))
			{
			//	var_dump($row);
				extract($row);
			
				echo "<tr>";
				echo "<td>{$id}</td>";
				
				echo "<td>".fromCodeToString($tipo)."</td>";
				if($macroarea)
					echo "<td>Impieghi</td>";
				else
					echo "<td>Fonti</td>";
				echo "<td>{$amount}</td>";
				echo "<td>{$anno}</td>";
				echo "<td>{$ripetizioni_annuali}</td>";
				
					echo "<td> / </td>";
				
					echo "<td>{$T_DATE}</td>";
				
				
				echo "<td>{$nome}</td>";
				echo "<td>{$id_flow}</td>";
				echo "<td><a href='{$home_url}admin/edit_transaction.php?ra=$ripetizioni_annuali&m=/&id=$id&amm=$amount&y=$anno&dp=$T_DATE&fid=$id_flow'>Edit </a></td>
					<td><a href='{$home_url}admin/read_flows.php?action=delete_flow&id=$id&fid=$id_flow'>Delete</a></td>";
				echo "</tr>";
			}
		while ($row = $stmt2->fetch(PDO::FETCH_ASSOC))
			{
			//	var_dump($row);
				extract($row);
			
				echo "<tr>";
				echo "<td>{$id}</td>";
				
				echo "<td>".fromCodeToString($tipo)."</td>";
				if($macroarea)
					echo "<td>Impieghi</td>";
				else
					echo "<td>Fonti</td>";
				echo "<td>{$amount}</td>";
				echo "<td>{$anno}</td>";
				echo "<td>{$ripetizioni_annuali}</td>";
				//if(isset($T_MESE))
					echo "<td>{$T_MESE}</td>";
				/*else
					echo "<td> / </td>";*/
				/*if(isset($T_DATE))
					echo "<td>{$T_DATE}</td>";
				else*/
					echo "<td> / </td>";
				
				echo "<td>{$nome}</td>";
				echo "<td>{$id_flow}</td>";
				echo "<td><a href='{$home_url}admin/edit_transaction.php?id=$id&amm=$amount&y=$anno$&m=$T_MESE&fid=$id_flow'>Edit </a></td>
					<td><a href='{$home_url}admin/read_transactions.php?action=delete_transaction&id=$id&fid=$id_flow'>Delete</a></td>";
				
				echo "</tr>";
			}
		
		echo "</table>";
		$page_url="read_transactions.php?";
		if(isset($action))
			$page_url.="action=$action&";
		if(isset($fid))
			$page_url.="id=$fid";
		
			
		$total_rows = $transactionP->countAll();
		$total_rows += $transactionB->countAll();
 
		include_once 'paging.php';
	}
else
	{
		echo "<div class='alert alert-danger'>
        <strong>No Transactions found.</strong>
		</div>";
	}
?>