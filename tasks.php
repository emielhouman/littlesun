<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

if (isset($_POST['create'])) {
    $task = new Task();
    $task->setTask($_POST['task']);

    $task->save();
}

if (isset($_POST['delete'])) {
    Task::deleteTask($_POST['id']);
}

$tasks = Task::getAll();

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
            <h2 class="font-extrabold text-4xl pb-12">HUB Tasks</h2>
            <div class="flex justify-between w-full pb-10">
                <form class="w-10/12" action="" method="get">
                    <input class="w-2/5 px-3.5 py-2 text-lg rounded border-gray-300 border-2" type="text" id="search" name="q" placeholder="Search...">
                    <button class="px-3.5 py-2 text-lg rounded border-gray-300 border-2 bg-gray-100" type="submit">Search</button>
                </form>
                <button class="flex items-center justify-center gap-2.5 px-4 uppercase rounded bg-yellow-400 task__btn">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 650 650">
                        <path d="M325,650c-42.3,0-83.52-7.98-122.51-23.72-40.43-16.32-76.62-40.28-107.55-71.22-30.93-30.94-54.9-67.12-71.22-107.55C7.98,408.52,0,367.3,0,325s7.98-83.52,23.72-122.51c16.32-40.43,40.28-76.62,71.22-107.55s67.12-54.9,107.55-71.22C241.48,7.98,282.7,0,325,0s83.52,7.98,122.51,23.72c40.43,16.32,76.62,40.28,107.55,71.22,30.93,30.94,54.9,67.12,71.22,107.55,15.74,38.99,23.72,80.21,23.72,122.51s-7.98,83.52-23.72,122.51c-16.32,40.43-40.28,76.62-71.22,107.55-30.94,30.93-67.12,54.9-107.55,71.22-38.99,15.74-80.21,23.72-122.51,23.72ZM325,56.15c-72.14,0-139.74,27.87-190.36,78.49-50.61,50.61-78.49,118.22-78.49,190.36s27.87,139.74,78.49,190.36c50.61,50.61,118.22,78.49,190.36,78.49s139.74-27.87,190.36-78.49c50.61-50.61,78.49-118.22,78.49-190.36s-27.87-139.74-78.49-190.36c-50.61-50.61-118.22-78.49-190.36-78.49Z" />
                        <path d="M443.98,296.92h-90.9s0-90.9,0-90.9c0-15.51-12.57-28.08-28.08-28.08s-28.08,12.57-28.08,28.08v90.9s-90.9,0-90.9,0c-15.51,0-28.08,12.57-28.08,28.08s12.57,28.08,28.08,28.08h90.9s0,90.9,0,90.9c0,15.51,12.57,28.08,28.08,28.08s28.08-12.57,28.08-28.08v-90.9s90.9,0,90.9,0c15.51,0,28.08-12.57,28.08-28.08s-12.57-28.08-28.08-28.08Z" />
                    </svg>
                    <span class="font-semibold whitespace-nowrap">Create task</span>
                </button>
            </div>
            <div class="grid grid-cols-3 gap-8">
                <?php foreach ($tasks as $task) : ?>
                    <div class="flex items-center justify-between p-6 border-gray-200 border-2 rounded-lg bg-white shadow-md">
                        <span class="font-semibold text-xl"><?php echo $task['name'] ?></span>
                        <form class="flex gap-3" action="" method="post">
                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                            <button class="w-10 h-10 p-2.5 bg-black/80 rounded-full shadow-md" type="submit" name="edit">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 800 800">
                                    <path class="w-full h-auto fill-current text-yellow-400" style="fill-rule: evenodd" d="M664.14,101.08c-34.78-34.78-91.16-34.78-125.94,0L117.96,521.32c-12.43,12.43-20.9,28.26-24.35,45.5l-17.43,87.13c-8.31,41.54,28.32,78.16,69.86,69.86l87.13-17.43c17.24-3.45,33.07-11.92,45.5-24.35l420.24-420.24c34.78-34.78,34.78-91.16,0-125.93l-34.77-34.78ZM580.18,143.06c11.59-11.59,30.39-11.59,41.98,0l34.77,34.78c11.59,11.59,11.59,30.39,0,41.98l-79.3,79.3-76.75-76.75,79.3-79.3ZM458.9,264.34l-298.96,298.96c-4.14,4.14-6.97,9.42-8.12,15.17l-17.43,87.13,87.13-17.43c5.75-1.15,11.02-3.97,15.17-8.12l298.96-298.96-76.75-76.75Z" />
                                </svg>
                            </button>
                            <button class="w-10 h-10 p-2.5 bg-black/80 rounded-full shadow-md" type="submit" name="delete">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 800 800">
                                    <path class="w-full h-auto fill-current text-yellow-400" d="M536.25,710h-272.5c-56.35,0-102.19-46.04-102.19-102.63v-342.11c-18.81,0-34.06-15.32-34.06-34.21s15.25-34.21,34.06-34.21h68.12v-34.21c0-56.59,45.84-102.63,102.19-102.63h136.25c56.35,0,102.19,46.04,102.19,102.63v34.21h68.13c18.81,0,34.06,15.32,34.06,34.21s-15.25,34.21-34.06,34.21v342.11c0,56.59-45.84,102.63-102.19,102.63ZM229.69,265.26v342.11c0,18.86,15.28,34.21,34.06,34.21h272.5c18.78,0,34.06-15.35,34.06-34.21v-342.11H229.69ZM297.81,196.84h204.38v-34.21c0-18.86-15.28-34.21-34.06-34.21h-136.25c-18.78,0-34.06,15.35-34.06,34.21v34.21ZM468.13,573.16c-18.81,0-34.06-15.32-34.06-34.21v-171.05c0-18.89,15.25-34.21,34.06-34.21s34.06,15.32,34.06,34.21v171.05c0,18.89-15.25,34.21-34.06,34.21ZM331.88,573.16c-18.81,0-34.06-15.32-34.06-34.21v-171.05c0-18.89,15.25-34.21,34.06-34.21s34.06,15.32,34.06,34.21v171.05c0,18.89-15.25,34.21-34.06,34.21Z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script src="js/script.js"></script>
</body>

</html>