<?php

require_once(__DIR__ . "/Db.php");

class Task
{
    private $conn;

    public function addTask($taskName)
    {
        $this->conn = Db::getConnection(); // Establish connection
        $stmt = $this->conn->prepare("INSERT INTO tasks (name) VALUES (:name)");
        $stmt->bindValue(':name', $taskName);
        return $stmt->execute();
    }

   
    public function getAllTasks()
    {
        $this->conn = Db::getConnection(); // Establish connection
        $stmt = $this->conn->query("SELECT * FROM tasks");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    public function deleteTask($taskId)
    {
        $this->conn = Db::getConnection(); // Establish connection
        $stmt = $this->conn->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $taskId);
        return $stmt->execute();
    }
}


?>
