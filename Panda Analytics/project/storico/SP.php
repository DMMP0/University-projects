<?php
 
 //var_dump($_POST['anno']);
 
function q($t,$n)
{
	if(isset($_SESSION["query_flag"])&&$_SESSION["query_flag"])
	{
		return "update transazioni set tipo=:tipo,amount=:cifra,macroarea=$n,anno=:anno,id_flow=:flow where id=:id";
	}
	
	if(isset($_POST['anno']))
	{
		$q="select distinct anno from transazioni";
		$stmt = $t->conn->prepare($q);
		$stmt->execute();
	
		while (	($row = $stmt->fetch(PDO::FETCH_ASSOC))	)
		{
			if($row!=null)
			{
			
				extract($row);
				if($anno == $_POST['anno'])
				{
					$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=$n,anno=:anno,id_flow=:flow where id=:id";
					$_SESSION["query_flag"] = true;
					break;
				}
				else
					$q="insert into transazioni values(:id,:tipo,:cifra,$n,:anno,0,:flow)";
				
              
			}
		}
		$_SESSION["query_flag"] = false;
		return $q;
	
	}
	return -1;
}
    //Altervistaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa


if(isset($_GET['h'])&& $_GET['h']==0)
{
    header("Location: http://localhost/project/index.php?action=refresh1");
}

include_once "$_SERVER[DOCUMENT_ROOT]/project/config/core.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/database.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/objects/transaction.php";
$action = $_GET['action'];


$database = new Database();
$db = $database->getConnection();
$t = new TPrecisa($db);



switch($action)
{
	
  case '1':{$page_title="SPFonti1";break;}
  case '2':{
    
    //debug
    //var_dump($_SESSION);
    //var_dump($_POST);
    //lo invia, yee
	//questo metodo non va
	/*$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
	if($pageWasRefreshed ) 
	{
		$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";
	} else 
	{
		$q=q($t,0);
	}*/
	// var_dump($q);
	
	//impieghi
	
	
	//here you get the url behind the domain.
	$currentPage = $_SERVER['REQUEST_URI'];
	//if the session current page is not set.
	if(!isset($_SESSION['currentPage']))
	{

		//set the session to the current page.
		$_SESSION['currentPage'] = $currentPage;
	}
	//check if the session is not equal to the current page
	if($_SESSION['currentPage'] != $currentPage)
	{

		// if it's not equal you set the session again to the current page.
		$_SESSION['currentPage'] = $currentPage;

		$q=q($t,0);
	}
	else
	{
		$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";
	}
	
	
	
    
    $page_title="SPFonti2";
    $anno=$_POST['anno'];
    $tr1=$_POST;
    $tr1= array_push_assoc($tr1,'tipo1',311);
    $tr1= array_push_assoc($tr1,'tipo2',312);
    $tr1= array_push_assoc($tr1,'tipo3',313);
    $tr1= array_push_assoc($tr1,'tipo4',314);
    $tr1= array_push_assoc($tr1,'tipo5',315);
    $tr1= array_push_assoc($tr1,'tipo6',316);
    $tr1= array_push_assoc($tr1,'tipo7',317);
    $tr1= array_push_assoc($tr1,'tipo8',318);
    $tr1= array_push_assoc($tr1,'tipo9',323);
    $tr1= array_push_assoc($tr1,'tipo10',323);
	$tr1= array_push_assoc($tr1,'tipo11',323);
	
	$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND tipo BETWEEN 300 AND 399";
	$stmt1 = $t->conn->prepare($q);
	$stmt1->bindParam(':fid', $_SESSION['current_flow_id']);
	$stmt1->bindParam(':y', $anno);
	$stmt1->execute();
	$num = $stmt1->rowCount();
	if($num==0)
		$q="insert into transazioni values(:id,:tipo,:cifra,0,:anno,0,:flow)";
	else
		$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";
	
	for($i=1;$i<12;$i++)
    {
				  
		$idd="".$_SESSION["user_id"].$_SESSION["current_flow_id"]."$anno". ($i+12);
		$idd = str_replace(' ', '', $idd);
		$tr1= array_push_assoc($tr1,"id$i",$idd);  //id transazione
    }


    $macroarea=0;

              //inserimento nel db
             
             //  var_dump($tr1);
    for( $i=1;$i<12;$i++)
	 {
	   $stmt1 = $t->conn->prepare($q);
	   $stmt1->bindParam(':tipo', $tr1["tipo$i"]); 
	   $stmt1->bindParam(':id', $tr1["id$i"]); 
	   $stmt1->bindParam(':cifra', $tr1["cifra$i"."a"]);
	
	
	
	   $stmt1->bindParam(':anno', $anno);
	   $stmt1->bindParam(':flow', $_SESSION['current_flow_id']);
	   $stmt1->execute();
	 }

            //  $_SESSION['tr1']= $tr1;
             /// var_dump($_SESSION); vi entra, ma viene svuotato più avanti
              break;}
  case '3':{
    
    //debug

      //var_dump($_POST);
   //le cose diventano null a caso
   /*
   $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
    if($pageWasRefreshed ) 
	{
		$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";
	} else 
	{
		$q=q($t,0);
	}*/
	
	//here you get the url behind the domain.
	
	$currentPage = $_SERVER['REQUEST_URI'];
	//if the session current page is not set.
	if(!isset($_SESSION['currentPage']))
	{

		//set the session to the current page.
		$_SESSION['currentPage'] = $currentPage;
	}
	//check if the session is not equal to the current page
	if($_SESSION['currentPage'] != $currentPage)
	{

		// if it's not equal you set the session again to the current page.
		$_SESSION['currentPage'] = $currentPage;

		$q=q($t,0);
	}
	else
	{
		$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";
	}
	//var_dump($q);
	
	
	
	
	// var_dump($q);
   $anno=$_POST['anno'];
    $page_title="SPImpieghi1";
	  $tr2=$_POST;
	  $tr2 =array_push_assoc($tr2,'tipo1',410);
	  $tr2 = array_push_assoc($tr2,'tipo2',421);
	  $tr2  =array_push_assoc($tr2,'tipo3',422);
	  $tr2 = array_push_assoc($tr2,'tipo4',423);
	  
	  for($i=1;$i<5;$i++)
	   {
		   $idd="".$_SESSION["user_id"].$_SESSION["current_flow_id"]."$anno". (23+$i);
		   $idd = str_replace(' ', '', $idd);
		  // var_dump($idd);
		 $tr2= array_push_assoc($tr2,"id$i",$idd);  //id transazione
	   }
			   
	$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND tipo BETWEEN 400 AND 499";
	$stmt1 = $t->conn->prepare($q);
	$stmt1->bindParam(':fid', $_SESSION['current_flow_id']);
	$stmt1->bindParam(':y', $anno);
	$stmt1->execute();
	$num = $stmt1->rowCount();
	if($num==0)
		$q="insert into transazioni values(:id,:tipo,:cifra,0,:anno,0,:flow)";
	else
		$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";
	#var_dump($num);
	#var_dump($q);
			   
              $macroarea=0;

              
              for( $i=1;$i<5;$i++)
              {
                $stmt1 = $t->conn->prepare($q);
                $stmt1->bindParam(':tipo', $tr2["tipo$i"]); 
                $stmt1->bindParam(':cifra', $tr2["cifra$i"."a"]);
              $stmt1->bindParam(':id', $tr2["id$i"]); 
            
                
                $stmt1->bindParam(':anno', $anno);
                $stmt1->bindParam(':flow', $_SESSION['current_flow_id']);
                $stmt1->execute();
              }
/*
              $_SESSION['tr2']=$tr2;
              
              
              var_dump($_SESSION);
              */
              break;}

  case '4':{
    //debug
    //var_dump($_SESSION);
	/*$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
    if($pageWasRefreshed ) 
	{
		$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";
	} else 
	{
		$q=q($t,1);
	}*/
	
	
	//here you get the url behind the domain.
	$currentPage = $_SERVER['REQUEST_URI'];
	//if the session current page is not set.
	if(!isset($_SESSION['currentPage']))
	{

		//set the session to the current page.
		$_SESSION['currentPage'] = $currentPage;
	}
	//check if the session is not equal to the current page
	if($_SESSION['currentPage'] != $currentPage)
	{

		// if it's not equal you set the session again to the current page.
		$_SESSION['currentPage'] = $currentPage;

		$q=q($t,1);
	}
	else
	{
		$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";
	}
	
	
	
	
	// var_dump($q);
    $page_title="SPImpieghi2";
    $anno=$_POST['anno'];
              $tr3=$_POST;
              $tr3=  array_push_assoc($tr3,'tipo1',111);
              $tr3 = array_push_assoc($tr3,'tipo2',112);
              $tr3 = array_push_assoc($tr3,'tipo3',121);
              $tr3 = array_push_assoc($tr3,'tipo4',122);
              $tr3 = array_push_assoc($tr3,'tipo5',131);
              $tr3 = array_push_assoc($tr3,'tipo6',132);
              $tr3 = array_push_assoc($tr3,'tipo7',133);
			  
			  for($i=1;$i<8;$i++)
               {
				   $idd="".$_SESSION["user_id"].$_SESSION["current_flow_id"]."$anno$i";
				   $idd = str_replace(' ', '', $idd);
				 // var_dump($idd);
                 $tr3= array_push_assoc($tr3,"id$i",$idd);  //id transazione
               }
	$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND tipo BETWEEN 100 AND 199";
		$stmt1 = $t->conn->prepare($q);
		$stmt1->bindParam(':fid', $_SESSION['current_flow_id']);
		$stmt1->bindParam(':y', $anno);
		$stmt1->execute();
		$num = $stmt1->rowCount();
		if($num==0)
			$q="insert into transazioni values(:id,:tipo,:cifra,1,:anno,0,:flow)";
		else
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";
              $macroarea=1;
           //   var_dump($tr3);

              for( $i=1;$i<8;$i++)
              {
//var_dump ($i);

                $stmt1 = $t->conn->prepare($q);
                $stmt1->bindParam(':tipo', $tr3["tipo$i"]);
                $stmt1->bindParam(':cifra', $tr3["cifra$i"."a"]);
               $stmt1->bindParam(':id', $tr3["id$i"]); 
               
                $stmt1->bindParam(':anno', $anno);
                $stmt1->bindParam(':flow', $_SESSION['current_flow_id']);
                $stmt1->execute();
              }
/*
              $_SESSION['tr3']=$tr3;*/break;}
  case '5':{
	  
	  //here you get the url behind the domain.
	$currentPage = $_SERVER['REQUEST_URI'];
	//if the session current page is not set.
	if(!isset($_SESSION['currentPage']))
	{

		//set the session to the current page.
		$_SESSION['currentPage'] = $currentPage;
	}
	//check if the session is not equal to the current page
	if($_SESSION['currentPage'] != $currentPage)
	{

		// if it's not equal you set the session again to the current page.
		$_SESSION['currentPage'] = $currentPage;

		$q=q($t,1);
	}
	else
	{
		$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";
	}
/*
	  $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
	  var_dump( $pageWasRefreshed);
      if($pageWasRefreshed ) 
		{
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";
		} 
		else 
		{
			$q=q($t,1);
		}
		
		*/
			 // var_dump($q);
                //debug
                //var_dump($_SESSION);
                $anno=$_POST['anno'];
                $tr4=$_POST;
                $tr4=  array_push_assoc($tr4,'tipo1',211);
                $tr4= array_push_assoc($tr4,'tipo2',221);
                $tr4= array_push_assoc($tr4,'tipo3',222);
                $tr4= array_push_assoc($tr4,'tipo4',223);
                $tr4= array_push_assoc($tr4,'tipo5',231);
				
				 for($i=1;$i<6;$i++)
                 {
					 $idd="".$_SESSION["user_id"].$_SESSION["current_flow_id"]."$anno". ($i+7);
				   $idd = str_replace(' ', '', $idd);
				  //var_dump($idd);
                   $tr4= array_push_assoc($tr4,"id$i",$idd);  //id transazione
                 }
				 $q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND tipo BETWEEN 200 AND 299";
				$stmt1 = $t->conn->prepare($q);
				$stmt1->bindParam(':fid', $_SESSION['current_flow_id']);
				$stmt1->bindParam(':y', $anno);
				$stmt1->execute();
				$num = $stmt1->rowCount();
				if($num==0)
					$q="insert into transazioni values(:id,:tipo,:cifra,1,:anno,0,:flow)";
				else
					$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";
                $macroarea=1;

               // var_dump($tr4);
                
                for( $i=1;$i<6;$i++)
                   {
                     $stmt1 = $t->conn->prepare($q);
                     $stmt1->bindParam(':tipo', $tr4["tipo$i"]); ///VARIABILI DINAMICHE
                     $stmt1->bindParam(':cifra', $tr4["cifra$i"."a"]);
                   $stmt1->bindParam(':id', $tr4["id$i"]); 
              
                     $stmt1->bindParam(':anno', $anno);
                     $stmt1->bindParam(':flow', $_SESSION['current_flow_id']);
                     $stmt1->execute();
                   }

                  unset($_SESSION['query_flag']);
				  
				  //allora, dato che mysql è molto simpatico e setta a 0 quando dovrebbe essere 1 e viceversa, 
				  //qui metto query dal significato incontrovertibile che sistemano questo contrattempo
				  $q="update transazioni set macroarea=0 where tipo>300";
				  $stmt1 = $t->conn->prepare($q);
                  $stmt1->execute();
				  $q="update transazioni set macroarea=1 where tipo<300";
				  $stmt1 = $t->conn->prepare($q);
                  $stmt1->execute();

                   echo "<script> location.replace(\"SP.php?h=0\"); </script>";
                   
                   
                   exit;
  }
                
  default : {$page_title="SPFonti1";break;}
}

$require_login=true;
include_once "$_SERVER[DOCUMENT_ROOT]/project/login_checker.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/layout_head.php";


switch($action)
{
  case 1:
    {
      echo "<form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"SP.php?action=2\">";
      echo "  <div class=\"container\">
     
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                <title>Anno di riferimento</title>
                <label>A quale anno fa riferimento codesto stato patrimoniale ?
                <input required id=\"anno\" name=\"anno\" class=\"eltab\" type=\"number\"  min=\"1800\" max=\"".date("Y")."\"/></label>

                <h3>Capitale di debito</h3><br>
              </div>";
    


   
    
    echo "
    
              
                <div class=\"container\">
              
                <table class=\"table\">
                <thead>
                  <tr>
                    <th>Debiti a breve scadenza</th>
                  </tr>
                </thead>
                <tbody>
              
                  <tr>
                    <td>Fondi rischi e oneri</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
              
                      <label>Inserisci cifra
                        <input id=\"cifra1a\" name=\"cifra1a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\" />
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <td>Debiti per TFR</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
                      <label>Inserisci cifra
                      <input id=\"cifra2a\" name=\"cifra2a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/></label>
                    </td>
                  </tr>
                  <tr>
                    <td>Obbligazioni</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
                      <label>Inserisci cifra
                        <input id=\"cifra3a\" name=\"cifra3a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
                        </label>
                    </td>
                  </tr>
                  <tr>
                    <td>Debiti verso banche</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
                      <label>Inserisci cifra
                        <input id=\"cifra4a\" name=\"cifra4a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
                      </label>
                    </td>
                  </tr>
                  <tr><td>Debiti verso fornitori</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
                      <div>
                        <label>Inserisci cifra
                        <input id=\"cifra5a\" name=\"cifra5a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/></label>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Debiti tributari</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
                      <label>Inserisci cifra
                        <input id=\"cifra6a\" name=\"cifra6a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <td>Debiti verso Istituti di previdenza</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
                      <label>Inserisci cifra
                        <input id=\"cifra7a\" name=\"cifra7a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <td>Ratei e risconti</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
                      <label>Inserisci cifra
                        <input id=\"cifra8a\" name=\"cifra8a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
                      </label>
                    </td>
                  </tr>
                </tbody>
                <thead>
                  <tr><th>Debiti a media/lunga scadenza</th></tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Debiti per TFR</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
                      <label>Inserisci cifra
                        <input id=\"cifra9a\" name=\"cifra9a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
                      </label>
                  </tr>
                  <tr>
                    <td>Obbligazioni</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
                      <label>Inserisci cifra
                        <input id=\"cifra10a\" name=\"cifra10a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <td>Debiti verso banche</td>
                    <td>
                      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
                      <title>Inserisci cifra</title>
                      <label>Inserisci cifra
                        <input id=\"cifra11a\" name=\"cifra11a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
                      </label>
                    </td>
                  </tr>
                </tbody>
              </table>";




    
    echo "  </div>
          
          <div class=\"footer\" align=\"center\">
            <div class=\"pagination\">";
           //echo "<button href=\"{$home_url}index.php\" class=\"previous\">&laquo; Indietro</button>";
      echo "<a><strong>1</strong></a>";
      echo "<a>2</a>";
      echo "<a>3</a>";
      echo "<a>4</a>
            <input class=\"next\" type=\"submit\" value=\"Avanti &raquo;\"/>";
      echo"
     
    </div>
  </div>
  
  </form>";
  break;
    
   
  }
  case 2:
  {
      $anno=$_POST['anno'];
    echo "<form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"SP.php?action=3\">";
    echo "<div class=\"container\">
    <input id=\"anno\" name=\"anno\" class=\"eltab\" type=\"hidden\" value=\" $anno\"/></label>

 <h3>Capitale proprio</h3><br>
</div>";

echo "

<div class=\"container\">
<table class=\"table\">
<thead>
  <tr>
    <th>Capitale sociale</th>
  </tr>
</thead>
<tbody>
<form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"SPImpieghi1.php\">
  <tr>
    <td>Capitale sociale</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra1a\" name=\"cifra1a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
</tbody>
<thead>
  <tr><th>Riserve</th></tr>
</thead>
<tbody>
  <tr>
    <td>Riserva legale</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra2a\" name=\"cifra2a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
  <tr>
    <td>Riserva straordinaria</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra3a\" name=\"cifra3a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
  <tr>
    <td>Utile dell'esercizio</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra4a\" name=\"cifra4a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
</tbody>
</table>";

    echo "</div>
    <div class=\"footer\" align=\"center\">
      <div class=\"pagination\">";
      //echo "<button href=\"{$home_url}index.php\" class=\"previous\">&laquo; Indietro</button>";
      echo "<a>1</a>";
      echo "<a><strong>2</strong></a>";
      echo "<a>3</a>";
      echo "<a>4</a>
      <input class=\"next\" type=\"submit\" value=\"Avanti &raquo;\"/>";
      echo"
    
    </div>
  </div>
  </form>";
    break;
  }
  case 3:
  {
    echo "<form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"SP.php?action=4\">";
    echo "<div class=\"container\">
    <input id=\"anno\" name=\"anno\" class=\"eltab\" type=\"hidden\" value=\" $anno\"/></label>
 <h3>Attivo Corrente</h3><br>
</div>";

echo "



<div class=\"container\">
<table class=\"table\">
<thead>
  <tr>
    <th>Disponibilità liquide</th>
  </tr>
</thead>
<tbody>
  <form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"SPImpieghi2.php\">
  <tr>
    <td>Depositi bancari e postali</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra1a\" name=\"cifra1a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
  <tr>
    <td>Denaro e valori in cassa</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra2a\" name=\"cifra2a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
</tbody>
<thead>
  <tr><th>Disponibilità finanziarie</th></tr>
</thead>
<tbody>
  <tr>
    <td>Crediti verso clienti</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra3a\" name=\"cifra3a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
  <tr>
    <td>Ratei e riscontri</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra4a\" name=\"cifra4a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
<thead>
  <tr><th>Rimanenze</th></tr>
</thead>
<tbody>
  <tr>
    <td>Materie prime, sussidarie e di consumo</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra5a\" name=\"cifra5a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
  <tr>
    <td>Prodotti in corso di lavorazione e semilavorate</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra6a\" name=\"cifra6a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
  <tr>
    <td>Prodotti finiti e merci</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra7a\" name=\"cifra7a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
      </label>
    </td>
  </tr>
</tbody>
</table>";





    echo "</div>
    
    <div class=\"footer\" align=\"center\">
      <div class=\"pagination\">";

      echo "<a>1</a>";
      echo "<a>2</a>";
      echo "<a><strong>3</strong></a>";
      echo "<a>4</a>
      <input class=\"next\" type=\"submit\" value=\"Avanti &raquo;\"/>";
      echo"
     
    </div>
  </div>
  </form>";
    break;
  }
  case 4:
  {
    echo "<form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"SP.php?action=5\">";
    echo "<div class=\"container\">
    <input id=\"anno\" name=\"anno\" class=\"eltab\" type=\"hidden\" value=\" $anno\"/></label>
    <h3>Attivo immobilizzato</h3><br>
   </div>";
   
   echo "
   
   

 <div class=\"container\">
   <table class=\"table\">
   <thead>
     <tr>
       <th>Immobilizzazioni immateriali</th>
     </tr>
   </thead>
   <tbody>
   <form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"flowDisplay.php\">
     <tr>
       <td>Diritti di brevetto industriale e diritti di utilizzazione</td>
       <td>
         <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
         <title>Inserisci cifra</title>
   
         <label>Inserisci cifra
           <input id=\"cifra1a\" name=\"cifra1a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
         </label>
       </td>
     </tr>
   </tbody>
   <thead>
     <tr><th>Immobilizzazioni materiali</th></tr>
   </thead>
   <tbody>
     <tr>
       <td>Terreni e fabbricati</td>
       <td>
         <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
         <title>Inserisci cifra</title>
         <label>Inserisci cifra
           <input id=\"cifra2a\" name=\"cifra2a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
         </label>
       </td>
     </tr>
     <tr>
       <td>Attrezzature industriali e commerciali</td>
       <td>
         <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
         <title>Inserisci cifra</title>
         <label>Inserisci cifra
           <input id=\"cifra3a\" name=\"cifra3a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
         </label>
       </td>
     </tr>
     <tr>
       <td>Altri beni</td>
       <td>
         <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
         <title>Inserisci cifra</title>
         <label>Inserisci cifra
           <input id=\"cifra4a\" name=\"cifra4a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
         </label>
       </td>
     </tr>
   </tbody>
   <thead>
     <tr><th>Immobilizzazioni finanziarie</th></tr>
   </thead>
   <tbody>
     <tr>
       <td>Crediti verso clienti</td>
       <td>
         <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
         <title>Inserisci cifra</title>
         <label>Inserisci cifra
           <input id=\"cifra5a\" name=\"cifra5a\" class=\"eltab\" type=\"number\" value=\"0\" min=\"0\"/>
         </label>
       </td>
     </tr>
   </tbody>
   </table>";
   

    echo "</div>
    <div class=\"footer\" align=\"center\">
      <div class=\"pagination\">";

      echo "<a>1</a>";
      echo "<a>2</a>";
      echo "<a>3</a>";
      echo "<a><strong>4</strong></a>
      <input class=\"next\" type=\"submit\" value=\"Salva\"/>";
      echo"
    
    </div>
    </div>
    </form>";
    break;
  }
}






 

// footer HTML and JavaScript codes
include "$_SERVER[DOCUMENT_ROOT]/project/layout_foot.php";










