<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: add_customer.php");
    exit;
}

$title = trim($_POST["title"] ?? "");
$firstName = trim($_POST["first_name"] ?? "");
$lastName = trim($_POST["last_name"] ?? "");
$contactNo = trim($_POST["contact_no"] ?? "");
$district = trim($_POST["district"] ?? "");

$allowedTitles = ["Mr", "Mrs", "Miss", "Dr"];

if (
    !in_array($title, $allowedTitles) ||
    strlen($firstName) < 2 ||
    strlen($lastName) < 2 ||
    !preg_match("/^[0-9+() -]{7,20}$/", $contactNo) ||
    empty($district)
) {
    header("Location: add_customer.php?error=Invalid customer data");
    exit;
}

$sql = "INSERT INTO customers (title, first_name, last_name, contact_no, district)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $title, $firstName, $lastName, $contactNo, $district);

if ($stmt->execute()) {
    header("Location: view_customer.php?message=Customer added successfully");
} else {
    header("Location: add_customer.php?error=Unable to save customer");
}

exit;