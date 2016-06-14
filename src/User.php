<?php

namespace Src;

class User
{
    private $username;
    private $name;
    private $repos;
    private $eventsScore;
    private $eventsCount;
    private $followers;
    private $score;
    private $photo;
    private $stars;

    public function __construct()
    {
        $this->eventsScore = [
        "create" => 0,
        "push" => 0,
        "issues" => 0,
        "commit" => 0,
        "otro" => 0];

        $this->eventsCount = [
        "create" => 0,
        "push" => 0,
        "issues" => 0,
        "commit" => 0,
        "otro" => 0];
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getStars()
    {
        return $this->stars;
    }

    public function getFollowers()
    {
        return $this->followers;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getEventScore($event)
    {
        if (is_null($this->eventsScore[$event])) {
            return "No valid Event";
        }
        return $this->eventsScore[$event];
    }

    public function getEventCount($event)
    {
        if (is_null($this->eventsScore[$event])) {
            return "No valid Event";
        }
        return $this->eventsCount[$event];
    }

    public function setStars($array)
    {
        $stars_count = 0;
        foreach ($array as $repo) {
            $stars_count += $repo->stargazers_count;
        }
        $this->stars = $stars_count;
    }

    public function setUser($json)
    {
        if (is_null($json->name)) {
            $this->name = "NO NAME";
        } else {
            $this->name = $json->name;
        }
        $this->username = $json->login;
        $this->followers = $json->followers;
        $this->photo = $json->avatar_url;
    }

    public function setScore()
    {
        foreach ($this->eventsScore as $key => $value) {
            $this->score += $value;
        }
        $this->score = ($this->stars)*0.4 + ($this->followers)*0.2 + ($this->score)+0.4;
    }

    public function setEvents($array)
    {
        foreach ($array as $event) {
            if ($event->type == "PushEvent") {
                $this->eventsScore["push"] += 5;
                $this->eventsCount["push"] += 1;
            } elseif ($event->type == "CreateEvent") {
                $this->eventsScore["create"] += 4;
                $this->eventsCount["create"] += 1;
            } elseif ($event->type == "IssuesEvent") {
                $this->eventsScore["issues"] += 3;
                $this->eventsCount["issues"] += 1;
            } elseif ($event->type == "CommitCommentEvent") {
                $this->eventsScore["commit"] += 2;
                $this->eventsCount["commit"] += 1;
            } else {
                $this->eventsScore["otro"] += 1;
                $this->eventsCount["otro"] += 1;
            }
        }
    }
}
