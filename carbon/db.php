<?php
try {
    $conn = new PDO(
        "mysql:host=localhost;dbname=carbon_tracker",
        "root",
        "1234"
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed");
}
?>
