<?php
include_once(__DIR__ . "/bootstrap.php");

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User;
    $user->setEmail($email);
    $user->setPassword($password);

    if ($user->canLogin()) {
        header('Location: index.php');
        
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-black flex justify-center items-center h-screen">

    <div class="bg-yellow-400 p-10 rounded shadow-md">
        <form action="" method="post" class="space-y-4">
            <h2 class="text-2xl font-bold mb-4">Sign In</h2>
            <?php if (isset($error)) : ?>
                <div class="text-red-500">
                    <p>Something went wrong... Please try again!</p>
                </div>
            <?php endif; ?>
            <div class="flex flex-col">
                <label for="email" class="text-sm font-medium">Email</label>
                <input type="text" name="email" id="email" class="mt-1 p-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>
            <div class="flex flex-col">
                <label for="password" class="text-sm font-medium">Password</label>
                <input type="password" name="password" id="password" class="mt-1 p-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>
            <div class="flex justify-center">
                <input type="submit" value="Sign up" class="mt-4 px-4 py-2 bg-black text-white rounded cursor-pointer hover:bg-blue-600 transition duration-300 ease-in-out">
            </div>
        </form>
    </div>
</body>
</html>
