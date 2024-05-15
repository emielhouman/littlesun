<?php

include_once(__DIR__ . "/Db.php");

class Member extends User
{
    public static function getAll()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select id, firstname, lastname, location_id from users where role_id = 3");
        $statement->execute();
        $members = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $members;
    }
}
