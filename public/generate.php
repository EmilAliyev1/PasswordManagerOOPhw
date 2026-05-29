<?php

require_once "../classes/PasswordGenerator.php";

$result = "";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $gen = new PasswordGenerator();

    $result = $gen->generate(
        (int)$_POST["length"],
        (int)$_POST["lower"],
        (int)$_POST["upper"],
        (int)$_POST["numbers"],
        (int)$_POST["special"]
    );
}
?>

<!DOCTYPE html>
<html>
<body>

<h1>Password Generator</h1>

<form method="POST">

    Length:
    <input type="number" name="length" value="9" required>
    <br><br>

    Lowercase:
    <input type="number" name="lower" value="2">
    <br><br>

    Uppercase:
    <input type="number" name="upper" value="3">
    <br><br>

    Numbers:
    <input type="number" name="numbers" value="2">
    <br><br>

    Special:
    <input type="number" name="special" value="2">
    <br><br>

    <button type="submit">Generate</button>

</form>

<?php if ($result): ?>
    <h2>Result:</h2>
    <pre><?= htmlspecialchars($result) ?></pre>
<?php endif; ?>

<br>

<a href="dashboard.php">
    Dashboard
</a>

</body>
</html>