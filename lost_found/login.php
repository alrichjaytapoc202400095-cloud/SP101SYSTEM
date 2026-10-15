<?php
require "db.php";
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $account = $result->fetch_assoc();
    if ($account && password_verify($password, $account["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = (int)$account["id"];
        $_SESSION["user_name"] = $account["name"];
        header("Location: index.php");
        exit;
    }
    $message = "Incorrect email or password.";
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Log In</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;background:#f4f6f9;margin:0;display:grid;place-items:center;min-height:100vh;color:#222}.box{background:white;width:min(420px,92%);padding:28px;border-radius:12px;box-shadow:0 3px 12px #ddd}h1{color:#203864;font-size:25px}label{display:block;margin-top:14px;font-weight:bold}input{width:100%;padding:11px;margin-top:6px;border:1px solid #bbb;border-radius:5px}button{width:100%;margin-top:20px;padding:12px;background:#203864;color:white;border:0;border-radius:5px;cursor:pointer}.msg{color:#b00020;color:#b00020}
</style></head><body><div class="box"><h1>Lost and Found</h1><p>Log in to your account.</p>
<?php if(isset($_GET["registered"])): ?><p style="color:green">Registration successful. You can log in now.</p><?php endif; ?>
<?php if($message): ?><p class="msg"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<form method="post"><label>Email</label><input type="email" name="email" required><label>Password</label><input type="password" name="password" required><button type="submit">Log In</button></form>
<p>Don't have an account? <a href="register.php">Register here</a></p></div></body></html>