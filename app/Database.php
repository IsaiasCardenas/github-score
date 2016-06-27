<?php

namespace App;

use PDO;
use App\Score;
use Dotenv\Dotenv;

class Database
{
    private $connection;
    private $stringStatement;
    private $sqlStatement;
    private $dotenv;

    public function __construct()
    {
        $this->stringStatement = '';
        $this->dotenv = new Dotenv(__DIR__ . "/..");
        $this->dotenv->load();
        $this->connection = new PDO(
            'mysql:dbname='. getenv('DB_DATABASE').';host=localhost',
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );
    }

    public function getRanking($table)
    {
        $this->stringStatement = 'SELECT * FROM '. $table .' ORDER BY score DESC';
        $this->sqlStatement = $this->connection->prepare($this->stringStatement);
        $this->sqlStatement->setFetchMode(PDO::FETCH_CLASS, Score::class);
        $this->sqlStatement->execute();
        return $this->sqlStatement->fetchAll();
    }

    public function insertUser($user, $score)
    {
        $this->stringStatement = 'INSERT INTO scores(username, score) VALUES(:username, :score);';
        $this->sqlStatement = $this->connection->prepare($this->stringStatement);
        $this->sqlStatement->bindParam(':username', $user);
        $this->sqlStatement->bindParam(':score', $score);

        if (!($this->sqlStatement->execute())) {
            $this->stringStatement = 'UPDATE scores SET score = :score 
            WHERE username = :username;';
            $this->sqlStatement->bindParam(':username', $user);
            $this->sqlStatement->bindParam(':username', $score);
        }
    }
}
