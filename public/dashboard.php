<?php

session_start();

require_once "../classes/Auth.php";

if (!Auth::isLoggedIn())
{
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Dashboard</h1>

<p>
    Welcome,
    <?= htmlspecialchars($_SESSION["username"]) ?>
</p>

<p>
    You are logged in.
</p>

<a href="add_password.php">
    Add Password
</a>

<br><br>

<a href="passwords.php">
    View Passwords
</a>

<br><br>

<a href="logout.php">
    Logout
</a>

</body>
</html>