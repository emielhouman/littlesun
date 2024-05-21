<?php 

include_once(__DIR__ . "/../bootstrap.php");
include_once(__DIR__ . "/../classes/Db.php");

if (!empty($_POST)) {
    $task = new ScheduledTask();
    $task->setTaskId($_POST['task_id']);
    $task->setUserId($_POST['user_id']);
    $task->setDate($_POST['date']);
    $task->setStartTime($_POST['start_time']);
    $task->setEndTime($_POST['end_time']);
    
    $task->save();

    $taskInfo = Task::getTaskWithId($task->getTaskId());

    $response = [
        'status' => 'success',
        'body' => [
            'task' => $taskInfo['name'],
            'task_id' => $task->getTaskId(),
            'user_id' => $task->getUserId(),
            'date' => $task->getDate(),
            'start_time' => $task->getStartTime(),
            'end_time' => $task->getEndTime(),
        ],
        'message' => 'The new task has been scheduled!'
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
}