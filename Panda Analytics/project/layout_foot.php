 <?php
if($page_title=='Flow Display')
	echo	"	<div class='footer' id=\"NavbarBottom\">
 			<div class=\"btn-group btn-group-justified\">
			<button><a href=\"storico/SP.php?action=1\" class\"btn btn-primary\">Aggiungi Stato patrimoniale</a></button>
			<button><a href=\"storico/DA.php?action=1\" class\"btn btn-primary\">Aggiungi Anno corrente</a></button>
			<button><a href=\"storico/viewAll.php\" class\"btn btn-primary\">Consulta e modifica Stato patrimoniale</a></button>
			<button><a href=\"storico/viewY.php\" class\"btn btn-primary\">Consulta e modifica Anno corrente</a></button>
			</div>
		</div>

   </div>

   
	<!-- /container -->";
	
if	(isset($y)&&$page_title=="Modifica storico $y")
{
	echo "
	</form></div>
	<div class='footer'>
		 <input class='normal-button' value='salva' type=\"submit\">
		 </div>";
}

 ?>
<!-- jQuery library -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<!-- end HTML page -->
</body>
</html>
