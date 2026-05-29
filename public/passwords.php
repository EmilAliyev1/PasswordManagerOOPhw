<?php

session_start();

require_once "../classes/Auth.php";
require_once "../classes/PasswordVault.php";
require_once "../classes/Encryption.php";

if (!Auth::isLoggedIn())
{
    header("Location: login.php");
    exit;
}

$vault = new PasswordVault();

$passwords = $vault->getPasswords(
    $_SESSION["user_id"]
);

$userKey =
    base64_decode(
        $_SESSION["user_key"]
    );
?>

<!DOCTYPE html>
<html>
<body>

<h1>Stored Passwords</h1>

<table border="1">

<tr>
    <th>Website</th>
    <th>Password</th>
    <th>Date</th>
</tr>

<?php foreach ($passwords as $entry): ?>

<tr>

<td>
<?= htmlspecialchars($entry["site_name"]) ?>
</td>

<td>

<?=
htmlspecialchars(
    Encryption::decryptPassword(
        base64_decode(
            $entry["encrypted_password"]
        ),
        $userKey,
        base64_decode(
            $entry["password_iv"]
        )
    )
)
?>

</td>

<td>
<?= htmlspecialchars($entry["created_at"]) ?>
</td>

</tr>

<?php endforeach; ?>

</table>

</body>
</html>