<?php
require "db.php";
require "auth.php";
require_login();
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $conn->prepare("UPDATE items SET status='Claimed' WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $_SESSION["user_id"]);
    $stmt->execute();
}
header("Location: items.php");
exit;
?>