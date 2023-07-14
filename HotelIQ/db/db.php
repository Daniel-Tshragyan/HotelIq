<?php
namespace db;

use PDO;

class DB
{
    private static $instance;
    private static $pdo;

    private function __construct()
    {
        $this::$pdo = new PDO('mysql:host=localhost;dbname=hotel_iq', 'root', '');
    }

    public static function getInstance()
    {
        if(!self::$instance){
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public static function getConnection()
    {
        return self::$pdo;
    }
}
