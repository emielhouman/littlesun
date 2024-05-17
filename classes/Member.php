<?php

include_once(__DIR__ . "/Db.php");

class Member extends User
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

        $options = ['cost' => 12];
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

    public function update()
    {
        $conn = Db::getConnection();

        $passwordUpdated = !empty($this->getPassword());
        $statement = $conn->prepare("update users set firstname = :firstname, lastname = :lastname, email = :email, location_id = :locationId" . ($passwordUpdated ? ", password = :password" : "") . " where id = :id");

        $statement->bindValue(":id", $this->getId());
        $statement->bindValue(":firstname", $this->getFirstname());
        $statement->bindValue(":lastname", $this->getLastname());
        $statement->bindValue(":email", $this->getEmail());
        $statement->bindValue(":locationId", $this->getLocationId());

        if ($passwordUpdated) {
            $options = ['cost' => 12];
            $password = password_hash($this->getPassword(), PASSWORD_DEFAULT, $options);
            $statement->bindValue(":password", $password);
        }

        return $statement->execute();
    }

    public static function deleteMember($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("delete from users where id = :id");
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public static function getMemberWithId($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from users where id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $member = $statement->fetch(PDO::FETCH_ASSOC);

        return $member;
    }

    public static function getMemberWithLocationId($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select id, firstname, lastname from users where location_id = :id and role_id = 3");
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        $member = $statement->fetch(PDO::FETCH_ASSOC);

        return $member;
    }

    public static function getAll($locationId = null)
    {
        $conn = Db::getConnection();
        if ($locationId) {
            $statement = $conn->prepare("select id, firstname, lastname, location_id from users where role_id = 3 and location_id = :locationId");
            $statement->bindValue(":locationId", $locationId, PDO::PARAM_INT);
        } else {
            $statement = $conn->prepare("select id, firstname, lastname, location_id from users where role_id = 3");
        }
        $statement->execute();
        $members = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $members;
    }
}


?>