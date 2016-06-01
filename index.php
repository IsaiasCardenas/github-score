<?php

require 'vendor/autoload.php';

use Src\Request;
use Src\User;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$request = new Request();

$user1 = new User(json_decode($request->getUser($_POST["user1"])));
$user1->setEvents(json_decode($request->getEvents($_POST["user1"])));
$user1->setStars(json_decode($request->getRepos($_POST["user1"])));
$user1->setScore();

$user2 = new User(json_decode($request->getUser($_POST["user2"])));
$user2->setEvents(json_decode($request->getEvents($_POST["user2"])));
$user2->setStars(json_decode($request->getRepos($_POST["user2"])));
$user2->setScore();


///////////// Main ///////////////////////


$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, ['debug' => true]);

$config_render = [
	'usuario_1' => $user1,
	'usuario_2' => $user2
];

echo $twig->render('git_hub_battle.html', $config_render);

