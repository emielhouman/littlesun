<?php

class Db
{
    private static $conn;

    public static function getConnection()
    {
        include_once(__DIR__ . "/../settings/settings.php");

        if (self::$conn === null) {
            self::$conn = new PDO('mysql:host=' . DB_SETTINGS['host'] . ';dbname='. DB_SETTINGS['db'], DB_SETTINGS['user'], DB_SETTINGS['password']);
            return self::$conn;
        } else {
            return self::$conn;
        }
    }
}
