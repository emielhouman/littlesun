<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

$showPopup = false;

if (isset($_POST['add_location'])) {
    try {
        $location = new Location();
        $location->setLocation($_POST['location']);

        $location->save();
        $success = "Location added!";
    } catch (\Throwable $th) {
        $error = $th->getMessage();
    }
}

if (isset($_POST['edit_location'])) {
    header("Location: location.php?id=" . $_POST['id'] . "&mode=edit");
}

if (isset($_POST['delete_location'])) {
    try {
        $result = Location::deleteLocation($_POST['id']);
        $success = "Location deleted!";
    } catch (\Throwable $th) {
        $error = $th->getMessage();
    }
}

$locations = Location::getAll();

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
    <div class="flex w-full relative">
        <?php include_once(__DIR__ . "/nav.inc.php") ?>
        <div class="mx-14 my-10 flex-1">
            <h2 class="font-bold text-3xl pt-1 pb-10">HUB Locations</h2>
            <div>
                <div class="flex justify-between w-full pb-8">
                    <form class="w-10/12" action="" method="get">
                        <input class="w-2/5 px-3 py-1.5 rounded border-gray-300 border-2" type="text" id="search" name="q" placeholder="Search...">
                        <button class="px-3 py-1.5 rounded border-gray-300 border-2 bg-gray-100" type="submit">Search</button>
                    </form>
                    <button class="w-2/12 px-3 py-1.5 font-bold rounded border-gray-300 border-2 bg-gray-100">ADD LOCATION</button>
                </div>
                <div class="w-full h-full hidden items-center justify-center absolute top-0 left-0 z-10 bg-black/50">
                    <div class="p-8 bg-white rounded-lg relative">
                        <h3 class="font-bold text-xl pb-6">Add location:</h3>
                        <form action="" method="post">
                            <input class="w-72 px-3 py-1.5 rounded border-gray-300 border-2" type="text" id="name" name="location" placeholder="Location...">
                            <input class="px-3 py-1.5 font-bold rounded cursor-pointer bg-gray-100 border-gray-300 border-2" type="submit" name="add_location" value="ADD">
                        </form>
                        <button class="leading-none font-bold mx-2.5 my-1.5 absolute right-0 top-0">
                            <span>x</span>
                        </button>
                    </div>
                </div>
                <div class="w-full border">
                    <div class="flex">
                        <span class="w-4/12 px-3 py-2 font-semibold border border-gray-200 bg-gray-100">HUB Location</span>
                        <span class="w-6/12 px-3 py-2 font-semibold border border-gray-200 bg-gray-100">HUB Manager</span>
                        <span class="w-2/12 px-3 py-2 font-semibold border border-gray-200 bg-gray-100">Actions</span>
                    </div>
                    <?php foreach ($locations as $location) : ?>
                        <div class="flex">
                            <div class="flex items-center w-4/12 px-3 py-2.5 border border-gray-200">
                                <span><?php echo $location['name'] ?></span>
                            </div>
                            <div class="flex items-center w-6/12 px-3 py-2.5 border border-gray-200">
                                <span>John Doe</span>
                            </div>
                            <div class="flex items-center w-2/12 px-3 py-2.5 border border-gray-200">
                                <form class="inline" action="" method="post">
                                    <input type="hidden" name="id" value="<?php echo $location['id']; ?>">
                                    <input class="px-1 cursor-pointer border-2 border-gray-300" type="submit" name="edit_location" value="Edit">
                                    <input class="px-1 cursor-pointer border-2 border-gray-300" type="submit" name="delete_location" value="Delete">
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
</body>

</html>