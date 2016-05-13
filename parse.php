<?php

	/*  valid_event($string)
		Parametro: String del evento a evaluar
		Funcion: Evalua si el evento es valido para un puntaje mayor a 1
		Retorna: True si el evento es valido; False en caso contrario
	*/

	function valid_event($evento)
	{
		$ArrayEvents = ["PushEvent","CreateEvent","IssuesEvent","CommitCommentEvent"];
		for ($i=0; $i < count($ArrayEvents); $i++) 
		{ 
			if ($evento == $ArrayEvents[$i]) 
			{
				return true;
			}
		}
		return false;
	}

	/*  extract_json($string)
		Parametro: String de la url que contiene el archivo .json
		Funcion: Extrae los datos de un archivo .json de apartir de una url
		Retorna: El arreglo u objeto json segun corresponda
	*/

	function extract_json($url)
	{
		$ch = curl_init();	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31"); // Identificacion para ingresar a //https:/api.github.com
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Se verifica si la extracion de informacion fue realizada
		$string_json = curl_exec($ch);
		$json = json_decode($string_json); // Se guarda el json en un arreglo de objetos
		curl_close($ch);
		return $json;
	}


	/*  event_counter($array, $array)
		Patametros: 1.- Arreglo json con los eventos github 2.- Arreglo con los contadores de eventos
		Funcion: Recorre el arreglo json para realizar un conteo de los tipos de eventos y los guarda en el arreglo de eventos
		Retorna: El arreglo con los contadores de eventos actualizados
	*/

	function event_counter($json_array, $events_array)
	{
		for ($i = 0; $i < count($json_array); $i++){ // Inicio del recorrido del arreglo para el conteo del puntaje
			if ($json_array[$i]->type == "PushEvent"){
				$events_array["score"] += 5;
				$events_array["push"] += 1;
	  		}

	  		elseif ($json_array[$i]->type == "CreateEvent"){
				$events_array["score"] += 4;
				$events_array["create"] += 1;
	  		}
	  		elseif ($json_array[$i]->type == "IssuesEvent"){
				$events_array["score"] += 3;
				$events_array["issues"] += 1;
	  		}
	  		elseif ($json_array[$i]->type == "CommitCommentEvent"){
				$events_array["score"] += 2;
				$events_array["commit"] += 1;
	  		}
	  		elseif (!valid_event($json_array[$i]->type)){
	  			$events_array["score"] += 1;
	  			$events_array["otro"] += 1;
	  		}

		}
		return $events_array;
	}

	/*  stars_count($array)
		Patametros: Arreglo json con los objetos repositorios de un usuario
		Funcion: Recorre el arreglo json para realizar un conteo acumulado de las estrellas de cada repositorio
		Retorna: La suma de todas las estrellas (int)
	*/

	function stars_count($json_repo)
	{
		$stars = 0;
		for ($i = 0; $i < count($json_repo); $i++){ // Inicio del recorrido del arreglo para el conteo del puntaje
			$stars += $json_repo[$i]->stargazers_count;
		}
		return $stars;
	}

	////////////__Main__////////

	//Usuario 1

	$user = $_POST["user1"];
	$url = "https://api.github.com/users/". $user; // URL del usuario 1 ingresado


	$json_1 = extract_json($url); // Se guarda el json en un arreglo de objetos
	$json_1_events = extract_json(substr($json_1->events_url, 0, -10));
	$json_1_repo = extract_json($json_1->repos_url);
	// Contadores de eventos
	$eventos_1 = [
		"create" => 0,  //CreateEvent
		"push" => 0,  //PushEvent
		"issues" => 0,  //IssuesEvent
		"commit" => 0,  //CommitCommentEvent
		"otro" => 0,  //Otro
		"score" => 0,  // Contador puntaje total
	];

	$eventos_1 = event_counter($json_1_events, $eventos_1);

	//Usuario 2

	$user = $_POST["user2"];
	$url = "https://api.github.com/users/". $user; // URL del usuario 2 ingresado


	$json_2 = extract_json($url); // Se guarda el json en un arreglo de objetos
	$json_2_events = extract_json(substr($json_2->events_url, 0, -10));
	$json_2_repo = extract_json($json_2->repos_url);
	// Contadores de eventos
	$eventos_2 = [
		"create" => 0,  //CreateEvent
		"push" => 0,  //PushEvent
		"issues" => 0,  //IssuesEvent
		"commit" => 0,  //CommitCommentEvent
		"otro" => 0,  //Otro
		"score" => 0,  // Contador puntaje total
	];

	$eventos_2 = event_counter($json_2_events, $eventos_2);


	////////////Datos de los Usuarios //

	$user_image_1 = $json_1->avatar_url;
	$user_name_1 = $json_1->name;
	$user_image_2 = $json_2->avatar_url;
	$user_name_2 = $json_2->name;




?>

<!--   //////////////////// Se muestran los datos por pantalla  ///////////////////////-->

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/style_score.css">
	<title></title>
</head>
<body>
	<header id = "header"></header>

	<div id = "contenedor">
	  <div id = "contenidos">
	    <div id = "score_1">
	    	<li><a> <?php echo "Total PushEvents = " . $eventos_1["push"] . "<br>"; ?> </a></li>
    		<li><a> <?php echo "Total CreateEvent = " . $eventos_1["create"] . "<br>"; ?> </a></li>
   			<li><a> <?php echo "Total IssuesEvent = " . $eventos_1["issues"] . "<br>"; ?> </a></li>
   			<li><a> <?php echo "Total CommitCommentEvent = " . $eventos_1["commit"] . "<br>"; ?> </a></li>
    		<li><a> <?php echo "Otros Eventos = " . $eventos_1["otro"] . "<br>"; ?> </a></li>
    		<li><a> <?php echo "Total Estrellas = " . stars_count($json_1_repo) . "<br>"; ?> </a></li>
    		<li><a> <?php echo "Total Seguidores = " . $json_1->followers. "<br>"; ?> </a></li>
    		<?php echo "<h2> GitHub Score: " . $eventos_1["score"] ."</h2>"; ?>
	    </div>
	    <div id = "profile_1">
	    	<h3>Your Photo <hr></h3>
			<img src='<?php echo $user_image_1;?>' width = "150">
			<?php if ($user_name_1 != "") {echo "<br><br><br>" . $user_name_1;} else { echo "<br><br><br>NO NAME";} ?>
	    </div>
	    <div id = "vs">
	    	<h4>vs</h4>
	    </div>
	    <div id = "score_2">
	    	<li><a> <?php echo "Total PushEvents = " . $eventos_2["push"] . "<br>"; ?> </a></li>
    		<li><a> <?php echo "Total CreateEvent = " . $eventos_2["create"] . "<br>"; ?> </a></li>
   			<li><a> <?php echo "Total IssuesEvent = " . $eventos_2["issues"] . "<br>"; ?> </a></li>
   			<li><a> <?php echo "Total CommitCommentEvent = " . $eventos_2["commit"] . "<br>"; ?> </a></li>
    		<li><a> <?php echo "Otros Eventos = " . $eventos_2["otro"] . "<br>"; ?> </a></li>
    		<li><a> <?php echo "Total Estrellas = " . stars_count($json_2_repo) . "<br>"; ?> </a></li>
    		<li><a> <?php echo "Total Seguidores = " . $json_2->followers. "<br>"; ?> </a></li>
    		<?php echo "<h2> GitHub Score: " . $eventos_2["score"] ."</h2>"; ?>
	    </div>
	    <div id = "profile_2">
	    	<h3>Your Photo <hr></h3>
			<img src='<?php echo $user_image_2;?>' width = "150">
			<?php if ($user_name_2 != "") {echo "<br><br><br>" . $user_name_2;} else { echo "<br><br><br>NO NAME";} ?>
	    </div>
	  </div>
	</div>
		<!-- /////////////////////////////////////////////////-->
</body>
</html>

