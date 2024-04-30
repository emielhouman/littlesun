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
        $statement = $conn->prepare("select * from locations");
        $statement->execute();
        $locations = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $locations;
    }

    public static function getLocationInfo($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from locations where id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $location = $statement->fetch(PDO::FETCH_ASSOC);

        return $location;
    }

    public static function updateLocation($id, $name, $managerId){
        $conn = Db::getConnection();
        
        $statement = $conn->prepare("UPDATE locations SET name = :name, manager_id = :managerId WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->bindValue(":name", $name);
        $statement->bindValue(":managerId", $managerId);
        $statement->execute();

        $statement = $conn->prepare("UPDATE users SET location_id = :id WHERE id = :managerId");
        $statement->bindValue(":id", $id);
        $statement->bindValue(":managerId", $managerId);
        $statement->execute();
    }

    public static function deleteLocation($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("DELETE FROM locations WHERE id = :id");
        $statement->bindValue(":id", $id);
        $result = $statement->execute();

        return $result;
    }
}
