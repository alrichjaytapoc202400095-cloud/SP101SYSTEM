<?php
require "db.php";
require "auth.php";
require_login();
$search = trim($_GET["search"] ?? "");
if ($search !== "") {
    $stmt = $conn->prepare("SELECT items.*, users.name AS reporter FROM items JOIN users ON items.user_id=users.id WHERE items.item_name LIKE CONCAT('%', ?, '%') OR items.location LIKE CONCAT('%', ?, '%') ORDER BY items.id DESC");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute(); $items = $stmt->get_result();
} else {
    $items = $conn->query("SELECT items.*, users.name AS reporter FROM items JOIN users ON items.user_id=users.id ORDER BY items.id DESC");
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>All Items</title>
<style>
*{box-sizing:border-box}body{font-family:Arial,sans-serif;background:#f4f6f9;margin:0;color:#222}header{background:#203864;color:white;padding:22px 6%}nav{background:white;padding:14px 6%}nav a{margin-right:18px;color:#203864;text-decoration:none;font-weight:bold}main{max-width:1200px;margin:28px auto;padding:0 16px}.panel{background:white;padding:20px;border-radius:10px;box-shadow:0 2px 8px #ddd;overflow-x:auto}input{padding:10px;border:1px solid #bbb;border-radius:5px}button,.btn{display:inline-block;background:#203864;color:white;padding:9px 12px;border:0;border-radius:5px;text-decoration:none;cursor:pointer}table{border-collapse:collapse;width:100%;margin-top:18px;min-width:950px}th,td{text-align:left;padding:11px;border-bottom:1px solid #ddd;vertical-align:middle}th{background:#edf1f8}.thumb{width:85px;height:70px;object-fit:cover;border-radius:6px}.claimed{color:green;font-weight:bold}
</style></head><body><header><h1>Lost and Found Items</h1></header>
<nav><a href="index.php">Dashboard</a><a href="add.php">Report Item</a><a href="items.php">View Items</a><a href="logout.php">Log Out</a></nav>
<main><div class="panel"><h2>All Reported Items</h2><?php if(isset($_GET["added"])): ?><p style="color:green">Item successfully added.</p><?php endif; ?>
<form method="get"><input name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search item or location"><button type="submit">Search</button> <a class="btn" href="items.php">Clear</a></form>
<table><thead><tr><th>Picture</th><th>Item</th><th>Type</th><th>Description</th><th>Location</th><th>Reported By</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if($items && $items->num_rows>0): while($item=$items->fetch_assoc()): ?><tr>
<td><?php if($item["image_name"] && is_file(__DIR__."/uploads/".$item["image_name"])): ?><a href="uploads/<?= rawurlencode($item["image_name"]) ?>" target="_blank"><img class="thumb" src="uploads/<?= rawurlencode($item["image_name"]) ?>" alt="Item picture"></a><?php else: ?>No picture<?php endif; ?></td>
<td><?= htmlspecialchars($item["item_name"]) ?></td><td><?= htmlspecialchars($item["item_type"]) ?></td><td><?= htmlspecialchars($item["description"] ?? "") ?></td><td><?= htmlspecialchars($item["location"]) ?></td><td><?= htmlspecialchars($item["reporter"]) ?></td><td><?= htmlspecialchars($item["date_reported"]) ?></td><td class="<?= $item["status"]==="Claimed"?"claimed":"" ?>"><?= htmlspecialchars($item["status"]) ?></td>
<td><?php if((int)$item["user_id"]===(int)$_SESSION["user_id"] && $item["status"]!=="Claimed"): ?><a class="btn" href="update.php?id=<?= (int)$item["id"] ?>">Mark Claimed</a><?php endif; ?><?php if((int)$item["user_id"]===(int)$_SESSION["user_id"]): ?> <a class="btn" href="delete.php?id=<?= (int)$item["id"] ?>" onclick="return confirm('Delete this item?')">Delete</a><?php endif; ?></td>
</tr><?php endwhile; else: ?><tr><td colspan="9">No items found.</td></tr><?php endif; ?>
</tbody></table></div></main></body></html>