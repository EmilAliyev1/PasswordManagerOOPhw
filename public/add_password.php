<?php

session_start();

require_once "../classes/Auth.php";
require_once "../classes/PasswordVault.php";

if (!Auth::isLoggedIn())
{
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $vault = new PasswordVault();

    $success = $vault->savePassword(
        $_SESSION["user_id"],
        $_POST["site_name"],
        $_POST["password"],
        base64_decode($_SESSION["user_key"])
    );

    $message = $success
        ? "Password saved"
        : "Failed";
}
?>

<!DOCTYPE html>
<html>
<body>

<h1>Add Password</h1>

<form method="POST">

    <label>Website</label><br>
    <input
        type="text"
        name="site_name"
        required
    >

    <br><br>

    <label>Password</label><br>
    <input
        type="text"
        name="password"
        required
    >

    <br><br>

    <button type="submit">
        Save
    </button>

</form>

<p><?= htmlspecialchars($message) ?></p>

<a href="dashboard.php">
    Dashboard
</a>

</body>
</html>