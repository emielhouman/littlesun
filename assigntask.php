<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

if (isset($_POST['assign__task'])) {
  $task = new Task();
  $task->setTaskId($_POST['task']);
  $task->setUserId($_POST['member']);
  $task->assignTask();
}

$tasks = Task::getAll();
$assignedTasks = Task::getAssignedTasks();
$members = Member::getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Little Sun</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="flex w-screen relative">
    <?php include_once(__DIR__ . "/nav.inc.php") ?>
    <div class="ml-72 px-14 py-10 flex-1">
      <h2 class="font-extrabold text-4xl pb-12">Assign Task</h2>
      <form class="w-1/3 grid grid-rows-2 gap-6" method="post" action="">
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-lg" for="member">Select a HUB Member:</label>
          <select class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="member" name="member">
            <?php foreach ($members as $member) : ?>
              <option value="<?php echo $member['id']; ?>"><?php echo $member['firstname'] . ' ' . $member['lastname']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-lg" for="task">Select a HUB Task:</label>
          <select class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="task" name="task">
            <?php foreach ($tasks as $task) : ?>
              <option value="<?php echo $task['id']; ?>"><?php echo $task['name']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="pt-2">
          <input class="w-full h-12 font-bold uppercase cursor-pointer rounded border-2 border-yellow-400 bg-yellow-400" name="assign__task" type="submit" value="Assign task">
        </div>
      </form>
      

    </div>
  </div>
</body>

</html>