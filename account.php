<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

$id = $_SESSION['user_id'];
$user = User::getUserInfo($id);

$roleId = $user['role_id'];
$role = User::getUserRole($roleId);
$tasks = Task::getTasksFromUserId($id);

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
        <div id="popup-scrn" class="w-screen h-screen items-center justify-center absolute z-10 top-0 left-0 bg-black/50" style="display: none;"></div>
        <div class="ml-72 px-14 py-10 flex-1 flex justify-center items-center">
            <?php if ($roleId == 3): ?>
                <div class="bg-black border-4 border-yellow-400 p-8 rounded-lg w-1/2 h-auto">
                    <div class="flex items-center gap-4 pb-6">
                        <div class="bg-yellow-400 w-14 h-14 rounded-full flex justify-center items-center">
                            <span class="text-black font-bold text-lg"><?php echo getUserInitials($user["firstname"], $user["lastname"]); ?></span>
                        </div>
                        <div>
                            <span class="block font-bold text-lg text-white">
                                <?php echo $user["firstname"] . " " . $user["lastname"]; ?>
                            </span>
                            <span class="block text-sm text-yellow-400">
                                <?php echo $role["name"]; ?>
                            </span>
                        </div>
                    </div>
                    <h3 class="font-extrabold text-2xl pt-6 text-yellow-400">Assigned Tasks</h3>
                    <ul class="list-disc pl-5 text-white mt-4">
                        <?php foreach ($tasks as $task) : ?>
                            <li class="font-semibold text-lg"><?php echo htmlspecialchars($task['name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>
