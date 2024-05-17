<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

// Assuming the manager's ID is stored in the session
$managerId = $_SESSION['user_id']; // Adjust this based on your session variable
$manager = Member::getMemberWithId($managerId);
$managerLocationId = $manager['location_id'];
$managerLocation = Location::getLocationWithId($managerLocationId);

if (isset($_POST['create'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $locationId = $managerLocationId; // Use manager's location
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];

    if ($password !== $passwordConfirm) {
        $error = "Passwords do not match!";
    } else {
        $member = new Member();
        $member->setFirstname($firstname);
        $member->setLastname($lastname);
        $member->setEmail($email);
        $member->setPassword($password);
        $member->setRoleId(3);
        $member->setLocationId($locationId);

        $member->save();
        $success = "Member added!";
    }
}

if (isset($_POST['assign_tasks'])) {
    $task = new Task();
    $task->setUserId($_POST['member']);
    $task->assignTasks(isset($_POST['tasks']) ? $_POST['tasks'] : []);
}

$tasks = Task::getAll();

// Filter members based on the manager's location
$members = Member::getAll($managerLocationId);

$assignedTasks = [];
if (isset($_POST['member'])) {
    $assignedTasks = Task::getTasksByUserId($_POST['member']);
}

if (isset($_POST['edit'])) {
    header("Location: members.php?id=" . $_POST['id'] . "&mode=edit");
    exit;
}

if (isset($_POST['delete'])) {
    Member::deleteMember($_POST['id']);
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
    <div class="flex w-screen relative">
        <?php include_once(__DIR__ . "/nav.inc.php") ?>
        <div id="popup-scrn" class="w-screen h-screen items-center justify-center absolute z-10 top-0 left-0 bg-black/50" style="display: none;">
            <div class="w-7/12 p-14 bg-white rounded-lg relative">
                <form class="w-full grid grid-cols-2 gap-x-14 gap-y-6" action="" method="post">
                    <div>
                        <label class="block py-1 font-semibold text-lg" for="firstname">Firstname</label>
                        <input class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="firstname" type="text" name="firstname">
                    </div>
                    <div>
                        <label class="block py-1 font-semibold text-lg" for="lastname">Lastname</label>
                        <input class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="lastname" type="text" name="lastname">
                    </div>
                    <div>
                        <label class="block py-1 font-semibold text-lg" for="email">Email</label>
                        <input class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="email" type="email" name="email">
                    </div>
                    <div>
                        <label class="block py-1 font-semibold text-lg" for="location">Location</label>
                        <input type="hidden" name="location" value="<?php echo $managerLocationId; ?>">
                        <input disabled class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300 bg-gray-200" id="location" type="text" value="<?php echo $managerLocation['name']; ?>" readonly>
                    </div>
                    <div>
                        <label class="block py-1 font-semibold text-lg" for="password">Password</label>
                        <input class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="password" type="password" name="password">
                    </div>
                    <div>
                        <label class="block py-1 font-semibold text-lg" for="password-confirm">Password Confirm</label>
                        <input class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="password-confirm" type="password" name="password-confirm">
                    </div>
                    <div class="pt-8 col-span-2">
                        <input class="w-full h-12 font-bold uppercase justify-self-center cursor-pointer rounded bg-yellow-400 border-yellow-400 border-2" name="create" type="submit" value="Add member">
                    </div>
                </form>
                <button class="w-7 h-7 m-3.5 p-px absolute right-0 top-0 font-bold opacity-25 leading-none close__btn">
                    <img class="w-full h-auto" src="./assets/close.svg" alt="close icon">
                </button>
            </div>
        </div>
        <div class="ml-72 px-14 py-10 flex-1">
        <div class="flex items-center space-x-4 pb-12 ">
    <div>
    <div class="w-14 h-14 p-3.5 rounded bg-black/80">
                                    <svg class="w-full h-auto" id="location" data-name="location" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 650 650">
                                        <path class="fill-current text-yellow-400" d="M617.5,650H32.5c-17.95,0-32.5-14.55-32.5-32.5s14.55-32.5,32.5-32.5h16.25v-150.53c0-19.01,0-29.48,2.82-41.19,2.53-10.47,6.67-20.37,12.31-29.41,6.43-10.3,14.25-17.8,27.22-30.23l19.8-18.97v-178.16c0-39.97,0-61.98,9.82-82.53,8.85-18.53,22.83-33.38,40.43-42.95C181.45,0,202.57,0,240.91,0h230.34c38.32,0,59.43,0,79.73,11.03,17.63,9.58,31.62,24.43,40.46,42.95,9.81,20.53,9.81,42.51,9.81,82.42v448.6h16.25c17.95,0,32.5,14.55,32.5,32.5s-14.55,32.5-32.5,32.5ZM448.91,585h87.34V136.4c0-28.4,0-47.16-3.46-54.4-2.86-5.99-7.42-10.9-12.85-13.85-5.78-3.14-23.78-3.14-48.68-3.14h-230.34c-24.93,0-42.94,0-48.72,3.14-5.48,2.98-9.91,7.77-12.82,13.85-3.47,7.26-3.47,26.06-3.47,54.52v115.98c17.2-16.04,29.41-25.64,45.38-30.77,18.01-5.79,37.06-5.79,55.08,0,20.17,6.48,34.36,20.08,60.11,44.77l70.08,67.16c12.94,12.4,20.75,19.89,27.21,30.23,5.64,9.04,9.78,18.93,12.31,29.41,2.84,11.78,2.84,22.85,2.84,41.19v150.53ZM113.75,585h270.16v-150.53c0-14.65-.04-21.87-1.03-25.96-.89-3.68-2.32-7.12-4.26-10.23-2.06-3.3-6.98-8.08-17.04-17.71l-70.08-67.15c-16.91-16.21-29.13-27.92-35.04-29.82-5-1.61-10.29-1.61-15.28,0-5.88,1.89-18.09,13.59-34.97,29.77l-70.13,67.2c-9.31,8.92-14.92,14.3-17.05,17.71-1.95,3.12-3.38,6.56-4.26,10.23-1.01,4.21-1.01,11.94-1.01,25.96v150.53Z" />
                                    </svg>
                                </div>
    </div>
    <div>
        <h2 class="font-extrabold text-1xl">HUB Members</h2>
        <h2 class="font-extrabold text-4xl text-yellow-400"><?php echo $managerLocation['name']; ?></h2>
    </div>
</div>

            <div class="flex justify-between w-full pb-10">
                <form class="w-10/12" action="" method="get">
                    <input class="w-2/5 px-3.5 py-2 text-lg rounded border-gray-300 border-2" type="text" id="search" name="q" placeholder="Search...">
                    <button class="px-3.5 py-2 text-lg rounded border-gray-300 border-2 bg-gray-100" type="submit">Search</button>
                </form>
                <button class="flex items-center justify-center gap-2.5 px-4 uppercase rounded bg-yellow-400 manager__btn">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 650 650">
                        <path d="M325,650c-42.3,0-83.52-7.98-122.51-23.72-40.43-16.32-76.62-40.28-107.55-71.22-30.93-30.94-54.9-67.12-71.22-107.55C7.98,408.52,0,367.3,0,325s7.98-83.52,23.72-122.51c16.32-40.43,40.28-76.62,71.22-107.55s67.12-54.9,107.55-71.22C241.48,7.98,282.7,0,325,0s83.52,7.98,122.51,23.72c40.43,16.32,76.62,40.28,107.55,71.22,30.93,30.94,54.9,67.12,71.22,107.55,15.74,38.99,23.72,80.21,23.72,122.51s-7.98,83.52-23.72,122.51c-16.32,40.43-40.28,76.62-71.22,107.55-30.94,30.93-67.12,54.9-107.55,71.22-38.99,15.74-80.21,23.72-122.51,23.72ZM325,56.15c-72.14,0-139.74,27.87-190.36,78.49-50.61,50.61-78.49,118.22-78.49,190.36s27.87,139.74,78.49,190.36c50.61,50.61,118.22,78.49,190.36,78.49s139.74-27.87,190.36-78.49c50.61-50.61,78.49-118.22,78.49-190.36s-27.87-139.74-78.49-190.36c-50.61-50.61-118.22-78.49-190.36-78.49Z" />
                        <path d="M443.98,296.92h-90.9s0-90.9,0-90.9c0-15.51-12.57-28.08-28.08-28.08s-28.08,12.57-28.08,28.08v90.9s-90.9,0-90.9,0c-15.51,0-28.08,12.57-28.08,28.08s12.57,28.08,28.08,28.08h90.9s0,90.9,0,90.9c0,15.51,12.57,28.08,28.08,28.08s28.08-12.57,28.08-28.08v-90.9s90.9,0,90.9,0c15.51,0,28.08-12.57,28.08-28.08s-12.57-28.08-28.08-28.08Z" />
                    </svg>
                    <span class="font-semibold whitespace-nowrap">Add member</span>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-8">
                <?php foreach ($members as $member) : ?>
                    <?php $memberLocation = Location::getLocationWithId($member['location_id']) ?>
                    <div class="flex items-center justify-between p-5 border-gray-200 border-2 rounded-lg bg-white shadow-md">
                        <div class="flex items-center gap-5">
                            <div class="flex justify-center items-center bg-yellow-400 w-14 h-14 rounded-full">
                                <span class="font-extrabold tracking-wider text-xl text-black"><?php echo getUserInitials($member["firstname"], $member["lastname"]); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-extrabold text-xl opacity-85"><?php echo $member['firstname'] . ' ' . $member['lastname'] ?></span>
                                <span class="pt-1 font-semibold text-sm text-yellow-400 uppercase"><?php echo !empty($memberLocation['name']) ? 'HUB Location: ' . $memberLocation['name'] : 'HUB Location: None'; ?></span>
                            </div>
                        </div>
                        <form class="flex gap-3" action="" method="post">
                            <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
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
    </div>
    <script src="js/script.js"></script>
</body>

</html>
