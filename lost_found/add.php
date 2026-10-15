<?php
require "db.php";
require "auth.php";
require_login();
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $item_name = trim($_POST["item_name"] ?? "");
    $item_type = $_POST["item_type"] ?? "";
    $description = trim($_POST["description"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $date_reported = $_POST["date_reported"] ?? date("Y-m-d");
    $image_name = null;

    if ($item_name === "" || !in_array($item_type, ["Lost", "Found"], true) || $location === "") {
        $message = "Please fill in all required fields.";
    } elseif (!isset($_FILES["item_image"]) || $_FILES["item_image"]["error"] === UPLOAD_ERR_NO_FILE) {
        $message = "Please upload a picture of the item.";
    } elseif ($_FILES["item_image"]["error"] !== UPLOAD_ERR_OK) {
        $message = "Image upload failed. Please try again.";
    } elseif ($_FILES["item_image"]["size"] > 5 * 1024 * 1024) {
        $message = "Image must be 5 MB or smaller.";
    } else {
        $tmp = $_FILES["item_image"]["tmp_name"];
        $image_info = getimagesize($tmp);
        $mime = $image_info["mime"] ?? "";
        $allowed = ["image/jpeg" => "jpg", "image/png" => "png", "image/gif" => "gif", "image/webp" => "webp"];
        if (!$image_info || !isset($allowed[$mime])) {
            $message = "Please upload a valid JPG, PNG, GIF, or WEBP image.";
        } else {
            $upload_dir = __DIR__ . "/uploads";
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }
            $image_name = bin2hex(random_bytes(16)) . "." . $allowed[$mime];
            if (!move_uploaded_file($tmp, $upload_dir . "/" . $image_name)) {
                $message = "Could not save the image. Check the uploads folder permission.";
            } else {
                $reporter_id = (int)$_SESSION["user_id"];
                $stmt = $conn->prepare("INSERT INTO items (user_id, item_name, item_type, description, location, date_reported, image_name, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Unclaimed')");
                $stmt->bind_param("issssss", $reporter_id, $item_name, $item_type, $description, $location, $date_reported, $image_name);
                if ($stmt->execute()) {
                    header("Location: items.php?added=1");
                    exit;
                }
                @unlink($upload_dir . "/" . $image_name);
                $message = "Could not save the item details.";
            }
        }
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Report Item</title>
<style>
*{box-sizing:border-box}body{font-family:Arial,sans-serif;background:#f4f6f9;margin:0;color:#222}header{background:#203864;color:white;padding:22px 6%}nav{background:white;padding:14px 6%}nav a{margin-right:18px;color:#203864;text-decoration:none;font-weight:bold}main{max-width:700px;margin:28px auto;padding:25px;background:white;border-radius:10px;box-shadow:0 2px 8px #ddd}label{display:block;margin-top:14px;font-weight:bold}input,select,textarea{width:100%;padding:10px;margin-top:6px;border:1px solid #bbb;border-radius:5px;font-size:15px}button{margin-top:20px;background:#203864;color:white;border:0;padding:12px 18px;border-radius:5px;cursor:pointer}.error{color:#b00020}small{color:#555}
</style></head><body><header><h1>Report a Lost or Found Item</h1></header>
<nav><a href="index.php">Dashboard</a><a href="add.php">Report Item</a><a href="items.php">View Items</a><a href="logout.php">Log Out</a></nav>
<main><h2>Item Information</h2><?php if($message): ?><p class="error"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<form method="post" enctype="multipart/form-data">
<label>Item Name *</label><input name="item_name" required maxlength="150">
<label>Report Type *</label><select name="item_type" required><option value="Lost">Lost Item</option><option value="Found">Found Item</option></select>
<label>Description</label><textarea name="description" rows="3" placeholder="Color, brand, or other details"></textarea>
<label>Location *</label><input name="location" required maxlength="200" placeholder="Where was it lost or found?">
<label>Date Reported *</label><input type="date" name="date_reported" value="<?= date('Y-m-d') ?>" required>
<label>Item Picture *</label><input type="file" name="item_image" accept="image/jpeg,image/png,image/gif,image/webp" capture="environment" required><small>On a supported phone, the camera may open. You can also choose an existing photo. Maximum 5 MB.</small>
<button type="submit">Save Item</button></form></main></body></html>