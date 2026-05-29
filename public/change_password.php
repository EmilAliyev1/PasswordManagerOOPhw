<?php

session_start();

require_once "../classes/Auth.php";
require_once "../classes/User.php";

if (!Auth::isLoggedIn())
{
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $user = new User();

    $success = $user->changePassword(
        $_SESSION["user_id"],
        $_POST["old_password"],
        $_POST["new_password"]
    );

    if ($success)
    {
        session_destroy();

        header("Location: login.php");
        exit;
    }

    $message =
        "Old password incorrect";
}
?>

<!DOCTYPE html>
<html>
<body>

<h1>Change Password</h1>

<form method="POST">

    <label>Old Password</label><br>
    <input
        type="password"
        name="old_password"
        required
    >

    <br><br>

    <label>New Password</label><br>
    <input
        type="password"
        name="new_password"
        required
    >

    <br><br>

    <button type="submit">
        Change Password
    </button>

</form>

<p>
<?= htmlspecialchars($message) ?>
</p>

<a href="dashboard.php">
    Dashboard
</a>

</body>
</html>