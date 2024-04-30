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
    <title>Little Sun</title>
</head>

<body>
    <div class="form">
        <form action="" method="post">
            <h2>Sign In</h2>
            <?php if (isset($error)) : ?>
                <div style="color: red;">
                    <p>Something went wrong... Please try again!</p>
                </div>
            <?php endif; ?>
            <div class="form__field">
                <label for="Email">Email</label>
                <input type="text" name="email">
            </div>
            <div class="form__field">
                <label for="Password">Password</label>
                <input type="password" name="password">
            </div>
            <div class="form__field">
                <input type="submit" value="Sign up" class="button">
            </div>
        </form>
    </div>
</body>

</html>