<?php
if($num>0)
	{
		
		
		echo "<table class='table table-hover table-responsive table-bordered'>";
 
		// table headers
		echo "<tr>";
		echo "<th>Name</th>";
		echo "<th>Disponibilità monetaria iniziale</th>";
		echo "<th>Primo anno?</th>";
		echo "<th>User ID</th>";
		
		echo "<th>Transactions</th>";
		echo "</tr>";

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				extract($row);
				//var_dump($row);
				echo "<tr>";
				echo "<td>{$nome}</td>";
				echo "<td>{$dmi}</td>";
				echo "<td>{$status}</td>";
				echo "<td>{$id_utente}</td>";			
				echo "<td><a href='{$home_url}admin/read_transactions.php?action=read_from_flow&id=$id'>Transazioni </a></td>";
				echo "<td><a href='{$home_url}admin/edit_flow.php?id=$id&uid=$id_utente&n=$nome'>Edit </a></td>
				<td><a href='{$home_url}admin/read_flows.php?action=delete_flow&id=$id&uid=$id_utente'>Delete</a></td>";

				
				echo "</tr>";
				
			}
 
		echo "</table>";
 
		$page_url="read_flows.php?";
		$total_rows = $flow->countAll();
 
		include_once 'paging.php';
		
	}
else
	{
		echo "<div class='alert alert-danger'>
        <strong>No flows found.</strong>
		</div>";
	}
	
