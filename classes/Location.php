<?php

include_once(__DIR__ . "/Db.php");

class Location
{
    private $location;

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        if (empty($location)) {
            throw new Exception("The name of the location can't be empty...");
        }
        $this->location = $location;

        return $this;
    }

    public function save()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into locations (name) values (:location)");

        $location = $this->getLocation();
        $statement->bindValue(":location", $location);
        $result = $statement->execute();

        return $result;
    }

    public static function getAll()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from locations order by id desc");
        $statement->execute();
        $locations = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $locations;
    }

    public static function getLocationWithId($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from locations where id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $location = $statement->fetch(PDO::FETCH_ASSOC);

        return $location;
    }

    public static function updateLocation($id, $name, $userId){
        $conn = Db::getConnection();
        
        $statement = $conn->prepare("update locations set name = :name where id = :id");
        $statement->bindValue(":id", $id);
        $statement->bindValue(":name", $name);
        $statement->execute();

        $statement = $conn->prepare("update users set location_id = :id WHERE id = :userId");
        $statement->bindValue(":id", $id);
        $statement->bindValue(":userId", $userId);
        $statement->execute();
    }

    public static function deleteLocation($id)
    {
        $conn = Db::getConnection();

        $statement = $conn->prepare("delete from locations where id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();

        $statement = $conn->prepare("update users set location_id = 0 where location_id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }
}
