<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM daily_log WHERE user_id = ?");
$stmt->execute([$user_id]);

header("Location: index.php?cleared=1");
exit();
?>