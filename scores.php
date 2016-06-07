<?php

require 'vendor/autoload.php';

use Src\Request;
use Src\User;
use Src\Score;
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();


////// BD ///////////


$rankingNumber = 1;

$connection = new PDO('mysql:dbname='. getenv('DB_DATABASE').';host=localhost', getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
//$connection = new PDO($dsn, $user, $pass);
//$sqlStatement = 'INSERT INTO scores(username, score) 
//VALUES(\'otrouser\', 123456)';
$sqlStatement = $connection->prepare('SELECT * FROM scores ORDER BY score DESC'); //WHERE username = \'IsaiasCardenas\';');
//VALUES(\''.$NAME.'\', 1234);');
$sqlStatement->setFetchMode(PDO::FETCH_CLASS, Score::class);

$sqlStatement->execute();

$scoreArray = $sqlStatement->fetchAll();

///////////// Main ///////////////////////

// RETORNAR UN ARREGLO DE USUARIOS DE LA DB PARA RENDERISAR

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, ['debug' => true]);

$config_render = [
	'scoreArray' => $scoreArray,
	'rankingNumber' => $rankingNumber
];

echo $twig->render('scores.html', $config_render);