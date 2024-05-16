<?php

include_once(__DIR__ . "/Db.php");

class Task
{
    private $task;
    private $taskId;
    private $userId;

    public function getTask()
    {
        return $this->task;
    }

    public function setTask($task)
    {
        $this->task = $task;
        return $this;
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function save()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into tasks (name) values (:task)");

        $task = $this->getTask();
        $statement->bindValue(":task", $task);
        $result = $statement->execute();

        return $result;
    }

    public function assignTasks($tasks)
    {
        $conn = Db::getConnection();

        // Remove existing tasks
        $statement = $conn->prepare("delete from user_tasks where user_id = :userId");
        $statement->bindValue(":userId", $this->getUserId());
        $statement->execute();

        // Assign new tasks
        foreach ($tasks as $taskId) {
            $statement = $conn->prepare("insert into user_tasks (user_id, task_id) values (:userId, :taskId)");
            $statement->bindValue(":userId", $this->getUserId());
            $statement->bindValue(":taskId", $taskId);
            $statement->execute();
        }
    }

    public static function deleteTask($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("delete from tasks where id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public static function getAll()
    {
        $conn = Db::getConnection(); 
        $statement = $conn->prepare("select * from tasks order by id desc");
        $statement->execute();
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $tasks;
    }

    public static function getTasksByUserId($userId)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from user_tasks where user_id = :userId");
        $statement->bindValue(":userId", $userId);
        $statement->execute();
        $assignedTasks = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $assignedTasks;
    }

    public static function getTasksFromUserId($userId)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("
            SELECT tasks.* 
            FROM tasks 
            JOIN user_tasks ON tasks.id = user_tasks.task_id 
            WHERE user_tasks.user_id = :userId
        ");
        $statement->bindValue(":userId", $userId);
        $statement->execute();
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $tasks;
    }

    
}
