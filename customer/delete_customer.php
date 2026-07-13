<?php
require_once __DIR__ . '/../config/database.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM customer WHERE customer_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: view_customer.php?message=Customer deleted successfully");
} else {
    header("Location: view_customer.php?error=Unable to delete customer");
}

exit;