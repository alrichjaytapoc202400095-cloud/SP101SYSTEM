<?php
require "db.php";
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        $message = "Enter your name, a valid email, and a password with at least 6 characters.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hash);
        if ($stmt->execute()) {
            header("Location: login.php?registered=1");
            exit;
        } else {
            $message = "That email may already be registered. Please try another email.";
        }
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Register</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;background:#f4f6f9;margin:0;display:grid;place-items:center;min-height:100vh;color:#222}.box{background:white;width:min(420px,92%);padding:28px;border-radius:12px;box-shadow:0 3px 12px #ddd}h1{color:#203864;font-size:25px}label{display:block;margin-top:14px;font-weight:bold}input{width:100%;padding:11px;margin-top:6px;border:1px solid #bbb;border-radius:5px}button{width:100%;margin-top:20px;padding:12px;background:#203864;color:white;border:0;border-radius:5px;cursor:pointer}.msg{color:#b00020}
</style></head><body><div class="box"><h1>Create Account</h1><p>Register to report lost or found items.</p>
<?php if($message): ?><p class="msg"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<form method="post"><label>Full Name</label><input name="name" required maxlength="100"><label>Email</label><input type="email" name="email" required><label>Password (at least 6 characters)</label><input type="password" name="password" minlength="6" required><button type="submit">Register</button></form>
<p>Already have an account? <a href="login.php">Log in</a></p></div></body></html>