<?php

	/*  valid_event($string)
		Funcion: Evalua si el evento es valido para un puntaje mayor a 1
		Retorna: True si el evento es valido; False en caso contrario
		Patametro: String del evento a evaluar
	*/

	function valid_event($evento){
		$ArrayEvents = ["PushEvent","CreateEvent","IssuesEvent","CommitCommentEvent"];
		for ($i=0; $i < count($ArrayEvents); $i++) { 
			if ($evento == $ArrayEvents[$i]) {
				return true;
			}
		}
		return false;
	}

	////////////__Main__////////

	$user = $_POST["user"];
	$url = "https://api.github.com/users/". $user . "/events"; // URL del usuario ingresado

	$ch = curl_init();	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31'); // Identificacion para ingresar a //https:/api.github.com
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Se verifica si la extracion de informacion fue realizada

	$curl_response = curl_exec($ch);
	$json = json_decode($curl_response); // Se guarda el json en un arreglo de objetos

	curl_close($ch);

	// Contadores de eventos
	$create = 0;
	$push = 0;
	$issues = 0;
	$commit = 0;
	$otro = 0;
	// Contador puntaje total
	$score = 0;

	for ($i = 0; $i < count($json); $i++)  // Inicio del recorrido del arreglo para el conteo del puntaje
	{
		if ($json[$i]->type == "PushEvent")
		{
			$score += 5;
			$push += 1;
  		}

  		elseif ($json[$i]->type == "CreateEvent")
		{
			$score += 4;
			$create += 1;
  		}
  		elseif ($json[$i]->type == "IssuesEvent")
		{
			$score += 3;
			$issues += 1;
  		}
  		elseif ($json[$i]->type == "CommitCommentEvent")
		{
			$score += 2;
			$commit += 1;
  		}
  		elseif (!valid_event($json[$i]->type))
  		 {
  			$score += 1;
  			$otro += 1;
  		}
	}

	////////////Datos del Usuario //
	$url = "https://api.github.com/users/". $user; // URL del usuario ingresado

	$ch = curl_init();	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_USERAGENT,'IsaiasCardenas'); // Identificacion para ingresar a //https:/api.github.com
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Se verifica si la extracion de informacion fue realizada

	$curl_response = curl_exec($ch);
	$user_obj = json_decode($curl_response); // Se guardan los datos del objeto

	curl_close($ch);

	$user_image = $user_obj->avatar_url;
	$user_name = $user_obj->name;



?>

<!--   //////////////////// Se muestran los datos por pantalla  ///////////////////////-->

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/style_score.css">
	<title></title>
</head>
<body>
	<header><img src="img/header.jpg" height="150"></header>
	<div class="score">
		<?php
		echo "<h2> GitHub Score: " . $score ."</h2>";
		?>
	</div>
	<div class="eventos">
		<ul>
    		<li><a> <?php echo "Total PushEvents = " . $push . "<br>"; ?> </a></li>
    		<li><a> <?php echo "Total CreateEvent = " . $create . "<br>"; ?> </a></li>
   			<li><a> <?php echo "Total IssuesEvent = " . $issues . "<br>"; ?> </a></li>
   			<li><a> <?php echo "Total CommitCommentEvent = " . $commit . "<br>"; ?> </a></li>
    		<li><a> <?php echo "Otros Eventos = " . $otro . "<br>"; ?> </a></li>

    	</ul>
	</div>
	<div class="user_image">
		<h3>Your Photo <hr></h3>
		<img src='<?php echo $user_image;?>' width = "200">
		<?php if ($user_name != "") {echo "<br><br><br>" . $user_name;} else { echo "<br><br><br>NO NAME";} ?>
	</div>
</body>
</html>
