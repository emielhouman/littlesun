<?php

include_once(__DIR__ . "/Db.php");

class Member extends User
{
    
        public function getUsersWithRoleId($roleId)
        {
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT id, firstname, lastname FROM users WHERE role_id = :role_id");
            $statement->bindValue(":role_id", $roleId);
            $statement->execute();
            $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    
            return $users;
        }
    
}