<?php
include 'db.php';

$user_id = $_POST['user_id'];
$activity_id = $_POST['activity_id'];
$quantity = $_POST['quantity'];

$stmt = $conn->prepare(
    "INSERT INTO daily_log (user_id, activity_id, quantity, log_date)
     VALUES (:user_id, :activity_id, :quantity, CURDATE())"
);

$stmt->execute([
    ':user_id' => $user_id,
    ':activity_id' => $activity_id,
    ':quantity' => $quantity
]);

header("Location: index.php?logsuccess=1");
exit;
?>