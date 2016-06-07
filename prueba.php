<?php

require 'vendor/autoload.php';

use Src\Request;
use Src\User;
use Src\Score;
//use Dotenv\Dotenv;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$NAME = 'nicolasvasquez';

/*
$dsn = 'mysql:host=localhost;dbname='.getenv('DB_DATABASE').';'; 
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
*/

try {
    $connection = new PDO('mysql:dbname='. getenv('DB_DATABASE').';host=localhost', getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    //$connection = new PDO($dsn, $user, $pass);
    //$sqlStatement = 'INSERT INTO scores(username, score) 
    //VALUES(\'otrouser\', 123456)';
    //$sqlStatement = $connection->prepare('SELECT * from scores'); //WHERE username = \'IsaiasCardenas\';');
    $sqlStatement = $connection->prepare('INSERT INTO scores(username, score) 
    VALUES(\''.$NAME.'\', 09876545678);');
    //$sqlStatement->setFetchMode(PDO::FETCH_CLASS, Score::class);
    
   var_dump($sqlStatement->execute()); 
   /* 
    foreach ($row as $object) {
        var_dump($object);
    }*/
 }
 catch (PDOException $e) {
    echo 'Error Database: ' . $e->getMessage();
 }
