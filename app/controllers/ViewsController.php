<?php

namespace App\controllers;

use App\Request;
use App\User;
use App\Score;
use Flight;

class ViewsController
{
    public static function form()
    {
        return view('form.html');
    }

    public static function battle()
    {
        try {
            $request = new Request();
            $user1 = new User();
            $user2 = new User();

            $user1->setUser(json_decode($request->getUser(Flight::request()->data->user1)));
            $user1->setEvents(json_decode($request->getEvents(Flight::request()->data->user1)));
            $user1->setStars(json_decode($request->getRepos(Flight::request()->data->user1)));
            $user1->setScore();

            $newScore = Score::whereUsername($user1->getUsername())->first();
            if ($newScore) {
                $newScore->score = $user1->getScore();
                $newScore->save();
            } else {
                Score::create([
                    'username' => $user1->getUsername(),
                    'score'    => $user1->getScore()
                ]);
            }


            $user2->setUser(json_decode($request->getUser(Flight::request()->data->user2)));
            $user2->setEvents(json_decode($request->getEvents(Flight::request()->data->user2)));
            $user2->setStars(json_decode($request->getRepos(Flight::request()->data->user2)));
            $user2->setScore();

            $newScore = Score::whereUsername($user2->getUsername())->first();
            if ($newScore) {
                $newScore->score = $user2->getScore();
                $newScore->save();
            } else {
                Score::create([
                    'username' => $user2->getUsername(),
                    'score'    => $user2->getScore()
                ]);
            }

            $config_render = [
                'usuario_1' => $user1,
                'usuario_2' => $user2
            ];

            return view('battle.html', $config_render);
        } catch (Exception $e) {
            return view('form.html', ['error' => true]);
        }
    }

    public static function scores()
    {

        $ranking = Score::orderBy('score', 'DESC')
            ->take(10)
            ->get()
            ->toArray();

        $rankingNumber = 1;

        $config_render = [
            'scoreArray' => $ranking,
            'rankingNumber' => $rankingNumber
        ];
        return view('scores.html', $config_render);
    }
}
