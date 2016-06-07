<?php

require 'vendor/autoload.php';

use Src\Request;
use Src\User;
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

try {
	$request = new Request();

	$user1 = new User(json_decode($request->getUser($_POST["user1"])));
	$user1->setEvents(json_decode($request->getEvents($_POST["user1"])));
	$user1->setStars(json_decode($request->getRepos($_POST["user1"])));
	$user1->setScore();

	$user2 = new User(json_decode($request->getUser($_POST["user2"])));
	$user2->setEvents(json_decode($request->getEvents($_POST["user2"])));
	$user2->setStars(json_decode($request->getRepos($_POST["user2"])));
	$user2->setScore();	
} 
catch (Exception $e) {
	header("Location:".$_SERVER['HTTP_REFERER']);  	
}



////// BD ///////////
/*

$dsn = 'mysql:host=localhost;dbname=githubScore'; 
$user = 'root';
$pass = 'isaias1994';*/

$connection = new PDO('mysql:dbname='. getenv('DB_DATABASE').';host=localhost', getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
//$connection = new PDO($dsn, $user, $pass);
$sqlStatement = $connection->prepare('INSERT INTO scores(username, score) 
    VALUES(\''.$user1->getUsername().'\', '.$user1->getScore().')');

if (!($sqlStatement->execute())) {
	$sqlStatement = $connection->prepare('UPDATE scores SET score = '. $user1->getScore() . 
	' WHERE username = \''.$user1->getUsername().'\';');
	$sqlStatement->execute();
	
}

$sqlStatement = $connection->prepare('INSERT INTO scores(username, score) 
    VALUES(\''.$user2->getUsername().'\', '.$user2->getScore().')');

if (!($sqlStatement->execute())) {
	$sqlStatement = $connection->prepare('UPDATE scores SET score = '.$user2->getScore().
	' WHERE username = \''.$user2->getUsername().'\';');
	$sqlStatement->execute();
	
}


///////////// Main ///////////////////////


$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, ['debug' => true]);

$config_render = [
	'usuario_1' => $user1,
	'usuario_2' => $user2
];

echo $twig->render('git_hub_battle.html', $config_render);

