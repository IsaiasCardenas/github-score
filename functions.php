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