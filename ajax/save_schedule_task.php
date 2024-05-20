<?php 

include_once(__DIR__ . "/../bootstrap.php");
include_once(__DIR__ . "/../classes/Db.php");

if (!empty($_POST)) {
    $conn = Db::getConnection();
    $statement = $conn->prepare("insert into schedule_tasks (task_id, user_id, date, start_time, end_time) values (:taskId, :userId, :date, :startTime, :endTime)");
    $statement->bindValue(":taskId", $_POST['task_id']);
    $statement->bindValue(":userId", $_POST['user_id']);
    $statement->bindValue(":date", $_POST['date']);
    $statement->bindValue(":startTime", $_POST['start_time']);
    $statement->bindValue(":endTime", $_POST['end_time']);
    $statement->execute();

    $response = [
        'status' => 'success',
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
}