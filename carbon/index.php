<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<?php include 'db.php'; ?>

<?php
$successMessage = "";
$logMessage = "";

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMessage = "User Registered Successfully";
}

if (isset($_GET['logsuccess']) && $_GET['logsuccess'] == 1) {
    $logMessage = "Activity Logged Successfully";
}
?>
<?php if($_SESSION['role']=='admin'){ ?>

<div class="report-btn">
<a href="admin_dashboard.php">⚙ Admin Dashboard</a>
</div>

<?php } ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Carbon Footprint Tracker</title>
    <?php include 'transition.php'; ?>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>
<?php if(isset($_GET['cleared'])): ?>
<div id="clearMessage" style="
position:fixed;
top:0;
left:0;
width:100%;
background:#2e7d32;
color:white;
text-align:center;
padding:10px;
font-weight:500;
z-index:999;">
All data cleared successfully
</div>
<?php endif; ?>

<script>
setTimeout(function(){

    let msg = document.getElementById("clearMessage");

    if(msg){
        msg.style.transition="opacity 0.5s";
        msg.style.opacity="0";

        setTimeout(()=>msg.remove(),500);
    }

    // remove ?cleared=1 from URL
    if(window.location.search.includes("cleared")){
        window.history.replaceState({}, document.title, window.location.pathname);
    }

},2000);
</script>

<div class="app">
    <?php include 'includes/header.php'; ?>
    
    <div class="title">
        <h1>Intelligent Carbon Tracker</h1>
        <p>Turn daily activities into environmental data</p>
    </div>
    
    <?php if ($successMessage): ?>
    <p style="color: green; text-align: center; font-weight: 500;">
        <?php echo $successMessage; ?>
    </p>
    <?php endif; ?>

    <?php if ($logMessage): ?>
    <p style="color: blue; text-align: center; font-weight: 500; margin-top:10px;">
        <?php echo $logMessage; ?>
    </p>
    <?php endif; ?>
    <p style="text-align:center;">
        Logged in as: <b><?php echo $_SESSION['user']; ?></b>
    </p>
    <div class="divider"></div>

    <div class="section">
    <h3>📝 Log Daily Activity</h3>
    <form action="log_activity.php" method="post">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
        <select name="activity_id" required>
    <option value="">Select Activity</option>

    <?php
    $activities = $conn->query("SELECT * FROM activities ORDER BY activity_name");

    $currentCategory = "";

    while ($row = $activities->fetch(PDO::FETCH_ASSOC)) {

        // When category changes → open new group
        if ($currentCategory != $row['activity_name']) {

            if ($currentCategory != "") {
                echo "</optgroup>";
            }

            $currentCategory = $row['activity_name'];
            echo "<optgroup label='{$currentCategory}'>";
        }

        echo "<option value='{$row['activity_id']}'>
                {$row['sub_activity']}
              </option>";
    }

    echo "</optgroup>";
    ?>
</select>
        <input type="number" step="0.1" id="quantity" name="quantity"
       placeholder="Enter value" required>

        <button type="submit">Log Activity</button>
    </form>
</div>
<div style="text-align:center; margin-top:15px;">
<form action="clear_data.php" method="post">
<button type="submit" style="background:#e53935;color:white;padding:8px 15px;border:none;border-radius:5px;">
Clear My Data
</button>
</form>
</div>
<div class="report-btn">
    <a href="report.php">📊 View Emission Report</a>
</div>
<div class="report-btn">
    <a href="offset.php">🌳 Carbon Offset Calculator</a>
</div>
<?php if($_SESSION['user']=="admin"){ ?>

<div class="report-btn">
<a href="admin_dashboard.php">⚙ Admin Dashboard</a>
</div>

<?php } ?>
</div>
<script>
document.querySelector("select[name='activity_id']").addEventListener("change", function () {
    let text = this.options[this.selectedIndex].text;

    let input = document.getElementById("quantity");

    if (text.includes("km")) {
        input.placeholder = "Enter distance (km)";
    } 
    else if (text.includes("hour")) {
        input.placeholder = "Enter time (hours)";
    } 
    else if (text.includes("kg")) {
        input.placeholder = "Enter weight (kg)";
    } 
    else {
        input.placeholder = "Enter value";
    }
});
</script>
</body>
</html>


