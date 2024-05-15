<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

if (isset($_GET['id']) && isset($_GET['mode'])) {
    $location = Location::getLocationWithId($_GET['id']);
    $locationManager = Manager::getManagerWithLocationId($_GET['id']);
    $managers = Manager::getAll();

    usort($managers, "sortByLocationId");

    if ($_GET['mode'] === "edit" && !empty($_POST)) {
        $id = $_GET['id'];
        $location = $_POST['location'];
        $userId = $_POST['user_id'];

        Location::updateLocation($id, $location, $userId);

        header("Location: location.php?id=$id&mode=read");
        exit;
    } else if ($_GET['mode'] === "read" && !empty($_POST)) {
        header("Location: locations.php");
        exit;
    }
} else {
    header("Location: locations.php");
    exit;
}

function sortByLocationId($a, $b)
{
    return $a['location_id'] - $b['location_id'];
}

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
        <div id="popup-scrn" class="w-screen h-screen items-center justify-center absolute z-10 top-0 left-0 bg-black/50" style="display: none;">
            <div class="w-2/5 p-12 bg-white rounded-lg relative">
                <h3 class="font-bold text-2xl pb-8">Select a HUB Manager:</h3>
                <input class="w-full mb-6 px-3.5 py-2 text-lg rounded border-gray-300 border-2" type="text" id="search" name="q" placeholder="Search...">
                <div class="flex flex-col gap-4">
                    <?php foreach ($managers as $manager) : ?>
                        <?php $managerLocation = Location::getLocationWithId($manager['location_id']); ?>
                        <button class="flex items-center justify-between p-3.5 rounded shadow border-2 border-gray-300 <?php echo ($manager['location_id'] === 0 ? "bg-white" : "bg-gray-50") ?> select__btn" type="button" data-id="<?php echo $manager['id'] ?>">
                            <div class="flex items-center gap-4">
                                <div class="flex justify-center items-center bg-yellow-400 w-12 h-12 rounded-full">
                                    <span class="font-bold tracking-wider text-lg text-black"><?php echo getUserInitials($manager["firstname"], $manager["lastname"]); ?></span>
                                </div>
                                <div class="flex flex-col items-start">
                                    <span class="font-extrabold manager"><?php echo $manager['firstname'] . " " . $manager['lastname'] ?></span>
                                    <span class="font-semibold text-sm text-yellow-400"><?php echo ($manager['location_id'] === 0 ? "Select a HUB Location" : $managerLocation['name']) ?></span>
                                </div>
                            </div>
                            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 650 650">
                                <path d="M325,650c-42.3,0-83.52-7.98-122.51-23.72-40.43-16.32-76.62-40.28-107.55-71.22-30.93-30.94-54.9-67.12-71.22-107.55C7.98,408.52,0,367.3,0,325s7.98-83.52,23.72-122.51c16.32-40.43,40.28-76.62,71.22-107.55s67.12-54.9,107.55-71.22C241.48,7.98,282.7,0,325,0s83.52,7.98,122.51,23.72c40.43,16.32,76.62,40.28,107.55,71.22,30.93,30.94,54.9,67.12,71.22,107.55,15.74,38.99,23.72,80.21,23.72,122.51s-7.98,83.52-23.72,122.51c-16.32,40.43-40.28,76.62-71.22,107.55-30.94,30.93-67.12,54.9-107.55,71.22-38.99,15.74-80.21,23.72-122.51,23.72ZM325,56.15c-72.14,0-139.74,27.87-190.36,78.49-50.61,50.61-78.49,118.22-78.49,190.36s27.87,139.74,78.49,190.36c50.61,50.61,118.22,78.49,190.36,78.49s139.74-27.87,190.36-78.49c50.61-50.61,78.49-118.22,78.49-190.36s-27.87-139.74-78.49-190.36c-50.61-50.61-118.22-78.49-190.36-78.49Z" />
                                <path d="M443.98,296.92h-90.9s0-90.9,0-90.9c0-15.51-12.57-28.08-28.08-28.08s-28.08,12.57-28.08,28.08v90.9s-90.9,0-90.9,0c-15.51,0-28.08,12.57-28.08,28.08s12.57,28.08,28.08,28.08h90.9s0,90.9,0,90.9c0,15.51,12.57,28.08,28.08,28.08s28.08-12.57,28.08-28.08v-90.9s90.9,0,90.9,0c15.51,0,28.08-12.57,28.08-28.08s-12.57-28.08-28.08-28.08Z" />
                            </svg>
                        </button>
                    <?php endforeach; ?>
                </div>
                <button class="w-7 h-7 m-3.5 p-px absolute right-0 top-0 font-bold opacity-25 leading-none close__btn">
                    <img class="w-full h-auto" src="./assets/close.svg" alt="close icon">
                </button>
            </div>
        </div>
        <div class="ml-72 px-14 py-10 flex-1">
            <div class="flex justify-between mb-12">
                <h2 class="font-bold text-4xl">HUB Location: <span class="font-normal"><?php echo $location['name'] ?></span></h2>
                <a href="location.php?id=<?php echo $location['id'] ?>&mode=<?php echo ($_GET['mode'] === "edit" ? "read" : "edit") ?>" class="h-12 flex items-center justify-center gap-2 px-4 font-semibold uppercase rounded cursor-pointer <?php echo ($_GET['mode'] === "edit" ? "bg-gray-200 border-gray-200" : "bg-yellow-400 border-yellow-400") ?>">
                    <?php if ($_GET['mode'] === "edit") : ?>
                        <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 800 800">
                            <path class="w-full h-auto" d="M699.33,273.7c-16.42-38.65-39.89-73.39-69.77-103.27-29.88-29.87-64.62-53.35-103.27-69.77-40.1-17.03-82.59-25.67-126.3-25.67s-86.2,8.64-126.3,25.67c-38.65,16.42-73.39,39.89-103.27,69.77-29.87,29.88-53.35,64.62-69.77,103.27-17.03,40.1-25.67,82.59-25.67,126.3s8.64,86.2,25.67,126.3c16.42,38.65,39.89,73.39,69.77,103.27,29.88,29.87,64.62,53.35,103.27,69.76,40.1,17.03,82.59,25.67,126.3,25.67s86.2-8.64,126.3-25.67c38.65-16.42,73.39-39.89,103.27-69.77,29.87-29.88,53.35-64.62,69.77-103.27,17.03-40.1,25.67-82.59,25.67-126.3s-8.64-86.2-25.67-126.3ZM546.09,600.37c-42.6,31.35-92.93,47.87-146.09,47.87-65.99,0-128.24-25.91-175.29-72.95-47.04-47.04-72.95-109.29-72.95-175.29,0-53.16,16.52-103.49,47.87-146.09l346.46,346.46ZM648.24,400c0,53.16-16.52,103.49-47.87,146.09L253.91,199.63c42.6-31.35,92.93-47.87,146.09-47.87,65.99,0,128.24,25.91,175.29,72.95,47.04,47.04,72.95,109.29,72.95,175.29Z" />
                        </svg>
                        <span>Undo changes</span>
                    <?php elseif ($_GET['mode'] === "read") : ?>
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 800 800">
                            <path class="w-full h-auto" style="fill-rule: evenodd" d="M664.14,101.08c-34.78-34.78-91.16-34.78-125.94,0L117.96,521.32c-12.43,12.43-20.9,28.26-24.35,45.5l-17.43,87.13c-8.31,41.54,28.32,78.16,69.86,69.86l87.13-17.43c17.24-3.45,33.07-11.92,45.5-24.35l420.24-420.24c34.78-34.78,34.78-91.16,0-125.93l-34.77-34.78ZM580.18,143.06c11.59-11.59,30.39-11.59,41.98,0l34.77,34.78c11.59,11.59,11.59,30.39,0,41.98l-79.3,79.3-76.75-76.75,79.3-79.3ZM458.9,264.34l-298.96,298.96c-4.14,4.14-6.97,9.42-8.12,15.17l-17.43,87.13,87.13-17.43c5.75-1.15,11.02-3.97,15.17-8.12l298.96-298.96-76.75-76.75Z" />
                        </svg>
                        <span>Edit location</span>
                    <?php endif; ?>
                </a>
            </div>
            <form class="w-full grid grid-cols-2 gap-x-14 gap-y-6 p-16 border-2 rounded-lg shadow-md" action="" method="post">
                <div class="flex flex-col gap-1.5">
                    <label class="font-semibold text-lg" for="location">HUB Location:</label>
                    <input class="w-full h-12 px-2.5 text-lg opacity-100 rounded border-2 border-gray-300 <?php echo ($_GET['mode'] === "edit" ? "bg-white" : "bg-gray-100") ?>" id="location" name="location" type="text" value="<?php echo $location["name"] ?>" <?php echo ($_GET['mode'] === "edit" ? "" : "disabled") ?>>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="font-semibold text-lg" for="manager">HUB Manager:</label>
                    <input class="manager__input" id="manager" type="hidden" name="user_id" value="<?php echo (!empty($locationManager)) ? $locationManager['id'] : "0" ?>">
                    <button class="w-full h-12 flex items-center justify-between px-2.5 rounded border-2 border-gray-300 <?php echo ($_GET['mode'] === "edit" ? "bg-white" : "bg-gray-100") ?> manager__btn" type="button" <?php echo ($_GET['mode'] === "edit" ? "" : "disabled") ?>>
                        <?php if (!empty($locationManager)) : ?>
                            <span class="text-lg"><?php echo $locationManager['firstname'] . " " . $locationManager['lastname'] ?></span>
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 650 650">
                                <path d="M325,650c-43.7,0-86.18-8.63-126.27-25.66-38.66-16.42-73.41-39.9-103.29-69.78-29.88-29.88-53.36-64.63-69.78-103.29C8.63,411.18,0,368.7,0,325s8.63-86.18,25.66-126.27c16.42-38.66,39.9-73.41,69.78-103.29,29.88-29.88,64.63-53.36,103.29-69.78C238.82,8.63,281.3,0,325,0s86.18,8.63,126.27,25.66c38.66,16.42,73.41,39.9,103.29,69.78,29.88,29.88,53.36,64.63,69.78,103.29,17.03,40.09,25.66,82.57,25.66,126.27s-8.63,86.18-25.66,126.27c-16.42,38.66-39.9,73.41-69.78,103.29-29.88,29.88-64.63,53.36-103.29,69.78-40.09,17.03-82.57,25.66-126.27,25.66ZM325,56.09c-71.5,0-138.94,28.06-189.89,79.02-50.95,50.95-79.02,118.39-79.02,189.89s28.06,138.94,79.02,189.89c50.95,50.95,118.39,79.02,189.89,79.02s138.94-28.06,189.89-79.02c50.95-50.95,79.02-118.39,79.02-189.89s-28.06-138.94-79.02-189.89c-50.95-50.95-118.39-79.02-189.89-79.02Z" />
                                <path d="M364.66,325l64.21-64.21c10.95-10.95,10.95-28.71,0-39.66-10.95-10.95-28.71-10.95-39.66,0l-64.21,64.21-64.21-64.21c-10.95-10.95-28.71-10.95-39.66,0-10.95,10.95-10.95,28.71,0,39.66l64.21,64.21-64.21,64.21c-10.95,10.95-10.95,28.71,0,39.66,5.48,5.48,12.65,8.21,19.83,8.21s14.35-2.74,19.83-8.21l64.21-64.21,64.21,64.21c5.48,5.48,12.65,8.21,19.83,8.21s14.35-2.74,19.83-8.21c10.95-10.95,10.95-28.71,0-39.66l-64.21-64.21Z" />
                            </svg>
                        <?php else : ?>
                            <span class="text-lg">Select a HUB Manager</span>
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 650 650">
                                <path d="M325,650c-42.3,0-83.52-7.98-122.51-23.72-40.43-16.32-76.62-40.28-107.55-71.22-30.93-30.94-54.9-67.12-71.22-107.55C7.98,408.52,0,367.3,0,325s7.98-83.52,23.72-122.51c16.32-40.43,40.28-76.62,71.22-107.55s67.12-54.9,107.55-71.22C241.48,7.98,282.7,0,325,0s83.52,7.98,122.51,23.72c40.43,16.32,76.62,40.28,107.55,71.22,30.93,30.94,54.9,67.12,71.22,107.55,15.74,38.99,23.72,80.21,23.72,122.51s-7.98,83.52-23.72,122.51c-16.32,40.43-40.28,76.62-71.22,107.55-30.94,30.93-67.12,54.9-107.55,71.22-38.99,15.74-80.21,23.72-122.51,23.72ZM325,56.15c-72.14,0-139.74,27.87-190.36,78.49-50.61,50.61-78.49,118.22-78.49,190.36s27.87,139.74,78.49,190.36c50.61,50.61,118.22,78.49,190.36,78.49s139.74-27.87,190.36-78.49c50.61-50.61,78.49-118.22,78.49-190.36s-27.87-139.74-78.49-190.36c-50.61-50.61-118.22-78.49-190.36-78.49Z" />
                                <path d="M443.98,296.92h-90.9s0-90.9,0-90.9c0-15.51-12.57-28.08-28.08-28.08s-28.08,12.57-28.08,28.08v90.9s-90.9,0-90.9,0c-15.51,0-28.08,12.57-28.08,28.08s12.57,28.08,28.08,28.08h90.9s0,90.9,0,90.9c0,15.51,12.57,28.08,28.08,28.08s28.08-12.57,28.08-28.08v-90.9s90.9,0,90.9,0c15.51,0,28.08-12.57,28.08-28.08s-12.57-28.08-28.08-28.08Z" />
                            </svg>
                        <?php endif; ?>
                    </button>
                </div>
                <div class="pt-8 col-span-2">
                    <input class="w-full h-12 font-bold uppercase rounded border-2 cursor-pointer <?php echo ($_GET['mode'] === "edit" ? "bg-yellow-400 border-yellow-400" : "bg-gray-200 border-gray-200") ?>" type="submit" value="<?php echo ($_GET['mode'] === "edit" ? "Update location" : "Go back") ?>">
                </div>
            </form>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>