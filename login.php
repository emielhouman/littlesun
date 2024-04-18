<?php 

function canLogin($pEmail, $pPassword) {
	$connection = new mysqli("localhost", "root", "root", "littlesun");
	$pEmail = $connection->real_escape_string($pEmail);
	$sql = "SELECT password FROM users WHERE email = '$pEmail'";
	$result = $connection-> query($sql);
	$user = $result->fetch_assoc();
	if(password_verify($pPassword, $user['password'])){
		return true;
	} else {
		return false;
	}
};

if (!empty($_POST)) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	if (canLogin($email, $password)) {
		session_start();
		$_SESSION['loggedin'] = true;
		header('Location: index.php');
	} else {
		$error = true;
	}
}

?><!DOCTYPE html>
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