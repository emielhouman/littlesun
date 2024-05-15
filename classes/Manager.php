<?php

include_once(__DIR__ . "/Db.php");

class Manager extends User
{
    public function save()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into users (firstname, lastname, email, password, role_id, location_id) values (:firstname, :lastname, :email, :password, :roleId, :locationId)");

        $firstname = $this->getFirstname();
        $lastname = $this->getLastname();
        $email = $this->getEmail();
        $password = $this->getPassword();
        $roleId = $this->getRoleId();
        $locationId = $this->getLocationId();

        $options = [
            'cost' => 12
        ];
        $password = password_hash($password, PASSWORD_DEFAULT, $options);

        $statement->bindValue(":firstname", $firstname);
        $statement->bindValue(":lastname", $lastname);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $password);
        $statement->bindValue(":roleId", $roleId);
        $statement->bindValue(":locationId", $locationId);
        $result = $statement->execute();

        return $result;
    }

    public static function getManagerWithLocationId($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select id, firstname, lastname from users where location_id = :id and role_id = 2");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $manager = $statement->fetch(PDO::FETCH_ASSOC);

        return $manager;
    }

    public static function getAll()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select id, firstname, lastname, location_id from users where role_id = 2");
        $statement->execute();
        $managers = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $managers;
    }
}
