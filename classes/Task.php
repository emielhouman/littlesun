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

    public function assignTask()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into user_tasks (user_id, task_id) values (:userId, :taskId)");
        $statement->bindValue(":userId", $this->getUserId());
        $statement->bindValue(":taskId", $this->getTaskId());
        $statement->execute();
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

    public static function getAssignedTasks()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from user_tasks");
        $statement->execute();
        $assignedTasks = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $assignedTasks;
    }
}
