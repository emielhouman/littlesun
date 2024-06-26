<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    $user->setEmail($email);
    $user->setPassword($password);

    if ($user->canLogin()) {
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['error_message'] = "Login failed! Please check your email and password.";
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
    <div class="flex items-center justify-center w-screen h-screen bg-black bg-opacity-100 bg-[url('./assets/background.png')] bg-cover bg-center">
        <div class="w-1/3 p-12 rounded border-2 border-gray-300 bg-white">
            <h2 class="font-bold text-3xl pb-8">Sign in</h2>
            <?php if (isset($_SESSION['error_message'])) : ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo $_SESSION['error_message']; ?></p>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            <form class="flex flex-col gap-4" action="" method="post">
                <div class="flex flex-col gap-1.5">
                    <label class="font-semibold" for="email">Email</label>
                    <input class="w-full h-12 px-2.5 rounded border-2 border-gray-300 bg-white" id="email" name="email" type="text">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="font-semibold" for="password">Password</label>
                    <input class="w-full h-12 px-2.5 rounded border-2 border-gray-300 bg-white" id="password" name="password" type="password">
                </div>
                <input class="mt-6 h-12 font-extrabold uppercase bg-yellow-400 text-black rounded cursor-pointer" type="submit" value="Sign in">
            </form>
        </div>
    </div>
</body>

</html>
