<?php
require "db.php";
require "auth.php";
require_login();
$total = $conn->query("SELECT COUNT(*) AS total FROM items")->fetch_assoc()["total"];
$lost = $conn->query("SELECT COUNT(*) AS total FROM items WHERE item_type='Lost' AND status='Unclaimed'")->fetch_assoc()["total"];
$found = $conn->query("SELECT COUNT(*) AS total FROM items WHERE item_type='Found' AND status='Unclaimed'")->fetch_assoc()["total"];
$claimed = $conn->query("SELECT COUNT(*) AS total FROM items WHERE status='Claimed'")->fetch_assoc()["total"];
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Dashboard</title>
<style>
*{box-sizing:border-box}body{font-family:Arial,sans-serif;background:#f4f6f9;margin:0;color:#222}header{background:#203864;color:white;padding:22px 6%}nav{background:#fff;padding:14px 6%;box-shadow:0 2px 5px #ddd}nav a{margin-right:18px;color:#203864;text-decoration:none;font-weight:bold}main{max-width:1000px;margin:30px auto;padding:0 18px}.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px}.card{background:white;padding:24px;border-radius:10px;box-shadow:0 2px 8px #ddd}.number{font-size:30px;font-weight:bold;color:#203864}.btn{display:inline-block;background:#203864;color:white;padding:11px 15px;border-radius:5px;text-decoration:none;margin:12px 8px 0 0}
</style></head><body><header><h1>Lost and Found Management System</h1><p>Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?>!</p></header>
<nav><a href="index.php">Dashboard</a><a href="add.php">Report Item</a><a href="items.php">View Items</a><a href="logout.php">Log Out</a></nav>
<main><h2>Dashboard</h2><div class="cards">
<div class="card">Total Items<div class="number"><?= $total ?></div></div><div class="card">Lost Items<div class="number"><?= $lost ?></div></div><div class="card">Found Items<div class="number"><?= $found ?></div></div><div class="card">Claimed Items<div class="number"><?= $claimed ?></div></div>
</div><a class="btn" href="add.php">+ Report an Item</a><a class="btn" href="items.php">View All Items</a></main></body></html>