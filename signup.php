<?php
if (!empty($_POST)) {
    $connection = new mysqli("localhost", "root", "root", "littlesun");

    // Get the data from POST
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hashing password with bcrypt
    $options = [
        'cost' => 12
    ];
    $password = password_hash($password, PASSWORD_DEFAULT, $options);

    // Send the data to users table
    $query = "insert into users (email, password) values ('$email', '$password')";
    $result = $connection->query($query);

    session_start();
    $_SESSION['loggedin'] = true;
    header("Location: index.php");
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
            <h2>Sign Up</h2>
            <?php if (isset($error)) : ?>
                <div class="form__error">
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
                <input type="checkbox" id="rememberUser"><label for="rememberUser">Remember me</label>
            </div>
        </form>
    </div>
</body>

</html>