<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

$locations = Location::getAll();

if (!empty($_POST)) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $locationId = $_POST['location'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];

    if ($password !== $passwordConfirm) {
        $error = "Passwords do not match!";
    } else {
        $manager = new Manager();
        $manager->setFirstname($firstname);
        $manager->setLastname($lastname);
        $manager->setEmail($email);
        $manager->setPassword($password);
        $manager->setRoleId(2);
        $manager->setLocationId($locationId);

        $manager->save();
        $success = "Manager added!";
    }
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
    <div class="flex">
        <?php include_once(__DIR__ . "/nav.inc.php") ?>
        <div class="flex-1 mx-16 my-10">
            <h2 class="font-bold text-3xl pt-1">HUB Managers</h2>
            <article>
                <?php if (isset($error)) : ?>
                    <div class="bg-red-200 border-red-300 border-2 rounded p-2">
                        <p class="text-red-800"><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>
                <?php if (isset($success)) : ?>
                    <div class="bg-green-200 border-green-300 border-2 rounded p-2">
                        <p class="text-green-800"><?php echo $success; ?></p>
                    </div>
                <?php endif; ?>
            </article>
            <form class="w-full grid grid-cols-2 gap-x-14 gap-y-6 pt-16 px-12" action="" method="post">
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
                    <select class="w-full h-12 px-1.5 text-lg opacity-100 rounded border-2 bg-white border-gray-300" id="location" name="location">
                        <option value="0">Select a location</option>
                        <?php foreach ($locations as $location) :  ?>
                            <option value="<?php echo $location['id'] ?>"><?php echo $location['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
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
                    <input class="w-full h-12 font-bold uppercase justify-self-center cursor-pointer rounded bg-yellow-400 border-yellow-400 border-2" type="submit" value="Add manager">
                </div>
            </form>
        </div>
    </div>
</body>

</html>