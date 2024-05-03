<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

if (isset($_GET['id']) && isset($_GET['mode'])) {
    $managers = Manager::getAll();
    $location = Location::getLocationInfo($_GET['id']);

    if ($_GET['mode'] === "edit" && !empty($_POST)) {
        $id = $location['id'];
        $name = $_POST['location'];
        $manager = $_POST['manager'];

        Location::updateLocation($id, $name, $manager);

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
    <div class="flex w-full">
        <?php include_once(__DIR__ . "/nav.inc.php") ?>
        <div class="flex-1 mx-16 my-10">
            <div class="flex justify-between items-end pt-1">
                <h2 class="font-bold text-3xl">HUB Location: <span class="font-normal"><?php echo $location['name'] ?></span></h2>
                <a href="location.php?id=<?php echo $location['id'] ?>&mode=<?php echo ($_GET['mode'] === "edit" ? "read" : "edit") ?>" class="px-2 py-0.5 font-bold rounded border-2 cursor-pointer <?php echo ($_GET['mode'] === "edit" ? "bg-gray-200 border-gray-200" : "bg-yellow-400 border-yellow-400") ?>">
                    <?php echo ($_GET['mode'] === "edit" ? "Undo changes" : "Edit location") ?>
                </a>
            </div>
            <form class="w-full grid grid-cols-2 gap-x-14 gap-y-6 pt-16 px-12" action="" method="post">
                <div>
                    <label class="block py-1 font-semibold text-lg" for="location">Location:</label>
                    <input class="w-full h-12 px-2.5 text-lg rounded border-2 <?php echo ($_GET['mode'] === "edit" ? "bg-white border-gray-300" : "bg-gray-100 border-gray-300") ?>" id="location" name="location" type="text" value="<?php echo $location["name"] ?>" <?php echo ($_GET['mode'] === "edit" ? "" : "disabled") ?>>
                </div>
                <div>
                    <label class="block py-1 font-semibold text-lg" for="manager">Manager:</label>
                    <select class="w-full h-12 px-1.5 text-lg opacity-100 rounded border-2 <?php echo ($_GET['mode'] === "edit" ? "bg-white border-gray-300" : "bg-gray-100 border-gray-300") ?>" id="manager" name="manager">
                        <option value="0">No manager selected</option>
                        <?php foreach ($managers as $manager) : ?>
                            <option value="<?php echo $manager['id'] ?>" <?php echo ($location['manager_id'] === $manager['id'] ? 'selected' : '') ?>><?php echo $manager['firstname'] . " " . $manager['lastname'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pt-8 col-span-2">
                    <input class="w-full h-12 font-bold uppercase rounded border-2 cursor-pointer <?php echo ($_GET['mode'] === "edit" ? "bg-yellow-400 border-yellow-400" : "bg-gray-200 border-gray-200") ?>" type="submit" value="<?php echo ($_GET['mode'] === "edit" ? "Update location" : "All locations") ?>">
                </div>
            </form>
        </div>
    </div>
</body>

</html>