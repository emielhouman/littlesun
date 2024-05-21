<?php
include_once(__DIR__ . "/bootstrap.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $roleId = $_SESSION['role_id'];

    $user = User::getUserDetails($userId);
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

$navigationItems = [
    1 => [
        ["icon" => "dashboard.svg", "label" => "Dashboard", "link" => "index.php"],
        ["icon" => "location.svg", "label" => "HUB Locations", "link" => "locations.php"],
        ["icon" => "people.svg", "label" => "HUB Managers", "link" => "managers.php"],
        ["icon" => "tasks.svg", "label" => "HUB Tasks", "link" => "tasks.php"],
    ],
    2 => [
        ["icon" => "dashboard.svg", "label" => "Dashboard", "link" => "index.php"],
        ["icon" => "schedule.svg", "label" => "HUB Schedule", "link" => "schedule.php"],
        ["icon" => "people.svg", "label" => "HUB Members", "link" => "member.php"],
        ["icon" => "tasks.svg", "label" => "HUB Tasks", "link" => "assigntask.php"],
        
    ],
    3 => [
        ["icon" => "dashboard.svg", "label" => "Dashboard", "link" => "timeoff.php"],
        ["icon" => "schedule.svg", "label" => "HUB Schedule", "link" => "schedule.php"],
    ],
];

$navigationItems = $navigationItems[$roleId];

?>
<nav class="fixed z-10">
    <div class="h-screen w-72 flex flex-col bg-black text-gray-50 pt-8 pb-6 pl-4 pr-6">
        <div class="mb-8 px-2.5">
            <a href="index.php"><img class="w-full h-auto" src="assets/logo.svg" alt="Little Sun Logo"></a>
        </div>
        <ul class="navigation__menu">
            <?php foreach ($navigationItems as $item) : ?>
                <li class="flex gap-4 items-center rounded p-3.5 my-2 hover:bg-gray-500 hover:bg-opacity-25">
                    <div class="w-8 h-auto">
                        <img src="./assets/icons/<?php echo $item['icon']; ?>" alt="<?php echo strtolower($item['label']); ?> icon">
                    </div>
                    <a class="block font-semibold text-lg tracking-wide" href="<?php echo $item['link']; ?>"><?php echo $item['label']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="mt-auto">
            <a href="account.php">
                <div class="mb-2.5 p-3.5 flex gap-4 items-center rounded hover:bg-gray-500 hover:bg-opacity-25">
                    <div class="h-12 w-12 flex justify-center items-center bg-yellow-400 rounded-full">
                        <span class="font-extrabold tracking-wider text-lg text-black"><?php echo getUserInitials($user["firstname"], $user["lastname"]); ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold"><?php echo $user["firstname"] . " " . $user["lastname"] ?></span>
                        <span class="text-sm text-yellow-400"><?php echo $role["name"] ?></span>
                    </div>
                </div>
            </a>
            <div class="font-extrabold uppercase rounded hover:bg-gray-500 hover:bg-opacity-25">
                <a class="block p-3.5 underline" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</nav>