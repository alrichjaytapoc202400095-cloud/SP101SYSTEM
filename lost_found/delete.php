<?php
require "db.php";
require "auth.php";
require_login();
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $conn->prepare("SELECT image_name FROM items WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $_SESSION["user_id"]);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
    if ($item) {
        $del = $conn->prepare("DELETE FROM items WHERE id=? AND user_id=?");
        $del->bind_param("ii", $id, $_SESSION["user_id"]);
        $del->execute();
        if ($item["image_name"]) { $path = __DIR__ . "/uploads/" . basename($item["image_name"]); if (is_file($path)) { @unlink($path); } }
    }
}
header("Location: items.php");
exit;
?>