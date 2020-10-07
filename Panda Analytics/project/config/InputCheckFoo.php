<?php
function array_push_assoc($array, $key, $value)
{
	$array[$key] = $value;
	return $array;
}



function equal_array($arr)
{
	$ArrayObject = new ArrayObject($arr);
	return $ArrayObject->getArrayCopy();  
}

function rip_tags($string) 
{ 
  //  echo "inside rip_tags: $string ->";
    // ----- remove HTML TAGs ----- 
    $string = preg_replace ('/<[^>]*>/', ' ', $string); 
  //  echo " $string ->";
    // ----- remove control characters ----- 
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", '', $string);   // --- replace with empty space
    $string = str_replace("\t", '', $string);   // --- replace with empty space
  //  echo " $string ->";
    // ----- remove multiple spaces ----- 
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
  //  echo "$string <- inside rip_tags";
    return $string; 

}//strip tags non funziona

function test_input($data) 
{
  //$data = trim($data); // toglie spazi, tabs e newline extra
  
  $data = rip_tags($data);//rimuove i tag html
  //$data = stripslashes($data);
  //$data = preg_replace("/&#?[a-z0-9]{2,8};/i","",$data);
  
  //$data = mysqli_real_escape_string($data); // aiuta a prevenire sql injection, ma la parte consistente sono le parameterized queries
  return $data;
}


//----------------------------------------------------------------------------------------------------------------------------------------------
//da qui a ---- le funzioni ritornano null se è tutto ok, un messaggio di errore se qualcosa non va

function validate_name($data)
{
	$data = test_input($data);
	if (strlen($data) >= 45) 
		$error = "Lo username deve avere massimo 45 caratteri";
	else
		$error = null;
	if($data==null || $data=="")
		$error = "Il campo nome non può essere vuoto";
	return $error;
}

function validate_password($data)
{
	$data = test_input($data);
	$error = "La password deve:";
	// check delle caratteristiche della password
	$uppercase = preg_match("@[A-Z]@", $data);
	$lowercase = preg_match("@[a-z]@", $data);
	$number    = preg_match("@[0-9]@", $data);
	//$specialChars = preg_match("@[^\w]@", $data);
	if(strlen($data) < 8 || strlen($data)>20)
		$length = false;
	else
		$length = true;

	if(!$uppercase || !$lowercase || !$number || !$length)
		{
		 if(!$uppercase)
			 $error .= "contenere almeno una lettera maiuscola";
		 if(!$lowercase)
			 $error .= ", contenere almeno una lettera minuscola";
		 if(!$number)
			 $error .= ", contenere almeno un numero";
		 if(!$length)
			 $error .= ", avere una lunghezza compresa tra 8 e 20";
		 str_replace(":,",$error,":");
		 $error .= ".";
		
		}
	else{
			$error=null;
		}
		if($data==null || $data=="")
			$error = "Il campo password non può essere vuoto";
	return $error;
}

function validate_mail($data)
{
	
	$data = test_input($data);
	if (!filter_var($data, FILTER_VALIDATE_EMAIL))
		$error = "Formato email non valido";
	else
		$error = null;
	if(strlen($data)>45)
		$error="la mail deve avere massimo 45 caratteri";
	return $error;
}

//----------------------------------------------------------------------------------------------------------------------------------------------

function month($num)
{
	switch($num)
	{
		case 1:return "Gennaio";
		case 2:return "Febbraio";
		case 3:return "Marzo";
		case 4:return "Aprile";
		case 5:return "Maggio";
		case 6:return "Giugno";
		case 7:return "Luglio";
		case 8:return "Agosto";
		case 9:return "Settembre";
		case 10:return "Ottobre";
		case 11: return "Novembre";
		case 12: return "Dicembre";
		default:return "";
	}
}


function fromCodeToString($code)
{
	$code=(string)$code; // cast a stringa
	if($code==null)
		return "Codice non presente";
	if(strlen($code)>3)
		return "Codice troppo lungo";
	if($code=="000")
		return "Questo codice dovrebbe appartenere ad un utente dummy";
	switch($code[0])
	{
		case "1":
			{
				switch($code[1])
				{
					case "1":
					{
						switch($code[2])
						{
							case "1":
							{
								return "depositi bancari e postali";
							}
							case "2":
							{
								return "denaro e valori in cassa";
							}
							default:
								{return "terzo carattere errato";}
						}
					}
					case "2":
					{
						switch($code[2])
						{
							case "1":
							{
								return "crediti verso clienti";
							}
							case "2":
							{
								return "ratei e risconti";
							}
							default:
								{return "terzo carattere errato";}
						}
					}
					case "3":
					{
						switch($code[2])
						{
							case "1":
							{
								return "materie prime,sussidiarie e di consumo";
							}
							case "2":
							{
								return "prodotti in corso di lavorazione e semilavorate";
							}
							case "3":
							{
								return "prodotti fini e merci";
							}
							default:
								{return "terzo carattere errato";}
						}
					}
					default:
						{return "secondo carattere errato";}
				}
			}
		case "2":
			{
				switch($code[1])
				{
					case "1":
					{
						switch($code[2])
						{
							case "1":
							{
								return "diritti di brevetto industriale e diritti di utilizzatore";
							}
							default:
								{return "terzo carattere errato";}
						}
					}
					case "2":
					{
						switch($code[2])
						{
							case "1":
							{
								return "terreni e fabbricati";
							}
							case "2":
							{
								return "attrezzature industriali e commerciali";
							}
							case "3":
							{
								return "altri beni";
							}
							default:
								{return "terzo carattere errato";}
						}
					}
					case "3":
					{
						switch($code[2])
						{
							case "1":
							{
								return "crediti verso i clienti";
							}
							default:
								{return "terzo carattere errato";}
						}
					}
					default:
						{return "secondo carattere errato";}
				}
				
			}
		case "3":
			{
				switch($code[1])
				{
					case "1":
					{
						switch($code[2])
						{
							case "1":
							{
								return "fondi rischi e oneri";
							}
							case "2":
							{
								return "debiti per tfr(breve scadenza)";
							}
							case "3":
							{
								return "obbligazioni(breve scadenza)";
							}
							case "4":
							{
								return "debiti verso banche(breve scadenza)";
							}
							case "5":
							{
								return "debiti verso fornitori";
							}
							case "6":
							{
								return "debiti tributari";
							}
							case "7":
							{
								return "debiti verso istituti di previdenza";
							}
							case "8":
							{
								return "ratei e risconti";
							}
							default:
								{return "terzo carattere errato";}
						}
					}
					case "2":
					{
						switch($code[2])
						{
							case "1":
							{
								return "debiti per tfr(media/lunga scadenza)";
							}
							case "2":
							{
								return "obbligazioni(media/lunga scadenza)";
							}
							case "3":
							{
								return "debiti verso banche(media/lunga scadenza)";
							}
							default:
								{return "terzo carattere errato";}
						}
					}
					default:
						{return "secondo carattere errato";}
				}
				
			}
		case "4":
			{
				switch($code[1])
				{
					case "1":
					{
						switch($code[2])
						{
							case "0":
							{
								return "capitale sociale";
							}
							default:
								{return "terzo carattere errato";}
						}
					}
					case "2":
					{
						switch($code[2])
						{
							case "1":
							{
								return "Riserva legale";
							}
							case "2":
							{
								return "Riserva straordinaria";
							}
							case "3":
							{
								return "utile(perdita) dell' esercizio - dividendi";
							}
							default:
								{return "terzo carattere errato";}
						}
					}
				}
			}
		default:
			{return "primo carattere errato";}
	}
	
}

?>