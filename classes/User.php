<?php

include_once(__DIR__ . "/Db.php");

class User
{   
    private $firstname;
    private $lastname;
    private $email;
    private $password;
    private $roleId;
    private $locationId;

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getRoleId()
    {
        return $this->roleId;
    }

    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    public function getLocationId()
    {
        return $this->locationId;
    }

    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    public function canLogin()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select id, password from users where email = :email");

        $email = $this->getEmail();
        $statement->bindValue(":email", $email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($this->getPassword(), $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            return true;
        } else {
            return false;
        }
    }

    public static function getUserInfo($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select firstname, lastname, role_id from users where id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    public static function getUserRole($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select name from roles where id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $role = $statement->fetch(PDO::FETCH_ASSOC);

        return $role;
    }
}
