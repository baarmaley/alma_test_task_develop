<?php

require_once 'Settings.php';

class ExceptionDataBase extends Exception {}

class db
{

    private $pdo;
    private static $instance;


    private function __construct()
    {
        $str_connect = sprintf('mysql:dbname=%s;host=%s', Settings::DB_BASE, Settings::DB_HOST);

        try{
            $this->pdo  = new PDO($str_connect, Settings::DB_USER, Settings::DB_PASS);
        }catch (PDOException $e){
            throw new ExceptionDataBase($e->getMessage());
        }

    }

    public static function getDB(){

        if(is_null(static::$instance)){
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function createTableUser(){

        $sql = "CREATE TABLE user(
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    firstName VARCHAR (50) NOT NULL,
                    lastName VARCHAR (50) NOT NULL,
                    email VARCHAR (50) NOT NULL,
                    isBlocked INT(1) DEFAULT 0
                    );";

        $this->pdo->exec($sql);

    }

    public function dropTableUser(){

        $sql = "DROP TABLE user;";

        $this->pdo->exec($sql);

    }

    public function existTableUser(){

        $sql = "SELECT 1 FROM user";

        return $this->pdo->prepare($sql)->execute();

    }

    public function insertTableUser(array $row){

        $sql = "INSERT INTO user (firstName, lastName, email, isBlocked) VALUES (?, ?, ?, ?)";

        $instance = $this->pdo->prepare($sql);

        array_map(function($item) use (&$instance){
            $instance->execute($item);
        }, $row);
    }

    public function countUserTable(){

        $sql = "SELECT COUNT(*) FROM user";

        $sth = $this->pdo->prepare($sql);

        $sth->execute();

        return $sth->fetch()[0];

    }

    public function fetchAllTableUser(){

        $sql = "SELECT * FROM user";

        $sth = $this->pdo->prepare($sql);

        $sth->execute();

        return $sth->fetchAll();

    }

    public function fetchRowTableUser($id){

        $sql = "SELECT firstName, lastName, email, isBlocked FROM user WHERE id = ?";

        $sth = $this->pdo->prepare($sql);

        $sth->execute([$id]);

        return $sth->fetch(PDO::FETCH_ASSOC);

    }

    public function updateBan($id, $state){

        $sql = "UPDATE user SET isBlocked = ? WHERE id = ?";

        $sth = $this->pdo->prepare($sql);

        return $sth->execute([$state, $id]);

    }

}