<?php
include_once(__DIR__ . "/bootstrap.php");

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $user = User::getUserInfo($id);

    $roleId = $user['role_id'];
    $role = User::getUserRole($roleId);
} else {
    header("Location: login.php");
}

function getUserInitials($firstname, $lastname)
{
    $firstnameInitial = substr($firstname, 0, 1);
    $lastnameInitial = substr($lastname, 0, 1);
    return $firstnameInitial . $lastnameInitial;
}

$tasksPage = ($roleId == 1) ? "tasks.php" : "assigntask.php";
$userPage = ($roleId == 2) ? "member.php" : "managers.php";
$userlink = ($roleId == 2) ? "HUB Members" : "HUB Managers";

?>
<nav class="fixed z-10">
    <div class="flex flex-col w-72 h-screen bg-black text-gray-50 pt-8 pb-6 pl-4 pr-6">
        <div class="mb-8 pl-2.5 pr-3">
            <a href="index.php"><img class="w-full h-auto" src="assets/logo.svg" alt="Little Sun Logo"></a>
        </div>
        <ul class="navigation__menu">
            <li class="flex gap-4 items-center rounded p-3.5 my-2 hover:bg-gray-500 hover:bg-opacity-25">
                <svg class="w-8 h-auto" id="dashboard" data-name="dashboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 650 650">
                    <path class="fill-current text-yellow-400" d="M549.25,0H100.75C45.2,0,0,45.2,0,100.75v448.5c0,55.55,45.2,100.75,100.75,100.75h448.5c55.55,0,100.75-45.2,100.75-100.75V100.75c0-55.55-45.2-100.75-100.75-100.75ZM100.75,65h448.5c19.71,0,35.75,16.04,35.75,35.75v94.25H65v-94.25c0-19.71,16.04-35.75,35.75-35.75ZM65,549.25v-289.25h130v325h-94.25c-19.71,0-35.75-16.04-35.75-35.75ZM549.25,585h-289.25v-325h325v289.25c0,19.71-16.04,35.75-35.75,35.75Z" />
                </svg>
                <a class="block font-semibold text-lg tracking-wide" href="index.php">Dashboard</a>
            </li>
            <?php if ($roleId !== 3) : ?>
                <?php if ($roleId !== 2) : ?>
                    <li class="flex gap-4 items-center rounded p-3.5 my-2 hover:bg-gray-500 hover:bg-opacity-25">
                        <svg class="w-8 h-auto" id="location" data-name="location" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 650 650">
                            <path class="fill-current text-yellow-400" d="M617.5,650H32.5c-17.95,0-32.5-14.55-32.5-32.5s14.55-32.5,32.5-32.5h16.25v-150.53c0-19.01,0-29.48,2.82-41.19,2.53-10.47,6.67-20.37,12.31-29.41,6.43-10.3,14.25-17.8,27.22-30.23l19.8-18.97v-178.16c0-39.97,0-61.98,9.82-82.53,8.85-18.53,22.83-33.38,40.43-42.95C181.45,0,202.57,0,240.91,0h230.34c38.32,0,59.43,0,79.73,11.03,17.63,9.58,31.62,24.43,40.46,42.95,9.81,20.53,9.81,42.51,9.81,82.42v448.6h16.25c17.95,0,32.5,14.55,32.5,32.5s-14.55,32.5-32.5,32.5ZM448.91,585h87.34V136.4c0-28.4,0-47.16-3.46-54.4-2.86-5.99-7.42-10.9-12.85-13.85-5.78-3.14-23.78-3.14-48.68-3.14h-230.34c-24.93,0-42.94,0-48.72,3.14-5.48,2.98-9.91,7.77-12.82,13.85-3.47,7.26-3.47,26.06-3.47,54.52v115.98c17.2-16.04,29.41-25.64,45.38-30.77,18.01-5.79,37.06-5.79,55.08,0,20.17,6.48,34.36,20.08,60.11,44.77l70.08,67.16c12.94,12.4,20.75,19.89,27.21,30.23,5.64,9.04,9.78,18.93,12.31,29.41,2.84,11.78,2.84,22.85,2.84,41.19v150.53ZM113.75,585h270.16v-150.53c0-14.65-.04-21.87-1.03-25.96-.89-3.68-2.32-7.12-4.26-10.23-2.06-3.3-6.98-8.08-17.04-17.71l-70.08-67.15c-16.91-16.21-29.13-27.92-35.04-29.82-5-1.61-10.29-1.61-15.28,0-5.88,1.89-18.09,13.59-34.97,29.77l-70.13,67.2c-9.31,8.92-14.92,14.3-17.05,17.71-1.95,3.12-3.38,6.56-4.26,10.23-1.01,4.21-1.01,11.94-1.01,25.96v150.53Z" />
                        </svg>
                        <a class="block font-semibold text-lg tracking-wide" href="locations.php">HUB Locations</a>
                    </li>
                <?php endif; ?>
                <li class="flex gap-4 items-center rounded p-3.5 my-2 hover:bg-gray-500 hover:bg-opacity-25">
                    <svg class="w-8 h-auto" id="people" data-name="people" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 650 650">
                        <path class="fill-current text-yellow-400" d="M183.95,254.06c13.92,0,27.32-2.26,39.87-6.42,24.66,28.18,60.88,46.01,101.19,46.01s76.52-17.83,101.19-46.01c12.54,4.16,25.95,6.42,39.87,6.42,70.04,0,127.03-56.99,127.03-127.03S536.1,0,466.05,0c-34.18,0-65.24,13.58-88.1,35.62-16.26-6.99-34.16-10.87-52.95-10.87s-36.69,3.88-52.95,10.87C249.19,13.58,218.13,0,183.95,0,113.9,0,56.91,56.99,56.91,127.03s56.99,127.03,127.03,127.03ZM536.99,127.03c0,39.12-31.82,70.94-70.94,70.94-4.09,0-8.1-.36-12-1.03,3.51-11.98,5.4-24.64,5.4-37.74,0-34.53-13.09-66.06-34.56-89.9,11.61-8.31,25.82-13.21,41.16-13.21,39.12,0,70.94,31.82,70.94,70.94ZM403.36,159.2c0,43.21-35.15,78.36-78.36,78.36s-78.36-35.15-78.36-78.36,35.15-78.36,78.36-78.36,78.36,35.15,78.36,78.36ZM183.95,56.09c15.34,0,29.55,4.9,41.16,13.21-21.47,23.84-34.56,55.37-34.56,89.9,0,13.1,1.89,25.76,5.4,37.74-3.9.67-7.91,1.03-12,1.03-39.12,0-70.94-31.82-70.94-70.94s31.82-70.94,70.94-70.94Z" />
                        <path class="fill-current text-yellow-400" d="M649.45,505.82c-4.33-32.91-14.95-80.64-40.89-121.07-32.7-50.96-81.98-77.9-142.5-77.9-45.84,0-85.21,15.47-115.83,45.11-8.21-.86-16.62-1.32-25.23-1.32s-17.01.46-25.23,1.32c-30.62-29.64-69.99-45.11-115.83-45.11-60.53,0-109.8,26.94-142.5,77.9C15.5,425.19,4.89,472.91.55,505.82c-2.33,17.7,2.77,35.19,13.99,47.98,10.22,11.66,24.76,18.35,39.87,18.35h53.47c-.29,1.93-.57,3.84-.83,5.7-2.62,18.92,2.81,37.73,14.9,51.61,11.37,13.06,27.68,20.55,44.74,20.55h316.62c17.06,0,33.36-7.49,44.74-20.55,12.09-13.87,17.52-32.68,14.9-51.61-.26-1.85-.54-3.76-.83-5.7h53.47c15.12,0,29.65-6.69,39.87-18.35,11.22-12.8,16.32-30.29,13.99-47.98ZM120.52,516.06H56.35c-.2-.62-.35-1.59-.18-2.92,3.59-27.25,12.13-66.38,32.48-98.1,22.5-35.06,53.67-52.1,95.3-52.1,17.3,0,32.78,2.96,46.59,8.92-2.83,1.43-5.64,2.91-8.39,4.49-25.66,14.69-47.77,35.63-65.71,62.24-16.65,24.69-28.08,51.8-35.91,77.47ZM485.75,592.61c-.75.86-1.57,1.3-2.44,1.3H166.69c-.87,0-1.69-.44-2.44-1.3-.68-.78-2.19-3.02-1.63-7.07,4.43-32.04,15.02-78.1,40.31-115.59,28.69-42.54,68.62-63.22,122.07-63.22s93.38,20.68,122.07,63.22c25.28,37.49,35.88,83.55,40.31,115.59.56,4.05-.95,6.3-1.63,7.07ZM593.66,516.06h-64.17c-7.83-25.67-19.26-52.78-35.91-77.47-17.95-26.61-40.06-47.55-65.71-62.24-2.75-1.58-5.56-3.05-8.39-4.49,13.81-5.96,29.29-8.92,46.59-8.92,41.63,0,72.8,17.04,95.29,52.1,20.35,31.72,28.9,70.85,32.49,98.1.17,1.32.02,2.29-.18,2.91Z" />
                    </svg>
                    <a class="block font-semibold text-lg tracking-wide" href="<?php echo $userPage; ?>"><?php echo $userlink; ?></a>
                </li>
                <li class="flex gap-4 items-center rounded p-3.5 my-2 hover:bg-gray-500 hover:bg-opacity-25">
                    <svg class="w-8 h-auto" id="tasks" data-name="tasks" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 650 650">
                        <path class="fill-current text-yellow-400" d="M491.35,254.7h-155.39c-12.26,0-22.2-9.94-22.2-22.2s9.94-22.2,22.2-22.2h155.39c12.26,0,22.2,9.94,22.2,22.2s-9.94,22.2-22.2,22.2Z" />
                        <path class="fill-current text-yellow-400" d="M180.85,276.9c-5.68,0-11.36-2.17-15.7-6.5l-22.2-22.2c-8.67-8.67-8.67-22.73,0-31.39,8.67-8.67,22.73-8.67,31.39,0l6.5,6.5,50.9-50.9c8.67-8.67,22.72-8.67,31.39,0,8.67,8.67,8.67,22.73,0,31.39l-66.6,66.6c-4.34,4.34-10.02,6.5-15.7,6.5Z" />
                        <path class="fill-current text-yellow-400" d="M491.35,461.9h-155.39c-12.26,0-22.2-9.94-22.2-22.2s9.94-22.2,22.2-22.2h155.39c12.26,0,22.2,9.94,22.2,22.2s-9.94,22.2-22.2,22.2Z" />
                        <path class="fill-current text-yellow-400" d="M180.85,484.09c-5.68,0-11.36-2.17-15.7-6.5l-22.2-22.2c-8.67-8.67-8.67-22.73,0-31.39,8.67-8.67,22.73-8.67,31.39,0l6.5,6.5,50.9-50.9c8.67-8.67,22.72-8.67,31.39,0s8.67,22.73,0,31.39l-66.6,66.6c-4.34,4.34-10.02,6.5-15.7,6.5Z" />
                        <path class="fill-current text-yellow-400" d="M545.42,650H104.58c-57.67,0-104.58-46.92-104.58-104.58V104.58C0,46.92,46.92,0,104.58,0h440.84c57.67,0,104.58,46.92,104.58,104.58v440.84c0,57.67-46.92,104.58-104.58,104.58ZM104.58,65c-21.82,0-39.58,17.76-39.58,39.58v440.84c0,21.82,17.76,39.58,39.58,39.58h440.84c21.82,0,39.58-17.76,39.58-39.58V104.58c0-21.82-17.76-39.58-39.58-39.58H104.58Z" />
                    </svg>
                    <a class="block font-semibold text-lg tracking-wide" href="<?php echo $tasksPage; ?>">HUB Tasks</a>
                </li>
            <?php endif; ?>
            <li class="flex gap-4 items-center rounded p-3.5 my-2 hover:bg-gray-500 hover:bg-opacity-25">
                <div class="w-8 h-auto">
                    <img src="./assets/icons/calendar.svg" alt="calendar icon">
                </div>
                <a class="block font-semibold text-lg tracking-wide" href="calendar.php">HUB Schedule</a>
            </li>
        </ul>
        <div class="mt-auto">
            <a href="account.php">
                <div class="flex gap-4 items-center rounded p-3.5 mb-2.5 hover:bg-gray-500 hover:bg-opacity-25">
                    <div class="flex justify-center items-center bg-yellow-400 w-12 h-12 rounded-full">
                        <span class="font-bold tracking-wider text-lg text-black"><?php echo getUserInitials($user["firstname"], $user["lastname"]); ?></span>
                    </div>
                    <div class="leading-snug">
                        <span class="block font-bold"><?php echo $user["firstname"] . " " . $user["lastname"] ?></span>
                        <span class="block text-sm text-yellow-400"><?php echo $role["name"] ?></span>
                    </div>
                </div>
            </a>
            <div class="font-bold uppercase rounded hover:bg-gray-500 hover:bg-opacity-25">
                <a class="block underline p-3.5" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</nav>