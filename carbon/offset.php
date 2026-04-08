<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'functions.php';
include 'db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT a.sub_activity, f.emission_factor, d.quantity
FROM daily_log d
JOIN activities a ON d.activity_id = a.activity_id
LEFT JOIN emission_factors f ON a.activity_id = f.activity_id
WHERE d.user_id=?";

$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);

$totalEmission = 0;

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    $base = $row['emission_factor'];

    if(!$base) continue;

    $learningFactor = getLearningFactor($user_id, $row['sub_activity'], $conn);

    $emission = $row['quantity']
        * getDynamicEmission($row['sub_activity'],$base,$row['quantity'])
        * getEfficiencyFactor($row['sub_activity'])
        * getTimeFactor($row['sub_activity'],$row['quantity'])
        * getSeasonalFactor($row['sub_activity'], date('m'))
        * $learningFactor;

    $totalEmission += $emission;
}


$treesNeeded = ceil($totalEmission / 21); // 1 tree absorbs ~21 kg CO2/year
?>

<!DOCTYPE html>
<html>
<head>
<title>Carbon Offset Calculator</title>
<link rel="stylesheet" href="css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<?php include 'transition.php'; ?>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="app">

<h2 style="text-align:center;">🌳 Carbon Offset Calculator</h2>

<p style="text-align:center;">
Your Total Emission: <b><?php echo round($totalEmission,2); ?> kg CO₂</b>
</p>

<p style="text-align:center;">
Trees Required to Offset: 
<b><?php echo $treesNeeded; ?> trees</b>
</p>

<p style="text-align:center;color:green;">
Planting <?php echo $treesNeeded; ?> trees can offset your yearly carbon footprint.
</p>

<div class="report-btn">
<a href="index.php">⬅ Back to Dashboard</a>
</div>

</div>

</body>
</html>