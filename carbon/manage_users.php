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
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { margin:0; font-family: 'Poppins', sans-serif; background:#f4f6f8; }
        .header { background:#4CAF50; color:white; padding:20px 30px; text-align:center; font-size:24px; font-weight:600; }
        .container { display:flex; min-height:calc(100vh - 60px); } 
        .sidebar { width:220px; background:#2e3b4e; color:white; padding:20px; display:flex; flex-direction:column; }
        .sidebar a { color:white; text-decoration:none; padding:10px 0; margin-bottom:5px; border-radius:5px; transition: background 0.3s; }
        .sidebar a:hover, .sidebar a.active { background:#4CAF50; }
        .content { flex:1; padding:30px; }
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
        <a href="manage_users.php" class="active">Manage Users</a>
        <a href="admin_reports.php">Reports</a>
        <a href="settings.php">Settings</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
        <h2>Manage Users</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
            <?php
            $stmt = $conn->query("SELECT user_id, name, email, role FROM users");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['role']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>