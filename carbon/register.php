<?php
include 'db.php';

$name = $_POST['name'];
$age = $_POST['age'];
$location = $_POST['location'];

$stmt = $conn->prepare(
    "INSERT INTO users (name, age, location)
     VALUES (:name, :age, :location)"
);

$stmt->execute([
    ':name' => $name,
    ':age' => $age,
    ':location' => $location
]);

header("Location: index.php?success=1");
exit;
?>