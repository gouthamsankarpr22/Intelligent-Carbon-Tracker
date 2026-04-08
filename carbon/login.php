<?php
session_start();
include 'db.php';

$error = '';

// If already logged in, redirect based on role
if (isset($_SESSION['user'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

// Handle login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $conn->prepare("SELECT user_id, name, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check password: hashed or plain text
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            // Set session
            $_SESSION['user'] = $user['name'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        }
    }

    $error = "Invalid Email or Password";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <?php include 'transition.php'; ?>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="login-card">
    <h2>Login</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p style="margin-top:15px; text-align:center;">
        Don't have an account? <a href="signup.php">Sign Up</a>
    </p>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>