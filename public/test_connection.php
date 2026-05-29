<?php

require_once "../classes/Database.php";

try {
    $db = new Database();
    $pdo = $db->connect();

    echo "Database connection successful!";
}
catch (Exception $e) {
    echo $e->getMessage();
}