<?php

include_once(__DIR__ . "/Db.php");

class Schedule {

    public static function getAllTasks() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from schedule_tasks order by date asc, start_time asc");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}
