<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

$task = new Task();
$tasks = $task->getAllTasks();

$member = new Member();
$users = $member->getUsersWithRoleId(3);
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
  <div class="flex">
    <?php include_once(__DIR__ . "/nav.inc.php") ?>
    <div class="mx-14 my-10">
      <h2 class="font-bold text-3xl pt-1">Assign Task</h2>
      
      <form method="post" action="" class="mt-6">
        
        <div class="mb-4">
          <label for="dropdown1" class="block text-sm font-semibold mb-1">Members</label>
          <select name="dropdown1" id="dropdown1" class="border border-gray-300 rounded px-3 py-2 w-full">
        <?php foreach ($users as $user): ?> 
                <option value="<?php echo $user['id']; ?>"><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></option>
        <?php endforeach; ?>
          </select>
        </div>
       
        <div class="mb-4">
    <label for="dropdown2" class="block text-sm font-semibold mb-1">Tasks</label>
    <select name="dropdown2" id="dropdown2" class="border border-gray-300 rounded px-3 py-2 w-full">
        <?php foreach ($tasks as $task): ?>
            <option value="<?php echo $task['id']; ?>"><?php echo $task['name']; ?></option>
        <?php endforeach; ?>
    </select>
</div>

        
    <button type="submit" name="assign_task" class="bg-yellow-400 text-black-400 font-semibold py-2 px-4 rounded hover:bg-black-600 hover:text-white">Assign Task</button>
      </form>
    </div>
  </div>
</body>

</html>
