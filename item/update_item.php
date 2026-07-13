<?php
require_once __DIR__ . '/../config/database.php';

$id = (int) ($_POST['item_id'] ?? 0);
$itemCode = trim($_POST['item_code'] ?? '');
$itemName = trim($_POST['item_name'] ?? '');
$categoryId = (int) ($_POST['category_id'] ?? 0);
$subcategoryId = (int) ($_POST['subcategory_id'] ?? 0);
$quantity = (int) ($_POST['quantity'] ?? 0);
$unitPrice = (float) ($_POST['unit_price'] ?? 0);

$sql = "UPDATE item
        SET item_code = ?, item_name = ?, category_id = ?,
            subcategory_id = ?, quantity = ?, unit_price = ?
        WHERE item_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssiiidi",
    $itemCode,
    $itemName,
    $categoryId,
    $subcategoryId,
    $quantity,
    $unitPrice,
    $id
);

if ($stmt->execute()) {
    header("Location: view_item.php?message=Item updated successfully");
} else {
    header("Location: view_item.php?error=Unable to update item");
}

exit;