<?php
session_start();
include 'db.php';

// Only allow admins
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { margin:0; font-family: 'Poppins', sans-serif; background:#f4f6f8; }
        .header { background:#4CAF50; color:white; padding:20px 30px; text-align:center; font-size:24px; font-weight:600; }
        .container { display:flex; min-height:calc(100vh - 60px); } 
        .sidebar { width:220px; background:#2e3b4e; color:white; padding:20px; display:flex; flex-direction:column; }
        .sidebar a { color:white; text-decoration:none; padding:10px 0; margin-bottom:5px; border-radius:5px; transition: background 0.3s; }
        .sidebar a:hover, .sidebar a.active { background:#4CAF50; }
        .content { flex:1; padding:30px; }
        .card { background:white; padding:20px; margin-bottom:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        table th, table td { padding:12px 15px; border:1px solid #ddd; text-align:left; }
        table th { background:#f4f6f8; }
        .logout-btn { margin-top:auto; padding:10px; background:#e74c3c; color:white; text-align:center; border-radius:5px; text-decoration:none; }
        .logout-btn:hover { background:#c0392b; }
        @media (max-width:768px) { .container { flex-direction:column; } .sidebar { width:100%; flex-direction:row; overflow-x:auto; } .sidebar a { margin:0 10px; } }
    </style>
</head>
<body>

<div class="header">Admin Dashboard</div>

<div class="container">
    <div class="sidebar">
        <a href="admin_dashboard.php">Home</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="admin_reports.php" class="active">Reports</a>
        <a href="settings.php">Settings</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
        <h2>Reports</h2>

        <!-- Example summary cards -->
        <div class="card">
            <h3>Total Users</h3>
            <?php
            $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
            $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            echo "<p>Total registered users: <strong>$totalUsers</strong></p>";
            ?>
        </div>

        <div class="card">
            <h3>User Roles</h3>
            <?php
            $stmt = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
            echo "<table>
                    <tr><th>Role</th><th>Count</th></tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>{$row['role']}</td><td>{$row['count']}</td></tr>";
            }
            echo "</table>";
            ?>
        </div>

        <div class="card">
            <h3>User Roles Distribution</h3>
            <div style="height:200px;">
                <canvas id="rolesChart"></canvas>
            </div>
        </div>
        <div class="card">
<h3>All Users Emission Report</h3>

<table>
<tr>
<th>User</th>
<th>Activity</th>
<th>Quantity</th>
<th>Emission</th>
<th>Date</th>
</tr>

<?php
$sql = "
SELECT u.name, a.activity_name, d.quantity,
(d.quantity * a.emission_factor) AS emission,
d.log_date
FROM daily_log d
JOIN users u ON d.user_id = u.user_id
JOIN activities a ON d.activity_id = a.activity_id
";

$result = $conn->query($sql);

foreach ($result as $row){
echo "<tr>";
echo "<td>{$row['name']}</td>";
echo "<td>{$row['activity_name']}</td>";
echo "<td>{$row['quantity']}</td>";
echo "<td>".round($row['emission'],2)." kg</td>";
echo "<td>{$row['log_date']}</td>";
echo "</tr>";
}
?>

</table>
</div>
        </div>

    </div>
</div>
<?php
$roles = $conn->query("SELECT role, COUNT(*) as total FROM users GROUP BY role")->fetchAll(PDO::FETCH_ASSOC);
$roleLabels = array_column($roles,'role');
$roleData = array_column($roles,'total');
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxRoles = document.getElementById('rolesChart')?.getContext('2d');
if(ctxRoles){
    new Chart(ctxRoles, {
        type: 'pie',
        data: {
            labels: <?= json_encode($roleLabels??[]) ?>,
            datasets: [{
                data: <?= json_encode($roleData??[]) ?>,
                backgroundColor:['#4CAF50','#FF9800','#2196F3']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // ensures canvas fills container height
            plugins: {
                legend: { position: 'bottom', labels:{ font:{size:12}, padding:5 } }
            },
            layout: { padding: 10 } // some padding inside chart
        }
    });
}
</script>
</body>
</html>