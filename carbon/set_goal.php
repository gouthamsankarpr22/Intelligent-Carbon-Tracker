<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

// GET EXISTING GOAL
$goalQuery = "SELECT target_emission FROM goals WHERE user_id = ?";
$stmt = $conn->prepare($goalQuery);
$stmt->execute([$user_id]);
$existingGoal = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['goal'])){
    $goal = $_POST['goal'];

    $sql = "INSERT INTO goals (user_id, target_emission)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE target_emission = VALUES(target_emission)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $goal]);

    header("Location: report.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Set Goal</title>

<style>
body {
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:#eef3e7;
}

/* NAVBAR */
.navbar {
    background:#1b5e20;
    color:white;
    padding:15px 20px;
    font-size:20px;
    font-weight:bold;
}

/* CENTER CONTAINER */
.container {
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:80vh;
}

/* CARD */
.card {
    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
    width:350px;
}

/* TITLE */
.card h3 {
    margin-bottom:15px;
    color:#2e7d32;
}

/* INPUT GROUP */
.input-group {
    display:flex;
    flex-direction:column;
    margin-bottom:15px;
}

.input-group label {
    font-size:14px;
    color:#555;
    margin-bottom:5px;
}

.input-group input {
    padding:12px;
    border-radius:8px;
    border:1px solid #ddd;
    font-size:15px;
    transition:0.3s;
}

.input-group input:focus {
    border-color:#4caf50;
    box-shadow:0 0 5px rgba(76,175,80,0.3);
    outline:none;
}

/* BUTTON */
button {
    width:100%;
    padding:12px;
    border:none;
    background:linear-gradient(90deg,#4caf50,#66bb6a);
    color:white;
    font-size:16px;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
}

button:hover {
    transform:translateY(-2px);
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
}

/* EXTRA TEXT */
.helper-text {
    font-size:13px;
    color:#777;
    margin-bottom:10px;
}
.back-btn {
    display:inline-block;
    margin-top:10px;
    padding:10px 18px;
    background:#1565c0;
    color:white;
    text-decoration:none;
    border-radius:8px;
    font-size:14px;
    transition:0.3s;
}

.back-btn:hover {
    background:#0d47a1;
    transform:translateY(-2px);
}

</style>

</head>

<body>

<div class="navbar">🎯 Set Your Goal</div>

<div class="container">

    <div class="card">

        <h3>Update Monthly Goal</h3>

        <p class="helper-text">
            Set a limit to control your carbon emissions.
        </p>

        <form method="POST">
            <p style="font-size:13px;color:#666;">
            Current Goal: <b><?php echo $existingGoal['target_emission'] ?? 'Not set'; ?> kg</b>
            </p>
            <div class="input-group">
                <label>Carbon Limit (kg)</label>
                <input 
                    type="number" 
                    name="goal"
                    value="<?php echo $existingGoal['target_emission'] ?? ''; ?>"
                    placeholder="Enter goal (e.g. 50)"
                    required
                >
            </div>

            <button type="submit">💾 Save Goal</button>

        </form>
    <div style="text-align:center; margin-top:15px;">
        <a href="report.php" class="back-btn">⬅ Back to Dashboard</a>
    </div>
    </div>
</div>

</body>
</html>