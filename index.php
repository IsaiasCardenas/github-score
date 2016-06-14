<?php

require 'vendor/autoload.php';

use Src\Request;
use Src\User;
use Src\Score;
use Src\Database;
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$request = new Request();
$database = new Database();
$score = new Score();
$user1 = new User();
$user2 = new User();

Flight::route('GET /', function () {
    return view('form.html');
});

Flight::route('POST /', function () use ($user1, $user2, $request, $dotenv, $database, $score) {

    try {
        $user1->setUser(json_decode($request->getUser(Flight::request()->data->user1)));
        $user1->setEvents(json_decode($request->getEvents(Flight::request()->data->user1)));
        $user1->setStars(json_decode($request->getRepos(Flight::request()->data->user1)));
        $user1->setScore();

        $user2->setUser(json_decode($request->getUser(Flight::request()->data->user2)));
        $user2->setEvents(json_decode($request->getEvents(Flight::request()->data->user2)));
        $user2->setStars(json_decode($request->getRepos(Flight::request()->data->user2)));
        $user2->setScore();

        $database->insertUser($user1->getUsername(), $user1->getScore());
        $database->insertUser($user2->getUsername(), $user2->getScore());

        $config_render = [
            'usuario_1' => $user1,
            'usuario_2' => $user2
        ];

        return view('battle.html', $config_render);
    } catch (Exception $e) {
        return view('form.html', ['error' => true]);
    }
});

Flight::route('/scores', function () use ($database, $score) {

    $rankingNumber = 1;
    $scoreArray = $database->getRanking("scores");

    $config_render = [
        'scoreArray' => $scoreArray,
        'rankingNumber' => $rankingNumber
    ];
    return view('scores.html', $config_render);
});

Flight::start();
