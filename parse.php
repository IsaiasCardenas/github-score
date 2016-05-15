<?php
	
	include "functions.php";
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

	$final_score_1 = (0.4 * $eventos_1["score"] + 0.4 * stars_count($json_1_repo) + 0.2 * $json_1->followers);
	$final_score_2 = (0.4 * $eventos_2["score"] + 0.4 * stars_count($json_2_repo) + 0.2 * $json_2->followers);

?>

<!--   //////////////////// Se muestran los datos por pantalla  ///////////////////////-->

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/style_score.css">
	<title>GhitHub Battle</title>
</head>
<body>
	<header id = "header"></header>
	<div id = "contenedor">
		<div id = "columna1">

			<div class = "fila">
		    	<div class = "datos" id = "usuario1">
		    		<li><a> <?php echo "Total PushEvents = " . $eventos_1["push"] . "<br>"; ?> </a></li>
		    		<li><a> <?php echo "Total CreateEvent = " . $eventos_1["create"] . "<br>"; ?> </a></li>
		   			<li><a> <?php echo "Total IssuesEvent = " . $eventos_1["issues"] . "<br>"; ?> </a></li>
		   			<li><a> <?php echo "Total CommitCommentEvent = " . $eventos_1["commit"] . "<br>"; ?> </a></li>
		    		<li><a> <?php echo "Otros Eventos = " . $eventos_1["otro"] . "<br>"; ?> </a></li>
		    		<li><a> <?php echo "Total Estrellas = " . stars_count($json_1_repo) . "<br>"; ?> </a></li>
		    		<li><a> <?php echo "Total Seguidores = " . $json_1->followers. "<br>"; ?> </a></li>
		    		<?php echo "<h2> GitHub Score: " . $final_score_1 ."</h2>"; ?>
		    	</div>
		    	<div class="datos" id = "profile_1">
		    		<h3>Your Photo <hr></h3>
					<img src='<?php echo $user_image_1;?>' width = "150">
					<?php if ($user_name_1 != "") {echo "<br><br><br>" . $user_name_1;} else { echo "<br><br><br>NO NAME";} ?>
		    	</div>
		    </div>	
			<div class = "fila">
		    	<div class = "datos" id = "usuario2">
		    		<li><a> <?php echo "Total PushEvents = " . $eventos_2["push"] . "<br>"; ?> </a></li>
		    		<li><a> <?php echo "Total CreateEvent = " . $eventos_2["create"] . "<br>"; ?> </a></li>
		   			<li><a> <?php echo "Total IssuesEvent = " . $eventos_2["issues"] . "<br>"; ?> </a></li>
		   			<li><a> <?php echo "Total CommitCommentEvent = " . $eventos_2["commit"] . "<br>"; ?> </a></li>
		    		<li><a> <?php echo "Otros Eventos = " . $eventos_2["otro"] . "<br>"; ?> </a></li>
		    		<li><a> <?php echo "Total Estrellas = " . stars_count($json_2_repo) . "<br>"; ?> </a></li>
		    		<li><a> <?php echo "Total Seguidores = " . $json_2->followers. "<br>"; ?> </a></li>
		    		<?php echo "<h2> GitHub Score: " . $final_score_2 ."</h2>"; ?>
		    	</div>
		    	<div class = "datos" id = "profile_2">
		    		<h3>Your Photo <hr></h3>
					<img src='<?php echo $user_image_2;?>' width = "150">
					<?php if ($user_name_2 != "") {echo "<br><br><br>" . $user_name_2;} else { echo "<br><br><br>NO NAME";} ?>
		    	</div>
		    </div>
	    </div>
	    <div id = "columna2">
	    	<div id = "winner">
		    	<?php 
		    		
		    		if ($final_score_1 > $final_score_2) {
		    		 	echo "<h4>You win!! <hr></h4><img src= \""  . $user_image_1 . "\" width = \"150\">";
						if ($user_name_1 != "") {echo "<br><br><br>" . $user_name_1;} else { echo "<br><br><br>NO NAME";} 
						echo "<br><br><br><br>Your Score: " . $final_score_1;
		    		}

		    		elseif ($final_score_1 < $final_score_2) {
		    			echo "<h4>You win!! <hr></h4><img src= \""  . $user_image_2 . "\" width = \"150\">";
						if ($user_name_2 != "") {echo "<br><br><br>" . $user_name_2;} else { echo "<br><br><br>NO NAME";} 
						echo "<br><br><br><br>Your Score: " . $final_score_2;
		    		}
		    			 	
		    		else{
		    			echo "<h6>draw</h6>";
		    		} 
		    	?>
	    	</div>
	    	
	    </div>
	</div>
	<div id = "vs"><h1>vs</h1></div>

		<!-- /////////////////////////////////////////////////-->
</body>
</html>

