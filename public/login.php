<?php

session_start();

require_once "../classes/Auth.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $auth = new Auth();

    if (
        $auth->login(
            $_POST["username"],
            $_POST["password"]
        )
    )
    {
        header("Location: dashboard.php");
        exit;
    }

    $message = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<form method="POST">

    <label>Username</label><br>
    <input
        type="text"
        name="username"
        required
    >

    <br><br>

    <label>Password</label><br>
    <input
        type="password"
        name="password"
        required
    >

    <br><br>

    <button type="submit">
        Login
    </button>

</form>

<p><?= htmlspecialchars($message) ?></p>

</body>
</html>