<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];
$currentMonth = date('m');

/* FUNCTIONS */
function getTimeFactor($sub_activity, $quantity){

    /* 🌬 COOLING */
    if (stripos($sub_activity, 'Air Conditioner') !== false)
        return $quantity > 6 ? 1.6 : ($quantity > 3 ? 1.3 : 1);

    if (stripos($sub_activity, 'Cooler') !== false)
        return $quantity > 6 ? 1.3 : 1.1;

    if (stripos($sub_activity, 'Fan') !== false)
        return $quantity > 8 ? 1.3 : ($quantity > 4 ? 1.2 : 1.1);


    /* 💡 LIGHTING */
    if (stripos($sub_activity, 'LED') !== false)
        return $quantity > 6 ? 1.2 : 1;

    if (stripos($sub_activity, 'Tube Light') !== false)
        return $quantity > 6 ? 1.2 : 1;

    if (stripos($sub_activity, 'Bulb') !== false)
        return $quantity > 6 ? 1.3 : 1.1;


    /* 📺 ELECTRONICS */
    if (stripos($sub_activity, 'TV') !== false)
        return $quantity > 5 ? 1.2 : 1;

    if (stripos($sub_activity, 'Laptop') !== false)
        return $quantity > 6 ? 1.2 : 1;

    if (stripos($sub_activity, 'Computer') !== false)
        return $quantity > 6 ? 1.3 : 1;

    if (stripos($sub_activity, 'Mobile') !== false)
        return $quantity > 4 ? 1.1 : 1;


    /* 🍳 KITCHEN */
    if (stripos($sub_activity, 'Refrigerator') !== false)
        return 1.2; // always running

    if (stripos($sub_activity, 'Microwave') !== false)
        return $quantity > 1 ? 1.2 : 1;

    if (stripos($sub_activity, 'Induction Stove') !== false)
        return $quantity > 2 ? 1.3 : 1.1;

    if (stripos($sub_activity, 'Electric Kettle') !== false)
        return $quantity > 2 ? 1.2 : 1;


    /* 🧺 HOUSEHOLD */
    if (stripos($sub_activity, 'Washing Machine') !== false)
        return $quantity > 2 ? 1.3 : 1;

    if (stripos($sub_activity, 'Iron') !== false)
        return $quantity > 2 ? 1.2 : 1;

    if (stripos($sub_activity, 'Vacuum Cleaner') !== false)
        return $quantity > 2 ? 1.3 : 1;


    /* 🚿 HEATING */
    if (stripos($sub_activity, 'Geyser') !== false)
        return $quantity > 2 ? 1.4 : 1.2;

    if (stripos($sub_activity, 'Heater') !== false)
        return $quantity > 3 ? 1.5 : 1.2;


    /* 🚗 TRANSPORT */
    if (stripos($sub_activity, 'Car') !== false)
        return $quantity > 50 ? 1.4 : ($quantity > 20 ? 1.2 : 1);

    if (stripos($sub_activity, 'Bike') !== false)
        return $quantity > 40 ? 1.3 : 1.1;

    if (stripos($sub_activity, 'Bus') !== false)
        return $quantity > 20 ? 1.2 : 1;

    if (stripos($sub_activity, 'Train') !== false)
        return $quantity > 50 ? 1.1 : 1;


    return 1;
}

function getDynamicEmission($sub_activity, $base, $quantity){

    /* 🚗 VEHICLES */
    if (stripos($sub_activity, 'Electric Car') !== false) return $base * 0.6;
    if (stripos($sub_activity, 'Petrol Car') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Diesel Car') !== false) return $base * 1.3;

    if (stripos($sub_activity, 'Electric Bike') !== false) return $base * 0.5;
    if (stripos($sub_activity, 'Petrol Bike') !== false) return $base * 1.1;

    if (stripos($sub_activity, 'Bus') !== false) return $base * 0.9;
    if (stripos($sub_activity, 'Train') !== false) return $base * 0.7;


    /* 🌬 COOLING */
    if (stripos($sub_activity, 'Air Conditioner') !== false) return $base * 1.5;
    if (stripos($sub_activity, 'Cooler') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Fan') !== false) return $base * 0.9;


    /* 💡 LIGHTING */
    if (stripos($sub_activity, 'LED') !== false) return $base * 0.6;
    if (stripos($sub_activity, 'Tube Light') !== false) return $base * 0.8;
    if (stripos($sub_activity, 'Bulb') !== false) return $base * 1.2;


    /* 📺 ELECTRONICS */
    if (stripos($sub_activity, 'TV') !== false) return $base * 1.1;
    if (stripos($sub_activity, 'Laptop') !== false) return $base * 0.9;
    if (stripos($sub_activity, 'Computer') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Mobile') !== false) return $base * 0.7;


    /* 🍳 KITCHEN */
    if (stripos($sub_activity, 'Refrigerator') !== false) return $base * 1.3;
    if (stripos($sub_activity, 'Microwave') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Induction Stove') !== false) return $base * 1.1;
    if (stripos($sub_activity, 'Electric Kettle') !== false) return $base * 1.1;


    /* 🧺 HOUSEHOLD */
    if (stripos($sub_activity, 'Washing Machine') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Iron') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Vacuum Cleaner') !== false) return $base * 1.3;


    /* 🚿 HEATING */
    if (stripos($sub_activity, 'Geyser') !== false) return $base * 1.4;
    if (stripos($sub_activity, 'Heater') !== false) return $base * 1.5;


    return $base;
}

function getEfficiencyFactor($sub_activity){

    /* 🌱 HIGH EFFICIENCY (LOW EMISSION) */
    if (stripos($sub_activity, 'LED') !== false) return 0.6;
    if (stripos($sub_activity, '5 Star') !== false) return 0.7;
    if (stripos($sub_activity, 'Inverter AC') !== false) return 0.75;
    if (stripos($sub_activity, 'Electric Vehicle') !== false) return 0.6;
    if (stripos($sub_activity, 'Electric Car') !== false) return 0.6;
    if (stripos($sub_activity, 'Electric Bike') !== false) return 0.6;


    /* ⚖ MEDIUM EFFICIENCY */
    if (stripos($sub_activity, 'Fan') !== false) return 0.9;
    if (stripos($sub_activity, 'Tube Light') !== false) return 0.85;
    if (stripos($sub_activity, 'Laptop') !== false) return 0.9;
    if (stripos($sub_activity, 'Mobile') !== false) return 0.85;
    if (stripos($sub_activity, 'Induction Stove') !== false) return 0.9;


    /* ⚡ NORMAL */
    if (stripos($sub_activity, 'TV') !== false) return 1;
    if (stripos($sub_activity, 'Refrigerator') !== false) return 1;
    if (stripos($sub_activity, 'Washing Machine') !== false) return 1;


    /* 🔥 LOW EFFICIENCY (HIGH CONSUMPTION) */
    if (stripos($sub_activity, 'Air Conditioner') !== false) return 1.2;
    if (stripos($sub_activity, 'Heater') !== false) return 1.4;
    if (stripos($sub_activity, 'Geyser') !== false) return 1.3;
    if (stripos($sub_activity, 'Iron') !== false) return 1.2;
    if (stripos($sub_activity, 'Microwave') !== false) return 1.1;
    if (stripos($sub_activity, 'Vacuum Cleaner') !== false) return 1.3;


    /* 🚗 VEHICLES */
    if (stripos($sub_activity, 'Petrol Car') !== false) return 1.3;
    if (stripos($sub_activity, 'Diesel Car') !== false) return 1.4;
    if (stripos($sub_activity, 'Petrol Bike') !== false) return 1.2;


    return 1;
}

function getSeasonalFactor($sub_activity, $month){

    /* 🌞 SUMMER (Mar–Jun) */
    if ($month >= 3 && $month <= 6) {
        if (stripos($sub_activity, 'Air Conditioner') !== false) return 1.5;
        if (stripos($sub_activity, 'Cooler') !== false) return 1.3;
        if (stripos($sub_activity, 'Fan') !== false) return 1.2;
        if (stripos($sub_activity, 'Refrigerator') !== false) return 1.1;
    }

    /* 🌧 MONSOON (Jul–Sep) */
    if ($month >= 7 && $month <= 9) {
        if (stripos($sub_activity, 'Fan') !== false) return 1.1;
        if (stripos($sub_activity, 'Dryer') !== false) return 1.3;
        if (stripos($sub_activity, 'Washing Machine') !== false) return 1.2;
    }

    /* ❄ WINTER (Oct–Feb) */
    if ($month >= 10 || $month <= 2) {
        if (stripos($sub_activity, 'Heater') !== false) return 1.5;
        if (stripos($sub_activity, 'Geyser') !== false) return 1.4;
        if (stripos($sub_activity, 'Iron') !== false) return 1.2;
    }

    /* ⚡ ALWAYS SLIGHT VARIATION */
    if (stripos($sub_activity, 'TV') !== false) return 1.05;
    if (stripos($sub_activity, 'Laptop') !== false) return 1.05;

    return 1;
}

function updateLearningFactor($user_id, $sub_activity, $conn){

    // Get avg usage
    $stmt = $conn->prepare("
        SELECT AVG(d.quantity) as avg_qty
        FROM daily_log d
        JOIN activities a ON d.activity_id = a.activity_id
        WHERE d.user_id=? AND a.sub_activity=?
    ");
    $stmt->execute([$user_id, $sub_activity]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $avg = $data['avg_qty'] ?? 0;

    // Decide factor
    if ($avg < 2) $factor = 0.9;
    elseif ($avg < 5) $factor = 1;
    elseif ($avg < 8) $factor = 1.1;
    else $factor = 1.2;

    // ✅ INSERT OR UPDATE (NO DUPLICATES)
    $stmt = $conn->prepare("
        INSERT INTO emission_learning (user_id, sub_activity, avg_quantity, adjusted_factor, last_updated)
        VALUES (?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
            avg_quantity = VALUES(avg_quantity),
            adjusted_factor = VALUES(adjusted_factor),
            last_updated = NOW()
    ");

    $stmt->execute([$user_id, $sub_activity, $avg, $factor]);
}

function getLearningFactor($user_id, $sub_activity, $conn){

    $stmt = $conn->prepare("
        SELECT adjusted_factor 
        FROM emission_learning 
        WHERE user_id=? AND sub_activity=?
    ");
    $stmt->execute([$user_id, $sub_activity]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['adjusted_factor'] ?? 1;
}

/* GOAL */
$stmtGoal = $conn->prepare("SELECT target_emission FROM goals WHERE user_id=?");
$stmtGoal->execute([$user_id]);
$goal = $stmtGoal->fetch(PDO::FETCH_ASSOC);
$target = $goal['target_emission'] ?? 0;

/* DATA */
$sql = "SELECT u.name,a.activity_name,a.sub_activity,a.unit,
f.emission_factor,d.quantity,d.log_date
FROM daily_log d
JOIN users u ON d.user_id=u.user_id
JOIN activities a ON d.activity_id=a.activity_id
LEFT JOIN emission_factors f ON a.activity_id=f.activity_id
WHERE d.user_id=?";

$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);

$total_emission = 0;
$chartData = [];
$maxValue = 0;
$maxActivity = "";

$stmtMonth = $conn->prepare("
    SELECT month, year, total_emission 
    FROM monthly_emission 
    WHERE user_id=?
    ORDER BY year, month
");
$stmtMonth->execute([$user_id]);

$months = [];
$emissions = [];

while($row = $stmtMonth->fetch(PDO::FETCH_ASSOC)){
    $months[] = $row['month']."/".$row['year'];
    $emissions[] = $row['total_emission'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body { margin:0; font-family:Segoe UI; background:#f4f6f8; }

/* LAYOUT */
.layout { display:flex; }

/* SIDEBAR */
.sidebar {
    width:220px;
    height:100vh;
    background:#1b5e20;
    color:white;
    padding:20px;
    position:fixed;
}
.sidebar a {
    display:block;
    color:white;
    padding:12px;
    margin:10px 0;
    text-decoration:none;
    border-radius:6px;
}
.sidebar a:hover { background:#2e7d32; }

/* MAIN */
.main {
    margin-left:240px;
    width:calc(100% - 240px);
}

/* TOPBAR */
.topbar {
    background:white;
    padding:15px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

/* CONTENT */
.content { padding:25px; }

/* GRID */
.grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-top:30px;  
}

/* CARDS */
.card {
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
    text-align:center;
    margin-top:25px;
}

/* TABLE */
table {
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}
th {
    background:#2e7d32;
    color:white;
    padding:12px;
}
td {
    padding:12px;
    border-bottom:1px solid #eee;
}

/* SMALL CENTERED CHART */
.chart-wrapper {
    width:240px;
    height:240px;
    margin:20px auto;
}
.chart-wrapper canvas {
    width:100% !important;
    height:100% !important;
}
.goal-card {
    text-align:left;
    padding:20px;
}

.goal-header {
    font-size:14px;
    color:#777;
    margin-bottom:10px;
}

.goal-value {
    font-size:28px;
    font-weight:600;
    color:#2e7d32;
}

.goal-value span {
    font-size:16px;
    color:#888;
}

/* PROGRESS BAR */
.progress-bar {
    width: 100%;
    height: 20px;
    background-color: #ddd;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 10px;
}

.progress-fill {
    height: 100%;
    background-color: #4caf50;
    display: block;
}

.goal-progress small {
    display:block;
    margin-top:5px;
    color:#666;
}
</style>
</head>

<body>

<div class="layout">

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>🌱 Tracker</h2>
    <a href="index.php">🏠 Back to Dashboard</a>
    <a href="report.php">📊 Report</a>
    <a href="set_goal.php">🎯 Goal</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<div class="content">

<!-- CARDS -->
<div class="grid section-space">    
    <div class="card goal-card">
    <div class="goal-header">
        🎯 Monthly Goal
    </div>

    <div class="goal-value">
        <?php echo $target; ?> <span>kg</span>
    </div>

    <div style="margin-top:10px; color:#555;">
    <?php
   if ($target = 0) {
        echo "No goal set";
    }
    ?>
    </div>
</div>

    <div class="card">
        <h4>🌿 Total</h4>
        <h2 id="totalBox">0</h2>
    </div>

    <div class="card">
        <h4>⚡ Score</h4>
        <h2 id="scoreBox">0</h2>
    </div>
</div>

<!-- TABLE -->
<div class="card">
<h3>📋 Activity Logs</h3>

<table>
<tr>
<th>User</th>
<th>Category</th>
<th>Activity</th>
<th>Qty</th>
<th>Emission</th>
<th>Date</th>
</tr>

<?php
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    $base = $row['emission_factor'] ?? 0;
    $learningFactor = getLearningFactor($user_id, $row['sub_activity'], $conn);
    updateLearningFactor($user_id, $row['sub_activity'], $conn);
    $emission = $row['quantity']
        * getDynamicEmission($row['sub_activity'],$base,$row['quantity'])
        * getEfficiencyFactor($row['sub_activity'])
        * getTimeFactor($row['sub_activity'],$row['quantity'])
        * getSeasonalFactor($row['sub_activity'],$currentMonth)
        * $learningFactor;

    $total_emission += $emission;

    if($emission > $maxValue){
        $maxValue = $emission;
        $maxActivity = $row['sub_activity'];
    }

    $chartData[$row['sub_activity']] = ($chartData[$row['sub_activity']] ?? 0) + $emission;

    echo "<tr>
    <td>{$row['name']}</td>
    <td><b>{$row['activity_name']}</b></td>
    <td>{$row['sub_activity']}</td>
    <td>{$row['quantity']} {$row['unit']}</td>
    <td>
        ".round($emission,2)." kg
        <br>
        <small style='color:#1565c0;'>LF: ".round($learningFactor,2)."</small>
    </td>
    <td>{$row['log_date']}</td>
    </tr>";
}
?>
</table>
</div>

<!-- INSIGHTS -->
<div class="grid">
    <div class="card">⚠ Highest<br><b><?php echo $maxActivity; ?></b></div>
    <div class="card">🌿 Total<br><b><?php echo round($total_emission,2); ?> kg</b></div>
    <div class="card">🔮 Prediction<br><b><?php echo round($total_emission*1.05,2); ?> kg</b></div>
</div>

<!-- CHART -->
<div class="card">
<h3>📊 Chart</h3>
<div class="chart-wrapper">
<canvas id="chart"></canvas>
</div>
</div>
<div class="card">
    <h3>📈 Monthly Trend</h3>

    <div class="chart-wrapper">
        <canvas id="monthlyChart"></canvas>
    </div>
</div>

</div>
</div>
</div>

<script>
let total = <?php echo round($total_emission,2); ?>;
let score = Math.max(0,100 - total/2);

document.getElementById("totalBox").innerText = total+" kg";
document.getElementById("scoreBox").innerText = Math.round(score)+"/100";

new Chart(document.getElementById("chart"), {
    type:'pie',
    data:{
        labels: <?php echo json_encode(array_keys($chartData)); ?>,
        datasets:[{
            data: <?php echo json_encode(array_values($chartData)); ?>,
            backgroundColor:[
                '#4CAF50','#FF9800','#2196F3','#E91E63',
                '#9C27B0','#00BCD4'
            ]
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false
    }
});
new Chart(document.getElementById("monthlyChart"), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
            label: "Monthly Emission (kg)",
            data: <?php echo json_encode($emissions); ?>,
            borderColor: "#4CAF50",
            backgroundColor: "rgba(76,175,80,0.2)",
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

</body>
</html>