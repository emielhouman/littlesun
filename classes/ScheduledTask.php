<?php

include_once(__DIR__ . "/Db.php");

class ScheduledTask
{
    private $taskId;
    private $userId;
    private $date;
    private $startTime;
    private $endTime;

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

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function save()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into scheduled_tasks (task_id, user_id, date, start_time, end_time) values (:taskId, :userId, :date, :startTime, :endTime)");

        $statement->bindValue(":taskId", $this->getTaskId());
        $statement->bindValue(":userId", $this->getUserId());
        $statement->bindValue(":date", $this->getDate());
        $statement->bindValue(":startTime", $this->getStartTime());
        $statement->bindValue(":endTime", $this->getEndTime());

        $result = $statement->execute();
        return $result;
    }

    public static function getAll()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from scheduled_tasks order by date asc, start_time asc");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getHoursWorked($userId, $startDate, $endDate)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT TIMESTAMPDIFF(HOUR, start_time, end_time) as hours FROM scheduled_tasks WHERE user_id = :userId AND date BETWEEN :startDate AND :endDate");
        $statement->bindValue(":userId", $userId);
        $statement->bindValue(":startDate", $startDate);
        $statement->bindValue(":endDate", $endDate);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $totalHours = 0;
        foreach ($result as $row) {
            $totalHours += $row['hours'];
        }

        return $totalHours;
    }
}
