<?php
if($num>0)
	{
		echo "<table class='table table-hover table-responsive table-bordered'>";
 
		// table headers
		echo "<tr>";
        echo "<th>Username</th>";
        echo "<th>Email</th>";
        echo "<th>Access Level</th>";
		echo "<th>Status</th>";
		echo "<th>Flows</th>";
		echo "</tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				extract($row);
			
				echo "<tr>";
				echo "<td>{$nome}</td>";
				echo "<td>{$mail}</td>";
				echo "<td>{$access_level}</td>";
				if($status)
				{
					echo "<td>Confermato</td>";
				}
				else
				{
					echo "<td>Non confermato</td>";
				}
				echo "<td><a href='{$home_url}admin/read_flows.php?action=read_from_user&id=$id'>Flows</a></td>";
				echo "<td><a href='{$home_url}admin/read_users.php?action=delete_user&uid=$id'>Delete</a></td>";
				
				echo "</tr>";
			}
 
		echo "</table>";
 
		$page_url="read_users.php?";
		$total_rows = $user->countAll();
 
		include_once 'paging.php';
	}
else
	{
		echo "<div class='alert alert-danger'>
        <strong>No users found.</strong>
		</div>";
	}
