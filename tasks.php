<?php
session_start();
// Include the bootstrap file which includes other necessary files like Task.php
include_once(__DIR__ . "/bootstrap.php");


// Create an instance of Task class
$taskManager = new Task();

// Check if form is submitted to create a task
if (isset($_POST['create_task'])) {
    if (!empty($_POST['task_name'])) {
        $taskName = $_POST['task_name'];
        $taskManager->addTask($taskName);

        // Redirect to avoid form resubmission
        header("Location: tasks.php");
        exit();
    } else {
        echo "Task name is required.";
    }
}

// Check if delete button is clicked
if (isset($_POST['delete_task'])) {
    $taskId = $_POST['task_id'];
    $taskManager->deleteTask($taskId);
    
    // Redirect to avoid form resubmission
    header("Location: tasks.php");
    exit();
}

// Fetch all tasks
$tasks = $taskManager->getAllTasks();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex">
        <?php include_once(__DIR__ . "/nav.inc.php") ?>
        <div class="mx-14 my-10">
            <h2 class="font-bold text-3xl pt-1">Create Task</h2>
            <!-- Formulier voor het maken van een nieuwe taak -->
            <form method="post" action="tasks.php" class="mt-6">
                <div class="mb-4">
                    <label for="task_name" class="block text-sm font-semibold mb-1">Task Name:</label>
                    <input type="text" name="task_name" id="task_name" class="border border-gray-300 rounded px-3 py-2 w-full" required>
                </div>
                <button type="submit" name="create_task" class="bg-yellow-400 text-white font-semibold py-2 px-4 rounded hover:bg-yellow-500">Create Task</button>
            </form>
            <!-- Lijst met taken -->
            <!-- Lijst met taken -->
<div class="mt-8">
    <h2 class="font-bold text-3xl pt-1">Task List</h2>
    <ul class="mt-4">
        <?php foreach ($tasks as $task): ?>
            <li class="text-lg">
                <?= $task['name'] ?>
                <!-- Delete button with confirmation dialog -->
                <form method="post" action="tasks.php" style="display:inline;">
                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                    <button type="submit" name="delete_task" onclick="return confirm('Are you sure you want to delete this task?');" class="bg-red-500 text-white font-semibold py-1 px-2 rounded hover:bg-red-600">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

    </div>
</body>
</html>
