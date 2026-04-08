<?php
session_start();
include 'db.php';

// Only allow admins
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';

// Example: Change admin password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch admin password from DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $update->execute([$hashed, $_SESSION['user_id']]);
            $success = "Password updated successfully!";
        } else {
            $error = "New password and confirm password do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { margin:0; font-family: 'Poppins', sans-serif; background:#f4f6f8; }
        .header { background:#4CAF50; color:white; padding:20px 30px; text-align:center; font-size:24px; font-weight:600; }
        .container { display:flex; min-height:calc(100vh - 60px); } 
        .sidebar { width:220px; background:#2e3b4e; color:white; padding:20px; display:flex; flex-direction:column; }
        .sidebar a { color:white; text-decoration:none; padding:10px 0; margin-bottom:5px; border-radius:5px; transition: background 0.3s; }
        .sidebar a:hover, .sidebar a.active { background:#4CAF50; }
        .content { flex:1; padding:30px; }
        .card { background:white; padding:20px; margin-bottom:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
        input[type="password"], button { padding:10px; margin-top:10px; width:100%; border-radius:5px; border:1px solid #ccc; font-size:14px; }
        button { background:#4CAF50; color:white; border:none; cursor:pointer; transition:0.3s; }
        button:hover { background:#45a049; }
        .message { margin-top:15px; font-size:14px; }
        .error { color:red; }
        .success { color:green; }
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
        <a href="admin_reports.php">Reports</a>
        <a href="settings.php" class="active">Settings</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
        <h2>Settings</h2>
        <div class="card">
<h3>Add New Admin</h3>

<form method="post">
<input type="text" name="name" placeholder="Admin Name" required>

<input type="text" name="location" placeholder="Location" required>

<input type="number" name="age" placeholder="Age" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="add_admin">Add Admin</button>
</form>

<?php
if(isset($_POST['add_admin'])){

$name = $_POST['name'];
$age = $_POST['age'];
$location = $_POST['location'];
$password = $_POST['password'];

$conn->query("INSERT INTO users (name, age, location, password, role)
VALUES ('$name','$age','$location','$password','admin')");

echo "<p style='color:green;'>Admin added successfully</p>";
}
?>

</div>
        <div class="card">
            <h3>Change Password</h3>
            <?php if($error) echo "<div class='message error'>$error</div>"; ?>
            <?php if($success) echo "<div class='message success'>$success</div>"; ?>
            <form method="post">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="submit">Update Password</button>
            </form>
        </div>

        <div class="card">
<h3>Update Emission Factor</h3>

<form method="post">

<select name="activity_id" required>
<option value="">Select Activity</option>

<?php
$activities = $conn->query("SELECT activity_id, activity_name FROM activities");
while($row = $activities->fetch(PDO::FETCH_ASSOC)){
    echo "<option value='".$row['activity_id']."'>".$row['activity_name']."</option>";
}
?>

</select>

<input type="number" step="0.01" name="emission_factor" 
placeholder="New Emission Factor" required>

<button type="submit">Update</button>

</form>

<?php
if(isset($_POST['activity_id'])){
    $activity_id = $_POST['activity_id'];
    $emission_factor = $_POST['emission_factor'];

    $conn->query("UPDATE activities 
                  SET emission_factor='$emission_factor' 
                  WHERE activity_id='$activity_id'");

    echo "<p style='color:green;'>Emission factor updated successfully</p>";
}
?>
</div>
</div>
    </div>
</div>

</body>
</html>