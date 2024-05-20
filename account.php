<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

$id = $_SESSION['user_id'];
$user = User::getUserInfo($id);

if (!$user) {
    echo "User not found.";
    exit();
}

$roleId = $user['role_id'];
$role = User::getUserRole($roleId);
$tasks = Task::getTasksFromUserId($id);
$memberLocation = !empty($user['location_id']) ? Location::getLocationWithId($user['location_id']) : null;
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
        <div class="ml-72 px-14 py-10 flex-1">
            <div class="flex items-center space-x-4 pb-12">
                <div>
                    <div class="w-14 h-14 p-3.5 rounded bg-black/80">
                        <svg class="w-full h-auto" id="location" data-name="location" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 650 650">
                            <path class="fill-current text-yellow-400" d="M617.5,650H32.5c-17.95,0-32.5-14.55-32.5-32.5s14.55-32.5,32.5-32.5h16.25v-150.53c0-19.01,0-29.48,2.82-41.19,2.53-10.47,6.67-20.37,12.31-29.41,6.43-10.3,14.25-17.8,27.22-30.23l19.8-18.97v-178.16c0-39.97,0-61.98,9.82-82.53,8.85-18.53,22.83-33.38,40.43-42.95C181.45,0,202.57,0,240.91,0h230.34c38.32,0,59.43,0,79.73,11.03,17.63,9.58,31.62,24.43,40.46,42.95,9.81,20.53,9.81,42.51,9.81,82.42v448.6h16.25c17.95,0,32.5,14.55,32.5,32.5s-14.55,32.5-32.5,32.5ZM448.91,585h87.34V136.4c0-28.4,0-47.16-3.46-54.4-2.86-5.99-7.42-10.9-12.85-13.85-5.78-3.14-23.78-3.14-48.68-3.14h-230.34c-24.93,0-42.94,0-48.72,3.14-5.48,2.98-9.91,7.77-12.82,13.85-3.47,7.26-3.47,26.06-3.47,54.52v115.98c17.2-16.04,29.41-25.64,45.38-30.77,18.01-5.79,37.06-5.79,55.08,0,20.17,6.48,34.36,20.08,60.11,44.77l70.08,67.16c12.94,12.4,20.75,19.89,27.21,30.23,5.64,9.04,9.78,18.93,12.31,29.41,2.84,11.78,2.84,22.85,2.84,41.19v150.53ZM113.75,585h270.16v-150.53c0-14.65-.04-21.87-1.03-25.96-.89-3.68-2.32-7.12-4.26-10.23-2.06-3.3-6.98-8.08-17.04-17.71l-70.08-67.15c-16.91-16.21-29.13-27.92-35.04-29.82-5-1.61-10.29-1.61-15.28,0-5.88,1.89-18.09,13.59-34.97,29.77l-70.13,67.2c-9.31,8.92-14.92,14.3-17.05,17.71-1.95,3.12-3.38,6.56-4.26,10.23-1.01,4.21-1.01,11.94-1.01,25.96v150.53Z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="font-extrabold text-1xl">HUB User</h2>
                    <h2 class="font-extrabold text-4xl text-yellow-400"><?php echo htmlspecialchars($user["firstname"] . " " . $user["lastname"]); ?></h2>
                </div>
            </div>

            <?php if (isset($_SESSION['success_message'])) : ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($_SESSION['success_message']); ?></p>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <div class="bg-white border-2 border-gray-300 p-6 rounded-lg shadow-md">
                <h3 class="font-extrabold text-2xl pt-6 text-yellow-400">Profile Information</h3>
                <div class="mt-4">
                    <p class="font-semibold text-lg">First Name: <?php echo htmlspecialchars($user['firstname']); ?></p>
                    <p class="font-semibold text-lg">Last Name: <?php echo htmlspecialchars($user['lastname']); ?></p>
                    <p class="font-semibold text-lg">Role: <?php echo htmlspecialchars($role['name']); ?></p>
                </div>
                <?php if ($roleId == 3): ?>
                    <h3 class="font-extrabold text-2xl pt-6 text-yellow-400">Assigned Tasks</h3>
                    <ul class="list-disc pl-5 mt-4">
                        <?php foreach ($tasks as $task) : ?>
                            <li class="font-semibold text-lg"><?php echo htmlspecialchars($task['name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>
