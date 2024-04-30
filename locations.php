<?php
include_once(__DIR__ . "/bootstrap.php");

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
    <div class="flex">
        <?php include_once(__DIR__ . "/nav.inc.php") ?>
        <div class="mx-14 my-10">
            <h2 class="font-bold text-3xl pt-1">HUB Locations</h2>
            <article class="py-8">
                <form action="" method="post">
                    <label class="block py-1" for="name">Location:</label>
                    <input class="w-56 px-1.5 py-1 rounded border-gray-300 border-2" id="name" name="location" type="text">
                    <input class="px-1.5 py-1 font-bold rounded cursor-pointer bg-gray-300 border-gray-300 border-2" type="submit" name="add_location" value="add">
                </form>
            </article>
            <ul class="list-disc pl-5">
                <?php foreach ($locations as $location) : ?>
                    <li>
                        <a class="underline" href="location.php?id=<?php echo $location['id'];?>&mode=read"><?php echo $location['name'] ?></a>
                        <form class="inline" action="" method="post">
                            <input type="hidden" name="id" value="<?php echo $location['id']; ?>">
                            <input class="px-1 cursor-pointer border-2 border-gray-300" type="submit" name="edit_location" value="Edit">
                            <input class="px-1 cursor-pointer border-2 border-gray-300" type="submit" name="delete_location" value="Delete">
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>

</html>