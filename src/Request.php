<?php

namespace Src;

use GuzzleHttp\Client;

class Request
{
    private $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function getUser($username)
    {
        return $this->get('https://api.github.com/users/' . $username)
            ->getBody()
            ->getContents();
    }

    public function getEvents($username)
    {
        return $this->get('https://api.github.com/users/' . $username . '/events')
            ->getBody()
            ->getContents();
    }

     public function getRepos($username)
    {
        return $this->get('https://api.github.com/users/' . $username . '/repos')
            ->getBody()
            ->getContents();
    }

    private function get($url)
    {
        return $this->client->request('GET', $url, [
            'auth' => ['IsaiasCardenas', getenv('GITHUB_PASSWORD')]
        ]);
    }
}